<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataReceivingAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_receiving_address', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('收货地址管理表');
            $table->integer('user_id')->unsigned()->index()->comment('用户ID');
            $table->string('consignee',64)->comment('收货人');
            $table->string('tel',32)->comment('收货人手机号');
            $table->string('province',32)->comment('省份');
            $table->string('city',32)->comment('城市');
            $table->string('county',32)->comment('县');
            $table->string('detailed_address',255)->comment('详细地址');
            $table->tinyInteger('status')->default(1)->comment('地址状态 1:普通 2:默认');
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
        Schema::dropIfExists('data_receiving_address');
    }
}
