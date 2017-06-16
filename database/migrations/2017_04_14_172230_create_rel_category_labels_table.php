<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelCategoryLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_category_labels', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('分类标签关联表');
            $table->integer('category_id')->unsigned()->index()->comment('分类ID');
            $table->integer('category_label_id')->unsigned()->index()->comment('分类标签ID');
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
        Schema::dropIfExists('rel_category_labels');
    }
}
