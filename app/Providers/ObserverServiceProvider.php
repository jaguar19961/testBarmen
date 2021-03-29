<?php

namespace App\Providers;

use App\Models\Ingredient;
use App\Models\Cocktail;
use App\Observers\IngredientObserver;
use App\Observers\CocktailObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        Ingredient::observe(IngredientObserver::class);
        Cocktail::observe(CocktailObserver::class);
    }
}
