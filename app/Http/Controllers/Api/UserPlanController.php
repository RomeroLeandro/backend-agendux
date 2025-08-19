<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class UserPlanController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $user = $request->user();
        $user->plan_id = $validated['plan_id'];
        $user->save();

        return response()->json(['message' => 'Plan actualizado exitosamente.']);
    }
}
