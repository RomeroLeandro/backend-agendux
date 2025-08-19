<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


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

    public function updatePassword(Request $request)
    {
        // 1. Validamos los datos de entrada
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        // 2. Verificamos que la contraseña actual sea correcta
        if (! Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['La contraseña actual no es correcta.'],
            ]);
        }

        // 3. Actualizamos la contraseña con la nueva
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // 4. Devolvemos una respuesta exitosa
        return response()->json(['message' => 'Contraseña actualizada exitosamente.']);
    }
}
