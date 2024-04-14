<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOpeningBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_opening_balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->integer('customer_id');
            $table->string('type');
            $table->string('description')->nullable();
            $table->float('amount');
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
        Schema::dropIfExists('customer_opening_balances');
    }
}
