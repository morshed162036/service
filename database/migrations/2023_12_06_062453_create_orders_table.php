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
            $table->bigInteger('customer_id');
            $table->bigInteger('service_id');
            $table->bigInteger('employee_id');
            $table->date('order_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->double('price')->default(0);
            $table->enum('status',['ordered','accept','complete','cancel'])->default('ordered');
            $table->bigInteger('approved_id')->default(0);
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
