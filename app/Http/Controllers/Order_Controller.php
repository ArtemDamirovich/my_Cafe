<?php

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;

class Order_Controller extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables_id',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        $total_Amount = 0;
        $items = [];

        foreach($request->items as $item)
        {
            $menu_Item = Menu::find($item['id']);
            $total_Amount += $menu_Item->price * $item['quantity'];
            $items[] = [
                'id' => $menu_Item->id,
                'name' => $menu_Item->name,
                'price' => $menu_Item->price,
                'quantity' => $item['quantity']
            ];
        }
        $order = Order::create([
            'table_id' => $request->table_id,
            'waiter_id' => $request->user()->id,
            'items' => $items,
            'total_amount' => $total_Amount,
            'notes' => $request->notes,
            'status' => 'new'
        ]);

        $table = Table::find($request->table_id);
        $table->booked();

        return response()->json([
            'success' => true,
            'order' => $order,
            'message' => 'Заказ создан'
        ]);
    }

    public function start_Cooking(Order $order)
    {
        $order->assign_To_Chef(request()->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Заказ в взят в работу'
        ]);
    }

    public function new_Orders_Cooking()
    {
        $orders = Order::with(['table', 'waiter'])->where('status', 'new')->orderBy('created_at', 'desc')->get();

        return response()->json($orders);
    }

    public function preparation_Orders () // preparation - подготовка
    {
        $orders = Order::with(['table', 'waiter'])->where('status', 'cooking')->
        where('chef_id', request()->user()->id)->orderBy('created_at', 'asc')->get(); // asc переводит старые заказы в приоритет

        return response()->json($orders);
    }

    public function ready_Order(Order $order)
    {
        $order->ready_Order();

        return response()->json([
            'success' => true,
            'message' => 'Готовый заказ'
        ]);
    }

     public function order_Waiter(Order $order)
    {
        $order->order_Waiter();

        return response()->json([
            'success' => true,
            'message' => 'Заказ подан'
        ]);
    }

    public function order_Paid(Order $order)
    {
        $order->order_Paid();

        return response()->json([
            'success' => true,
            'message' => 'Заказ оплачен'
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['table', 'waiter', 'chef']);
        return response()->json($order);
    }

    public function order_Submitted() // submitted - подан
    {
        $orders = Order::with(['table']) ->where('status', 'ready')->orderBy('created_at', 'desc')->get();

        return response()->json($orders);
    }
}
