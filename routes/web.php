<?php

use App\Http\Controllers\Admin_Controller;
use App\Http\Controllers\Dashboard_Controller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::middleware(['auth'])->group(function()
{
    Route::get('/dash_board', [Dashboard_Controller::class, 'index'])->name('dash_board');

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function()
    {
        Route::get('/dash_board', [Dashboard_Controller::class, 'index'])->name('dash_board');
        
        Route::get('/employees', [Admin_Controller::class, 'employees'])->name('employees');
        Route::get('/employees/create', [Admin_Controller::class, 'create_Employees'])->name('employees.create');
        Route::post('/employees', [Admin_Controller::class, 'store_Employees'])->name('employees.store');
        Route::get('/employees/{user}/edit', [Admin_Controller::class, 'edit_Employee'])->name('employees.edit');
        Route::put('employees/{user}', [Admin_Controller::class, 'update_Employee'])->name('employees.update');
        Route::post('employees/{user}/fire', [Admin_Controller::class, 'fired_Employee'])->name('employees.fired');
    });
}
