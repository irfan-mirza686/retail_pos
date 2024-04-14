<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierOpeningBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_opening_balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('voucher_no');
            $table->integer('supplier_id');
            $table->string('type');
            $table->float('amount');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('supplier_opening_balances');
    }
}
