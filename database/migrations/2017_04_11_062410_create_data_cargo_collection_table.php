<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataCargoCollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_cargo_collection', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('货品关注表 ');
            $table->integer('user_id')->unsigned()->index()->comment('用户ID');
            $table->integer('cargo_id')->unsigned()->index()->comment('货品ID');
            $table->tinyInteger('focus_type')->default(1)->comment('关注类型如：1 到货提醒，2 降价提醒');
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
        Schema::dropIfExists('data_cargo_collection');
    }
}
