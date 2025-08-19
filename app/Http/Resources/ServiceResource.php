<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class ServiceResource extends JsonResource
{
     public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'durationInMinutes' => $this->duration_in_minutes, // Usamos camelCase para consistencia
            // Anidamos la información del propietario usando nuestro UserResource
            'owner' => new UserResource($this->user), 
            'createdAt' => $this->created_at->toIso8601String(),
        ];
    }
}
