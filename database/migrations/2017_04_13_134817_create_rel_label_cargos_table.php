<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelLabelCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_label_cargos', function (Blueprint $table) {
            $table->increments('id')->unsigned()->unsigned()->comment('分类标签值与货品关联表');
            $table->json('category_attr_ids')->comment('分类标签值ID');
            $table->integer('goods_id')->unsigned()->index()->comment('商品ID');
            $table->integer('cargo_id')->unsigned()->index()->comment('货品ID');
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
        Schema::dropIfExists('rel_label_cargos');
    }
}
