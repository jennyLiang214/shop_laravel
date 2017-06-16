<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataBasicConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_basic_config', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('网站基本配置表');
            $table->string('site_name',32)->comment('网站名称');
            $table->string('site_describe',255)->comment('网站描述');
            $table->string('telephone',32)->comment('网站400电话');
            $table->string('logo',64)->comment('网站logo图片');
            $table->tinyInteger('level_set')->default(4)->comment('设置无限分类等级,默认为4级');
            $table->string('record_number',64)->comment('网站备案号');
            $table->string('address',64)->comment('公司地址');
            $table->string('copyright',64)->comment('版权信息');
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
        Schema::dropIfExists('data_basic_config');
    }
}
