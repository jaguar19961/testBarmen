<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CocktailIngredientPivot extends Pivot
{
    protected $table = 'cocktail_ingredient';

    protected $fillable = [
        'cocktail_id',
        'ingredient_id',
        'position'
    ];
}
