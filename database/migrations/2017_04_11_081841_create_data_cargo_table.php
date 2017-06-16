<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataCargoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_cargo', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('货品表');
            $table->integer('category_id')->unsigned()->index()->comment('分类ID');
            $table->integer('goods_id')->unsigned()->index()->comment('商品ID');
            $table->string('cargo_name')->comment('货品名称');
            $table->json('cargo_ids')->comment('货品标签');
            $table->string('cargo_cover', 255)->comment('货品封面');
            $table->integer('inventory')->default(0)->comment('库存量');
            $table->decimal('cargo_price', 7, 2)->comment('货品原价');
            $table->decimal('cargo_discount', 7, 2)->default(1)->comment('货品折扣');
            $table->integer('sales_volume')->default(0)->comment('销售量');
            $table->integer('number_of_comments')->default(0)->comment('评论数');
            $table->json('cargo_original')->comment('货品原图:多个');
            $table->text('cargo_info')->comment('货品详情');
            $table->tinyInteger('cargo_status')->default(1)->comment('货品状态 1:待售 2:上架 3:下架	默认为 1');
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
        Schema::dropIfExists('data_cargo');
    }
}
