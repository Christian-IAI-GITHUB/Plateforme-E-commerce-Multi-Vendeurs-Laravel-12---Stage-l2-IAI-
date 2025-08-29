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
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('order_date');
            $table->text('delivery_address');
            $table->enum('order_status', ['pending', 'paid', 'cancelled', 'processing', 'shipped', 'delivered']);
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['floos', 'tmoney']);
            $table->string('phone_number');
            $table->string('customer_name');
            $table->timestamps();

            // FKs
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('user_id');
            $table->index('order_status');
            $table->index('order_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_orders');
    }
};
