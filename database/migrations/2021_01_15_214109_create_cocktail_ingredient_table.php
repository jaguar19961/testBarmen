<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCocktailIngredientTable extends Migration
{
    public function up()
    {
        Schema::create('cocktail_ingredient', function (Blueprint $table) {
            $table->unsignedInteger('cocktail_id');
            $table->unsignedInteger('ingredient_id');
            $table->integer('position')->default(0);

            $table->unique(['cocktail_id', 'ingredient_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cocktail_ingredient');
    }
}
