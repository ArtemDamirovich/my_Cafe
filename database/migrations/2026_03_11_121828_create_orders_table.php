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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('table_id')->constrained();
            $table->foreignId('waiter_id')->constrained();
            $table->foreignId('chef_id')->nullable()->constrained('user');
            $table->enum('status', ['new', 'cooking', 'ready', 'not_paid', 'paid'])->default('new');
            $table->json('items'); // Тут хранится список блюд
            $table->decimal('total_amount', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamp('completed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
