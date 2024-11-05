<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\PasswordController;
use App\Http\Controllers\Front\HomeController;

Route::get('/', [HomeController::class, 'index']);

// Rota protegida por autenticação e verificação de papéis
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Grupo de rotas para gerenciamento de usuários, protegido por autenticação e verificação de papéis
Route::middleware(['auth', 'admin'])->group(function () {
    // Rota para dar toggle no status do usuário
    Route::put('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // Rota para enviar reset de senha
    Route::post('/users/{user}/send-reset-password', [UserController::class, 'sendResetPassword'])->name('users.sendResetPassword');

    // Rotas de recurso
    Route::resource('users', UserController::class);
});

Route::get('/password/create/{token}', function ($token) {
    return view('passwords.create', ['token' => $token]); // Crie a view correspondente
})->name('password.create');

Route::get('/password/reset/{token}', [PasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/password/store', [PasswordController::class, 'store'])->name('password.store');

// // Handle cases where role is not set properly for a user
// Route::fallback(function () {
//     abort(403, 'Acesso não autorizado.');
// });
