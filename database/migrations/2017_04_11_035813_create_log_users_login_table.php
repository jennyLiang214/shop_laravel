<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogUsersLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_users_login', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('登陆日志表');
            $table->integer('user_id')->unsigned()->index()->comment('用户ID');
            $table->string('login_name',32)->comment('登陆账号');
            $table->tinyInteger('third_party')->comment('第三方登陆: 1 网站 2 微信 3 微博 4 QQ ...');
            $table->ipAddress('login_ip')->comment('登录IP');
            $table->timestamp('login_time')->comment('登录时间');
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
        Schema::dropIfExists('log_users_login');
    }
}
