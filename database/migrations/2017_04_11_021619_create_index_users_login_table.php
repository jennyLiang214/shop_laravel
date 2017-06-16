<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexUsersLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('index_users_login', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('用户登录索引表');
            $table->integer('user_id')->unsigned()->index()->comment('用户ID');
            $table->string('login_name',32)->unique()->comment('登录账号');
            $table->string('password',255)->comment('登录密码');
            $table->ipAddress('last_login_ip')->comment('最后一次登录IP');
            $table->timestamp('last_login_time')->comment('最后一次登录时间');
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
        Schema::dropIfExists('index_users_login');
    }
}
