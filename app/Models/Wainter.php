<?php

use Illuminate\Contracts\Auth\Authenticatable;

class Wainter extends Authenticatable
{
    protected $fillable = 
    [
        'name',
        'login',
        'password',
        'role',
        'status',
        'hire_date',
        'fired_date'
    ];

    protected  $hidden = 
    [
        'password'
    ];
    
    public function relationship()
    {
        return $this->hasMany(Relationship::class)
    }
}