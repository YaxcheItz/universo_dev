<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function buscar(Request $request)
    {
        $query = $request->get('q');
        $userId = auth()->id();

        return User::where('id', '!=', $userId)   // SE EXCLUYE USUARIO ACTUAL para mostrar en creacion de equipo
            ->where('name', 'like', '%' . ltrim($query, '@') . '%')
            ->limit(5)
            ->get(['id', 'name']);
    }


}
