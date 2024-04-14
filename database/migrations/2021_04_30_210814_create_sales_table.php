<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no');
            $table->date('date');
            $table->integer('customer_id');
            $table->integer('area_id');
            $table->tinyInteger('status');
            $table->LongText('description')->nullable();
            $table->LongText('items_addon');
            $table->integer('total_qty');
            $table->float('amount');
            $table->float('discount');
            $table->tinyInteger('added_by');
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
        Schema::dropIfExists('sales');
    }
}
