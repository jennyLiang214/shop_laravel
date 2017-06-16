<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_orders', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('订单管理表');
            $table->integer('user_id')->unsigned()->index()->comment('用户ID');
            $table->char('guid',32)->index()->comment('订单编号');
            $table->json('goods_message')->comment('json包含 单价、数量、商品ID');
            $table->json('address_message')->comment('json包含收货地址信息');
            $table->string('pay_transaction',64)->nullable()->comment('支付交易号');
            $table->tinyInteger('pay_type')->comment('支付方式 1:支付宝 2:微信 3:其他');
            $table->tinyInteger('pay_status')->index()->default(1)->comment('支付状态 1:待支付 2:已支付 3:取消支付');
            $table->decimal('total_amount',11,2)->comment('商品总金额');
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
        Schema::dropIfExists('data_orders');
    }
}
