<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cocktail extends Model
{
    use SoftDeletes;

    protected $table = 'cocktails';

    protected $fillable = [
        'name', 'price'
    ];

    protected $observables = [
        'ingredientsUpdated'
    ];

    protected $casts = [
        'price' => 'float'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ingredients()
    {
        return $this->belongsToMany(
            Ingredient::class,
            'cocktail_ingredient',
            'cocktail_id',
            'ingredient_id',
            'id',
            'id'
        )
            ->withPivot('position')
            ->using(CocktailIngredientPivot::class)
            ->orderBy('cocktail_ingredient.position');
    }

    /**
     * Method used to sync product ingredients that fires
     * custom model event handled by observer
     *
     * @param array $ids
     * @return array
     */
    public function setIngredients(array $ids): array
    {
        $ingredients = $this->ingredients()->sync($ids);
        $this->fireModelEvent('ingredientsUpdated', false);

        return $ingredients;
    }

    /**
     * Check if cocktail has cached price and return it or calculate sum on fly
     *
     * @param $price
     * @return float|null
     */
    public function getPriceAttribute($price): ?float
    {
        return $price ?? $this->ingredients->sum('retail_price');
    }

    /**
     * Attribute returns true if any of ingredients is alcoholic
     *
     * @return bool
     */
    public function getHasAlcoholAttribute(): bool
    {
        return $this->ingredients->where('is_alcohol', true)->count();
    }
}
