<?php

use App\Models\Ingredient;
use App\Models\Cocktail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CocktailSeeder extends Seeder
{
    private $ingredients;

    public function __construct()
    {
        $this->ingredients = Ingredient::get();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try {
            Cocktail::truncate();

            factory(Cocktail::class, 8)->create()
                ->each( fn(Cocktail $cocktail) => $cocktail->setIngredients( $this->getIngredients() ) );

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    private function getIngredients()
    {
        return $this->ingredients
            ->shuffle()
            ->take(rand(2, 7))
            ->pluck('id')
            ->toArray();
    }
}
