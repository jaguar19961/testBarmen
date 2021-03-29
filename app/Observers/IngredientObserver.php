<?php

namespace App\Observers;

use App\Models\Ingredient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IngredientObserver
{
    /**
     * Handle the ingredient "deleted" event.
     *
     * @param Ingredient $ingredient
     */
    public function deleted(Ingredient $ingredient)
    {
        DB::beginTransaction();

        try {
            $ingredient->cocktails()->decrement('price', $ingredient->retail_price);
            $ingredient->cocktails()->detach();

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updated(Ingredient $ingredient)
    {
        DB::beginTransaction();
        try {
            if ($ingredient->isDirty('is_alcohol', 'price')) {
                $originalPrice = $ingredient->getOriginal('retail_price');
                $newPrice = $ingredient->getAttribute('retail_price');

                $diff = $newPrice - $originalPrice;

                if (numberIsPositive($diff)) {
                    $ingredient->cocktails()->increment('price', $diff);
                } else {
                    $ingredient->cocktails()->increment('price', $diff);
                }
            }
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
