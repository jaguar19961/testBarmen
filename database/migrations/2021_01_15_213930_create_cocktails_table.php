<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCocktailsTable extends Migration
{
    public function up()
    {
        Schema::create('cocktails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->double('price', 16, 2)
                ->nullable()
                ->default(null);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['deleted_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cocktails');
    }
}
