<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvanceCustomerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_customer_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no');
            $table->date('date');
            $table->integer('customer_id');
            $table->float('amount');
            $table->float('payment_discount')->default(0);
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advance_customer_payments');
    }
}
