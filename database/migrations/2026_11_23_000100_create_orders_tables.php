<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20)->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 20);
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city', 80);
            $table->string('state', 80);
            $table->string('pincode', 10);
            $table->text('notes')->nullable();
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('shipping');
            $table->unsignedInteger('total');
            $table->string('payment_method', 20);          // cod | online
            $table->string('payment_status', 20)->default('pending'); // pending | paid | failed
            $table->string('status', 20)->default('placed')->index(); // awaiting_payment | placed | packed | shipped | delivered | cancelled
            $table->string('razorpay_order_id')->nullable()->index();
            $table->string('razorpay_payment_id')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->string('meta')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('price');
            $table->unsignedInteger('qty');
            $table->unsignedInteger('line_total');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
