<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'shift_type',
        'start_type',
        'end_time'
    ];

    protected $casts = [
        'date' => 'datetime:H:i',
        'end_time' => 'datetime:H:i'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function volume_To_Date($query)
    {
        return $query->whereDate('date', now()->toDateString());
    }
}
