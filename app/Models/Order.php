<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Symfony\Component\Clock\now;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'table_id',
        'waiter_id',
        'chef_id',
        'status',
        'items',
        'total_amount',
        'notes',
        'completed_at'
    ];

    protected $casts = [
        'items' => 'array',
        'ordered_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public static function loading() // Генерация номера заказа по дате и номера случайного заказа
    {
        parent::loading();

        static::creating(function($order)
        {
            if(empty($order->order_number))
                {
                    $order->order_number = 'ORD-' . now()->format('dmy') . rand(0, 10000);
                }
        });
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    public function chef()
    {
        return $this->belongsTo(User::class, 'chef_id');
    }

    public function assign_To_Chef($chef_id)
    {
        $this->chef_id = $chef_id;
        $this->status = 'cooking';
        $this->save();
    }

    public function mark_To_Ready()
    {
        $this->status = 'ready';
        $this->save();
    }

    public function mark_To_Served()
    {
        $this->status = 'not_paid';
        $this->save();
    }

    public function mark_To_Paid()
    {
        $this->status = 'paid';
        $this->completed_at = now();
        $this->save();
        if($this->table)
            {
                $this->table->free();
            }
    }
}
