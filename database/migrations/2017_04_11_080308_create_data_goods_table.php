<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_goods', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('商品表');
            $table->integer('category_id')->unsigned()->index()->comment('分类ID');
            $table->string('goods_title', 255)->comment('商品标题');
            $table->tinyInteger('goods_status')->default(1)->comment('商品状态 1:待售 2:上架 3:下架	默认为 1');
            $table->json('goods_original')->comment('商品原图:多个');
            $table->text('goods_info')->comment('商品详情');
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
        Schema::dropIfExists('data_goods');
    }
}
