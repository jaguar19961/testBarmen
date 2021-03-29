<?php

use App\Models\Ingredient;
use App\Models\CocktailIngredientPivot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try {
            Ingredient::truncate();
            CocktailIngredientPivot::truncate();
            factory(Ingredient::class, 10)->create();
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
