<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelGoodsLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_goods_label', function (Blueprint $table) {
            $table->integer('goods_id')->unsigned()->index()->comment('商品ID');
            $table->integer('goods_label_id')->unsigned()->index()->comment('商品标签ID');
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
        Schema::dropIfExists('rel_goods_label');
    }
}
