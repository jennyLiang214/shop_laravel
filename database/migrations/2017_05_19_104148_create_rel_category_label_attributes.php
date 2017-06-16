<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelCategoryLabelAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_category_label_attributes', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('分类标签键值关联表');
            $table->integer('cid')->index()->comment('分类ID');
            $table->integer('lid')->index()->comment('标签ID');
            $table->integer('aid')->index()->comment('标签值ID');
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
        Schema::dropIfExists('rel_category_label_attributes');
    }
}
