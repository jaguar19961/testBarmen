<?php

namespace App\Http\Controllers;

use App\Http\Requests\CocktailRequest;
use App\Http\Resources\CocktailResource;
use App\Models\Cocktail;
use Illuminate\Support\Facades\DB;

class CocktailController extends Controller
{
    public function index()
    {
        return CocktailResource::collection(Cocktail::with('ingredients')->get())
            ->response()
            ->setStatusCode(200);
    }

    public function store(CocktailRequest $request)
    {
        DB::beginTransaction();

        try {
            $cocktail = Cocktail::create([
                'name' => $request['name']
            ]);

            $cocktail->setIngredients(
                $request['ingredients']
            );

            DB::commit();

            return (new CocktailResource($cocktail))
                ->response()
                ->setStatusCode(201);

        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function update(CocktailRequest $request, Cocktail $cocktail)
    {
        DB::beginTransaction();

        try {
            $cocktail->update([
                'name' => $request['name']
            ]);

            $cocktail->setIngredients(
                $request['ingredients']
            );

            DB::commit();

            return (new CocktailResource($cocktail))
                ->response()
                ->setStatusCode(200);

        } catch (\Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    public function destroy(Cocktail $cocktail)
    {
        $cocktail->delete();

        return (new CocktailResource($cocktail))
            ->response()
            ->setStatusCode(200);
    }
}
