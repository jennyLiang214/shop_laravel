<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('index_goods', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('商品索引表');
            $table->integer('goods_id')->unsigned()->index()->comment('商品ID');
            $table->integer('cargo_id')->unsigned()->index()->comment('货品ID');
            $table->text('body')->comment('中文分词后的内容');
            $table->timestamps();
            $table->softDeletes()->comment('软删除');
        });
        // 添加全文本索引
        \DB::statement('ALTER TABLE `index_goods` ADD FULLTEXT(`body`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('index_goods');
    }

}
