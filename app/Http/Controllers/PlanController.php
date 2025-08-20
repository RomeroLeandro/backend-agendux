<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Resources\PlanResource;

class PlanController extends Controller
{
    /**
     * Muestra una lista de todos los planes.
     * (Público)
     */
    public function index()
    {
        return PlanResource::collection(Plan::all());
    }

    /**
     * Muestra un plan específico.
     * (Público)
     */
    public function show(Plan $plan)
    {
        return new PlanResource($plan);
    }

    /**
     * Guarda un nuevo plan en la base de datos.
     * (Solo para Admins)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|array',
            'price.monthly' => 'required|numeric|min:0',
            'price.annual' => 'required|numeric|min:0',
            'features' => 'required|array',
            'features.*' => 'string|max:255',
            'isFeatured' => 'required|boolean',
            'extraReminderCost' => 'nullable|numeric|min:0',
        ]);

        $plan = Plan::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'price_monthly' => $validatedData['price']['monthly'],
            'price_annual' => $validatedData['price']['annual'],
            'features' => $validatedData['features'],
            'is_featured' => $validatedData['isFeatured'],
            'extra_reminder_cost' => $validatedData['extraReminderCost'] ?? null,
        ]);
        return new PlanResource($plan);
    }

    /**
     * Actualiza un plan existente.
     * (Solo para Admins)
     */
    public function update(Request $request, Plan $plan)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|array',
            'price.monthly' => 'sometimes|required|numeric|min:0',
            'price.annual' => 'sometimes|required|numeric|min:0',
            'features' => 'sometimes|required|array',
            'features.*' => 'string|max:255',
            'isFeatured' => 'sometimes|required|boolean',
            'extraReminderCost' => 'sometimes|nullable|numeric|min:0',
            'extra_reminder_cost' => $validatedData['extraReminderCost'] ?? null,
        ]);

        $dataToUpdate = [];
        if (isset($validatedData['name'])) $dataToUpdate['name'] = $validatedData['name'];
        if (isset($validatedData['description'])) $dataToUpdate['description'] = $validatedData['description'];
        if (isset($validatedData['features'])) $dataToUpdate['features'] = $validatedData['features'];
        if (isset($validatedData['isFeatured'])) $dataToUpdate['is_featured'] = $validatedData['isFeatured'];
        if (isset($validatedData['price']['monthly'])) $dataToUpdate['price_monthly'] = $validatedData['price']['monthly'];
        if (isset($validatedData['price']['annual'])) $dataToUpdate['price_annual'] = $validatedData['price']['annual'];
        if (isset($validatedData['extraReminderCost'])) {
            $dataToUpdate['extra_reminder_cost'] = $validatedData['extraReminderCost'];
        }

        $plan->update($dataToUpdate);

        return new PlanResource($plan);
    }

    /**
     * Elimina un plan.
     * (Solo para Admins)
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();

        return response()->json(null, 204);
    }
}
