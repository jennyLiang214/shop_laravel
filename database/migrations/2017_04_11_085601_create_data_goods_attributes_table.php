<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataGoodsAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_goods_attributes', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('商品标签值表');
            $table->integer('goods_label_id')->unsigned()->index()->comment('商品标签ID');
            $table->string('goods_label_name',64)->comment('商品标签值名称');
            $table->timestamps();
            $table->softDeletes()->comment('软删除');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_goods_attributes');
    }
}
