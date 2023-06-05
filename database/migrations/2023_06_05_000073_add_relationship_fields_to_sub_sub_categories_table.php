<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSubSubCategoriesTable extends Migration
{
    public function up()
    {
        Schema::table('sub_sub_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id', 'sub_category_fk_8582893')->references('id')->on('sub_categories');
        });
    }
}
