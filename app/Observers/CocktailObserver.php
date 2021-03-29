<?php

namespace App\Observers;

use App\Models\Cocktail;

class CocktailObserver
{
    /**
     * Handle the cocktail "ingredients updated" event.
     *
     * @param Cocktail $cocktail
     */
    public function ingredientsUpdated(Cocktail $cocktail)
    {
        $cocktail->price = $cocktail->ingredients->sum('retail_price');
        $cocktail->save();
    }

    /**
     * Handle the cocktail "deleted" event.
     *
     * @param Cocktail $cocktail
     */
    public function deleting(Cocktail $cocktail)
    {
        if ($cocktail->isForceDeleting()) {
            $cocktail->ingredients()->detach();
        }
    }
}
