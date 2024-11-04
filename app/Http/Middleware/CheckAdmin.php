<?php
// app/Http/Middleware/CheckAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->role_id !== 1) { // Supondo que '1' é o ID do 'admin'
            return redirect('/login')->with('error', 'Acesso negado');
        }

        return $next($request);
    }
}
