<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelGoodsActivitysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_goods_activitys', function (Blueprint $table) {
            $table->increments('id')->comment('商品活动关联表');
            $table->integer('activity_id')->unsigned()->index()->comment('活动ID');
            $table->integer('cargo_id')->unsigned()->index()->comment('货品ID');
            $table->smallInteger('number')->comment('用来做活动的商品数量');
            $table->decimal('promotion_price')->comment('促销价');
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
        Schema::dropIfExists('rel_goods_activitys');
    }
}
