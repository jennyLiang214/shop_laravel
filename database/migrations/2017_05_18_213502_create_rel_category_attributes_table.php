<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelCategoryAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_category_attributes', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('分类标签值关联表');
            $table->integer('category_id')->index()->comment('分类ID');
            $table->integer('category_attribute_id')->index()->comment('标签值ID');
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
        Schema::dropIfExists('rel_category_attributes');
    }
}
