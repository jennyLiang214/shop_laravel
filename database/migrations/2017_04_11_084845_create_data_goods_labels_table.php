<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataGoodsLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_goods_labels', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('商品标签表');
            $table->integer('category_id')->unsigned()->index()->comment('分类ID');
            $table->string('goods_label_name',32)->comment('商品标签名称');
            $table->tinyInteger('goods_label_status')->default(1)->comment('启用禁用  1:启用 2 禁用 | 默认为 1');
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
        Schema::dropIfExists('data_goods_labels');
    }
}
