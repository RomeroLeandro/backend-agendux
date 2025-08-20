<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\ServiceResource;


class ServiceController extends Controller
{
    use AuthorizesRequests;

    public function index(User $user)
    {
        return $user->services;
    }

    public function store(Request $request)
    {
        $this->authorize('create', Service::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_in_minutes' => 'required|integer|min:1',
        ]);

        $service = $request->user()->services()->create($validated);

        return new ServiceResource($service);
    }

    public function show(Service $service)
    {
        return $service;
    }

    public function update(Request $request, Service $service)
    {
        $this->authorize('update', $service);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'duration_in_minutes' => 'sometimes|required|integer|min:1',
        ]);
        
        $service->update($validated);

        return new ServiceResource($service);
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);
        
        $service->delete();

        return response()->json(null, 204);
    }

}
