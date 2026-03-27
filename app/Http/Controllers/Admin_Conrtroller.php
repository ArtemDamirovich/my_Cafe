<?php

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class Admin_Controller extends Controller
{
    public function employees()
    {
        $employees = User::with('shifts')->get();

        return view('admin.employees.index', compact('employees'));
    }

    public function create_Employees()
    {
        return view('admin.employees.create');
    }

    public function ctore_Employees(Request $requesr)
    {
        $requesr -> validate([
            'name' => 'required',
            'login' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
            'role' => 'required|in:admin, chef, waiter',
            'hire_date' => 'required|date'
        ]);

        User::create([
            'name' => $requesr->name,
            'login' => $requesr->login,
            'email' => $requesr->email,
            'password' => bcrypt($requesr->password),
            'role' => $requesr->role,
            'hire_date' => $requesr->hire_date,
            'status' => 'active'
        ]);

        return redirect()->route('admin.employees')->with('message', 'Сотрудник добавлен');
    }

    public function edit_Employee(User $user)
    {
        return view('admin.employees.edit', compact('user'));
    }

    public function update_Employee(Request $requesr, User $user)
    {
        $requesr->validate([
            'name' => 'required',
            'login' => 'required|unique:users.login' . $user->id,
            'email' => 'required|email|unique:users.email' . $user->id,
            'role' => 'required|in:admin, chef, waiter'
        ]);

        $user->update($requesr->only(['name', 'login', 'email', 'role']));

        return redirect()->route('admin.employees')->with('message', 'Данные сотрудника обновлены');
    }
}