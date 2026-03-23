<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;

class Dashboard_Controller extends Controller // Dashboard - панель управления 
{ 
    private function admin_Dashboard() // общее колличество сотрудников 
    {
        $stats = [
            'total_Employees' => User::where('status', 'active')->count(), // Employees - сотрудники 
            'tatol_Table' => Table::count(),
            'tatol_Orders' => Order::whereDate('created_at', today())->count(),
            'revenue_todye' => Order::whereDate('created_at', today())->where('status', 'paid')->sum('total_amount')
        ];

        $number_Orders = Order::with(['table', 'waiter'])->latest()->take(15)->get(); // показывает новые заказы новые будут первыми и ограничено до 15

        $employees = User::where('status', 'active')->get();

        return view('admin.dash_board', compact('stats', 'number_Orders', 'employees'));
    }

    private function chef_Dashboard(Request $request)
    {
        $new_Orders = Order::with(['table', 'waiter'])->where('status', 'new')->get(); // закрепляет к каждому заказу стол и официанта, ищет новые заказы

        $cooking_Orders = Order::with(['table', 'waiter'])->where('status', 'cooking')->where('chef_id', $request->user()->id)->get();

        return view('chef.dash_board', compact('new_Orders', 'cooking_Orders'));
    }

    private function waiter_Dashboard(Request $request)
    {
        $free_Tables = Table::where('status', 'free')->get();

        $my_Orders = Order::with(['table'])->where('waiter_id', $request->user()->id)->whereIn('status', ['new', 'cooking', 'ready'])->get();

        $ready_Orders = Order::with(['table'])->where('status', 'ready')->get();

        $menu_Items = \Menu::accessible()->get();

        return view('waiter.dash_board', compact('free_Tables', 'my_Orders', 'ready_Orders', 'menu_Items'));
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) 
        {
             return redirect()->route('login');
        }

        if($user->is_Admin())
        {
            return $this->admin_Dashboard();
        }
        elseif($user->is_Chef())
        {
            return $this->chef_Dashboard($request);
        }
        else
        {
            return $this->waiter_Dashboard($request);
        }
    }
}