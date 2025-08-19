<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Muestra una lista de todos los usuarios.
     * Solo para Admins.
     */
    public function index()
    {
        // Verifica si el usuario autenticado puede ejecutar la acción 'viewAny' del UserPolicy.
        $this->authorize('viewAny', User::class);
        
        return User::all();
    }

    /**
     * Guarda un nuevo usuario.
     * Solo para Admins.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|in:user,admin', // Opcional, por defecto será 'user'
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'] ?? 'user',
        ]);

        return response()->json($user, 201);
    }

    /**
     * Muestra un usuario específico.
     * Solo para Admins.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return $user;
    }

    /**
     * Actualiza un usuario.
     * Solo para Admins.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'sometimes|in:user,admin',
        ]);

        $user->update($validatedData);

        return response()->json($user);
    }

    /**
     * Elimina un usuario.
     * Solo para Admins.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json(null, 204);
    }
}
