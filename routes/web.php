<?php

use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\PasswordController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Dashboard\PostController;
use App\Http\Controllers\Dashboard\UploadController;

Route::get('/', [HomeController::class, 'index']);

// Rota protegida por autenticação e verificação de papéis
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Grupo de rotas para gerenciamento de usuários e posts, protegido por autenticação e verificação de papéis
Route::middleware(['auth', 'admin'])->prefix('dashboard')->group(function () {
    // Rota para dar toggle no status do usuário
    Route::put('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // Rota para enviar reset de senha
    Route::post('/users/{user}/send-reset-password', [UserController::class, 'sendResetPassword'])->name('users.sendResetPassword');

    // Rotas de recurso Users
    Route::resource('users', UserController::class);

    // Rotas de recurso Posts
    Route::resource('posts', PostController::class);
    Route::post('posts/{post}/toggle-featured', [PostController::class, 'toggleFeatured'])->name('posts.toggleFeatured');

    // Rotas de recurso Categories
    Route::resource('categories', CategoryController::class);

    // Route de upload de imagens
    Route::post('/upload-image', [UploadController::class, 'uploadImage'])->name('upload.image');
});


Route::get('/password/create/{token}', function ($token) {
    return view('passwords.create', ['token' => $token]); // Crie a view correspondente
})->name('password.create');

Route::get('/password/reset/{token}', [PasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/password/store', [PasswordController::class, 'store'])->name('password.store');

// Handle cases where role is not set properly for a user
// Route::fallback(function () {
//     abort(403, 'Acesso não autorizado.');
// });
