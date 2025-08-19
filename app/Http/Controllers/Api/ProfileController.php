<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user(); // Obtenemos el usuario autenticado

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            // El email debe ser único, pero ignorando el del propio usuario.
            'email' => ['sometimes', 'required', 'email', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validatedData);

        return response()->json($user);
    }
}
