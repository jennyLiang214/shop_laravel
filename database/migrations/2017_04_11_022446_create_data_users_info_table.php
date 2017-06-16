<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataUsersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_users_info', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('用户信息表');
            $table->integer('user_id')->unsigned()->unique()->comment('用户ID');
            $table->string('nickname',64)->nullable()->comment('用户昵称');
            $table->string('realname',64)->nullable()->comment('真实姓名');
            $table->string('email',32)->nullable()->comment('邮箱');
            $table->string('tel',32)->nullable()->comment('手机');
            $table->string('avatar',64)->nullable()->comment('头像');
            $table->tinyInteger('sex')->nullable()->comment('性别:1男 2 女 ');
            $table->string('id_number',32)->nullable()->comment('身份证号码');
            $table->string('answer_1',64)->nullable()->comment('密保问题1');
            $table->string('answer_2',64)->nullable()->comment('密保问题2');
            $table->string('birthday',32)->nullable()->comment('生日');
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
        Schema::dropIfExists('data_users_info');
    }
}
