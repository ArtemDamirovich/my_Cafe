<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;

class Dashboard_Controller extends Controller // Dashboard - панель управления 
{ 
    private function admin_Doshboard() // общее колличество сотрудников 
    {
        $stats = [
            'total_Employees' => User::where('status', 'active')->count(),
            'tatol_Table' => Table::count(),
            'tatol_Orders' => Order::whereDate('')
        ];
    }

    public function index()
    {
        $user = auth()->user();

        if($user->is_Admin())
            {
                return $this->admin_Doshboard();
            }
        elseif($user->is_Chef())
            {
                return $this->chef_Doshboard();
            }
        else
            {
                return $this->waiter_Doshboard();
            }
    }
}