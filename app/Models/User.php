<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
     protected $fillable =
    [
        'name',
        'login',
        'password',
        'email',
        'role',
        'status',
        'hire_date',
        'fired_date'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected  $hidden =
    [
        'password',
        'remember_token'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'hire_date' => 'date',
            'fired_date' => 'date'
        ];
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function waiter_Orders()
    {
        return $this->hasMany(Order::class, 'waiter_id');
    }

    public function chef_Orders()
    {
        return $this->hashed(Order::class, 'chef_id');
    }

    public function is_Admin()
    {
        return $this->role == 'admin';
    }

    public function is_Chef()
    {
         return $this->role == 'chef';
    }

    public function is_Waiter()
    {
         return $this->role == 'waiter';
    }

    public function is_Active()
    {
        return $this->role == 'active';
    }

    public function fire()
    {
        $this->status = 'fired';
        $this->fired_date = now();
        $this->save();
    }

    public function assign_Role($role)
    {
        $this->role = $role;
        $this->save();
    }
}
