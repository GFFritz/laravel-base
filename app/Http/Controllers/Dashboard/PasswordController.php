<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Encontrar o usuário usando o token
        $user = User::where('reset_token', $request->token)->first();
        // dd($user);
        if (!$user) {
            return redirect()->back()->with('error', 'Token inválido.'); // Se o token não for encontrado
        }

        // Atualizando a senha e limpando o token
        $user->password = Hash::make($request->password); // Define a nova senha
        $user->reset_token = null; // Limpa o token
        $user->status = 1; // Marca a conta como ativa
        $user->save(); // Salva as alterações

        return redirect()->route('login')->with('success', 'Senha criada com sucesso. Você pode agora fazer login.');
    }

    public function showResetForm($token)
    {

        // Verifique se o token existe e é válido
        $user = User::where('reset_token', $token)->first();

        // Se o token não for válido, redirecionar para a rota raiz
        if (!$user) {
            return redirect('/')->with('error', 'Token inválido ou expirado.');
        }

        return view('passwords.create', ['token' => $token]); // Se válido, mostra a view
    }
}
