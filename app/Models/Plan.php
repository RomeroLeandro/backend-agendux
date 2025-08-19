<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    public function users()
{
    return $this->hasMany(User::class);
}
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_monthly',
        'price_annual',
        'features',
        'is_featured',
    ];

    protected $casts = [
        'features' => 'array',
    ];
}
