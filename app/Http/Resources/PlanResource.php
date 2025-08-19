<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
     public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => [
                'monthly' => $this->price_monthly,
                'annual' => $this->price_annual,
            ],
            'features' => $this->features,
            'isFeatured' => $this->is_featured,
            'createdAt' => $this->created_at->toIso8601String(), // Formateamos la fecha
        ];
    }
}
