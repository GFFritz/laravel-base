<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return view('dashboard.admin');
        } elseif ($user->isModerator()) {
            return view('dashboard.moderator');
        } else {
            abort(403, 'Acesso não autorizado.');
        }
    }
}
