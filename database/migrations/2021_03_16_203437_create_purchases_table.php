<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('purchase_no');
            $table->date('date');
            $table->integer('supplier_id');
            $table->tinyInteger('status');
            $table->text('description')->nullable();
            $table->LongText('items_addon');
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
        Schema::dropIfExists('purchases');
    }
}
