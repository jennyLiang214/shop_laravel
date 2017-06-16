<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataActivitysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_activitys', function (Blueprint $table) {
            $table->increments('id')->comment('商品活动表');
            $table->string('name', 100)->comment('活动名称');
            $table->string('desc', 200)->comment('活动描述');
            $table->tinyInteger('type')->comment('活动类型 1:秒杀 2:特惠 3:团购 4:超值');
            $table->integer('start_timestamp')->unsigned()->comment('活动开始时间戳');
            $table->smallInteger('length')->unsigned()->comment('活动时长');
            $table->integer('end_timestamp')->unsigned()->comment('活动结束时间戳');
            $table->tinyInteger('is_over')->default(0)->comment('结束标识 0 未结束 1 已结束 默认 0');
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
        Schema::dropIfExists('data_activitys');
    }
}
