<?php

namespace App\Http\Controllers;

use App\Http\Requests\IngredientDeleteRequest;
use App\Http\Requests\IngredientRequest;
use App\Http\Resources\IngredientResource;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function index()
    {
        return IngredientResource::collection(Ingredient::get())
            ->response()
            ->setStatusCode(200);
    }

    /**
     * @param IngredientRequest $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function store(IngredientRequest $request)
    {
        $ingredient = Ingredient::create([
            'name' => $request['name'],
            'price' => $request['price'],
            'is_alcohol' => $request['is_alcohol']
        ]);

        return (new IngredientResource($ingredient))
            ->response()
            ->setStatusCode(201);
    }


    /**
     * @param IngredientRequest $request
     * @param Ingredient $ingredient
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function update(IngredientRequest $request, Ingredient $ingredient)
    {
        $ingredient->update([
            'name' => $request['name'],
            'price' => $request['price'],
            'is_alcohol' => $request['is_alcohol']
        ]);

        return (new IngredientResource($ingredient))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * @param IngredientDeleteRequest $request
     * @param Ingredient $ingredient
     * @return \Illuminate\Http\JsonResponse|object
     * @throws \Exception
     */
    public function destroy(IngredientDeleteRequest $request, Ingredient $ingredient)
    {
        $ingredient->delete();

        return (new IngredientResource($ingredient))
            ->response()
            ->setStatusCode(200);
    }
}
