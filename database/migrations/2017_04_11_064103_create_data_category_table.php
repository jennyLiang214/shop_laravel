<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_category', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('分类表');
            $table->integer('pid')->unsigned()->index()->comment('父级ID');
            $table->string('name',32)->comment('分类名称');
            $table->tinyInteger('level')->comment('分类层级');
            $table->string('describe',255)->nullable()->comment('分类描述');
            $table->string('img',64)->nullable()->comment('分类的图标');
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
        Schema::dropIfExists('data_category');
    }
}
