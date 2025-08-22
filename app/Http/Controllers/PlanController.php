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
            'extra_reminder_cost' => $request->input('extraReminderCost'), 
        ]);
        return new PlanResource($plan);
    }

    /**
     * Actualiza un plan existente.
     * (Solo para Admins)
     */
public function update(Request $request, Plan $plan)
{
    // La Policy ya protege esta acción, así que solo los admins pueden llegar aquí.
    
    $validatedData = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'sometimes|required|array',
        'price.monthly' => 'sometimes|required|numeric|min:0',
        'price.annual' => 'sometimes|required|numeric|min:0',
        'features' => 'sometimes|nullable|array',
        'isFeatured' => 'sometimes|required|boolean',
        'extraReminderCost' => 'sometimes|nullable|numeric|min:0',
    ]);

    // Creamos un array solo con los datos que vamos a actualizar
    $dataToUpdate = [];
    
    // Usamos $request->has() para verificar si el campo fue enviado
    if ($request->has('name')) $dataToUpdate['name'] = $validatedData['name'];
    if ($request->has('description')) $dataToUpdate['description'] = $validatedData['description'];
    if ($request->has('features')) $dataToUpdate['features'] = $validatedData['features'];
    if ($request->has('isFeatured')) $dataToUpdate['is_featured'] = $validatedData['isFeatured'];
    if ($request->has('extraReminderCost')) $dataToUpdate['extra_reminder_cost'] = $validatedData['extraReminderCost'];

    if ($request->has('price.monthly')) $dataToUpdate['price_monthly'] = $validatedData['price']['monthly'];
    if ($request->has('price.annual')) $dataToUpdate['price_annual'] = $validatedData['price']['annual'];
    
    // Actualizamos el plan solo con los datos presentes
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
