<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use SoftDeletes;

    const NON_ALCOHOL_ADDITION = 50;

    const ALCOHOL_ADDITION = 100;

    protected $table = 'ingredients';

    protected $fillable = [
        'name',
        'price',
        'is_alcohol'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_alcohol' => 'boolean'
    ];

    protected $appends = [
        'retail_price'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cocktails()
    {
        return $this->belongsToMany(
            Cocktail::class,
            'cocktail_ingredient',
            'ingredient_id',
            'cocktail_id',
            'id',
            'id'
        )
            ->withPivot('position')
            ->using(CocktailIngredientPivot::class);
    }

    /**
     * Attribute with on fly calculated retail price
     *
     * @return float
     */
    public function getRetailPriceAttribute(): float
    {
        $addition = !$this->is_alcohol ? self::NON_ALCOHOL_ADDITION : self::ALCOHOL_ADDITION;

        return addPercentTo(
            $this->price,
            $addition
        );
    }
}
