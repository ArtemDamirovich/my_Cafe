<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('waiter', function (Blueprint $table) {
            $table->string('login')->unique()->after('name');
            $table->enum('role', ['admin', 'chef', 'waiter'])->default('waiter')->after('password');
            $table->enum('status', ['active', 'fired']);
            $table->date('hire_date')->nullable()->after('status');
            $table->date('fired_date')->nullable()->after('hire_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waiter', function (Blueprint $table) {
            $table->dropColumn(['login', 'role', 'status', 'hire_date', 'fired_date']);
        });
    }
};
