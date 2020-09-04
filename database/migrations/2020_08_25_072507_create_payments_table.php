<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('manager_id');
            $table->string('contract_no');
            $table->string('seq');
            $table->string('amount');
            $table->timestamp('payment_date', 0)->nullable();
            $table->timestamp('deadline', 0)->nullable();
            $table->timestamp('current_payment_day', 0)->nullable();
            $table->string('paid');
            $table->string('remain');
            $table->string('percent')->default(0);
            $table->string('amount_percent')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}