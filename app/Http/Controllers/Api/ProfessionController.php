<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profession;

class ProfessionController extends Controller
{
    public function index()
    {
        // Obtenemos todas las profesiones, ordenadas por categoría y luego por nombre.
        return Profession::orderBy('category')->orderBy('name')->get();
    }
}
