<?php

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\Table;
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

    public function ctore_Employees(Request $request)
    {
        $request -> validate([
            'name' => 'required',
            'login' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
            'role' => 'required|in:admin, chef, waiter',
            'hire_date' => 'required|date'
        ]);

        User::create([
            'name' => $request->name,
            'login' => $request->login,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'hire_date' => $request->hire_date,
            'status' => 'active'
        ]);

        return redirect()->route('admin.employees')->with('message', 'Сотрудник добавлен');
    }

    public function edit_Employee(User $user)
    {
        return view('admin.employees.edit', compact('user'));
    }

    public function update_Employee(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'login' => 'required|unique:users.login' . $user->id,
            'email' => 'required|email|unique:users.email' . $user->id,
            'role' => 'required|in:admin, chef, waiter'
        ]);

        $user->update($request->only(['name', 'login', 'email', 'role']));

        return redirect()->route('admin.employees')->with('message', 'Данные сотрудника обновлены');
    }

    public function fired_Employee(User $user)
    {
        $user->fire();

        return redirect()->route('admin.employees')->with('message', 'Сотрудник уволен');
    }

    public function shifts()
    {
        $shifts = Shift::with('user')->whereMonth('date', now()->month)->get()->groupBy('date');

        $employees = User::with('status', 'active')->whereNotIn('role', ['admin'])->where('status', '!=', 'fired')->get();

        return view('admin.shifts.index', compact('shifts', 'employees'));
    }

    public function assign_Shift(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users, id',
            'date' => 'required|date',
            'shift_type' => 'required|in:morning,day,evening,night',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time'
        ]);

        $user = User::find($request->user_id);

        if($user->status == 'fired')
        {
            return back()->withErrors(['user_id' => 'Сотрудник уволен']);
        }

        Shift::create($request->all());

        return redirect()->route('admin.shifts')->with('message', 'смена насзначена');
    }

    public function tables()
    {
        $tables = Table::with('current_Order')->get();

        return view('admin.tables.index', compact('tables'));
    }

    public function store_Table(Request $request)
    {
        $request->validate([
            'table_number' => 'required|integer|unique:tables',
            'seats' => 'required|integer|min:1'
        ]);

        $tables_Count = Table::count();

        if($tables_Count >= 30)
        {
            return back()->withErrors(['tables_Count' => 'Достигнут лимит столиков']);
        }

        Table::create($request->all());

        return redirect()->route('admin.tables')->with('message', 'Столик добавлен');
    }

    public function update_Table(Request $request, Table $table)
    {
        $request->validate([
            'table_number' => 'required|integer|unique:tables,table_number,' . $table->id,
            'seats' => 'required|integer|min:1'
        ]);

        $table->update($request->all());

        return redirect('admin.tables')->with('message', 'Столик обновлен');
    }

    public function delete_Table(Table $table)
    {
        if($table->status == 'booked')
        {
            return back()->with('error', 'Стоик нельзя удалить, занят');
        }

        $table->delete();

        return redirect()->route('admin.tables')->with('message', 'Столик удален');
    }
}
