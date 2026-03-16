<?php

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = ['menu_items'];

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'is_accessible' // доступен
    ];

    protected $casts = [
        'is_accessible' => 'boolean'
    ];

    public function scope_Accessible($query) // объем доступный 
    {
        return $query->where('is_accessible', true);
    }

    public function scope_By_Category($query, $category)
    {
        return $query->where('category', $category);
    }
}