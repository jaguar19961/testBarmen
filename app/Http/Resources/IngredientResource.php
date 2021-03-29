<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Ingredient */
class IngredientResource extends JsonResource
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
            'price' => $this->price,
            'is_alcohol' => $this->is_alcohol,
            'retail_price' => $this->retail_price,
            'position' => $this->pivot->position ?? 0
        ];
    }
}
