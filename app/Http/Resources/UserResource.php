<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
        'firstName' => $this->first_name,
        'lastName' => $this->last_name,
        'email' => $this->email,
        'phone' => $this->phone,
        'profession' => $this->profession,
        'businessName' => $this->business_name,
        'role' => $this->role,
        'plan' => new PlanResource($this->whenLoaded('plan')),
        'registeredAt' => $this->created_at->toIso8601String(),
        ];
    }
}
