<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Cocktail */
class CocktailResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => round($this->price, 2),
            'has_alcohol' => $this->has_alcohol,

            'ingredients' => IngredientResource::collection(
                $this->whenLoaded('ingredients')
            ),
        ];
    }
}
