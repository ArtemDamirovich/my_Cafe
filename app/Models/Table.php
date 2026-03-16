<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'status',
        'seats'      
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function current_Order()
    {
        return $this->hasMany(Order::class)->whereIn('status', ['new', 'cooking', 'ready']);
    }

    public function is_free() // Проверить, свободен ли столик
    {
        return $this->status == 'free';
    }

    public function booked() // Забронированый столик
    {
        $this->status == 'booked';
        $this->save();
    }

    public function free() // Освободить столик (когда гости ушли)
    {
        $this->status == 'free';
        $this->save();
    }
}
