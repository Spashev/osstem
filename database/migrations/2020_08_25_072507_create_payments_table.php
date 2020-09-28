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
            $table->string('hash')->unique();
            $table->bigInteger('contract_id');
            $table->string('seq');
            $table->string('amount');
            $table->timestamp('payment_date', 0)->nullable();
            $table->timestamp('deadline', 0)->nullable();
            $table->string('paid')->default(0);
            $table->string('remain');
            $table->string('percent')->default(0);
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