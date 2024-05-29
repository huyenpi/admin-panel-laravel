<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 15)->unique();
            $table->integer('product_quantity');
            $table->integer('total_amount');
            $table->enum('payment_method', ['COD', 'Online Payment'])->default('COD');
            $table->string('customer_name', 100);
            $table->string('shipping_phone', 20);
            $table->string('shipping_address');
            $table->enum('status', ['placed', 'shipped', 'delivered', 'canceled'])->default('placed');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
