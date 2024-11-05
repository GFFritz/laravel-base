<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\CreatePasswordMail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        // Filtro de status
        if ($request->has('status') && in_array($request->status, [0, 1])) {
            $query->where('status', $request->status);
        }

        $users = $query->get(); // Pega os usuários filtrados

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all(); // Busca todas as roles
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Gera um token aleatório
        $token = Str::random(60);
        // Exibir token para debugging (remova em produção)
        // dd($token); // Apenas para verificar se o token está sendo gerado

        // Cria um novo usuário
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make(Str::random(8)), // A senha gerada (opcional)
            'role_id' => $validatedData['role_id'],
            'status' => 0, // Define a conta como inativa
            'reset_token' => $token, // Gera um token aleatório
        ]);

        // Enviando e-mail com um link para criar a nova senha, incluindo o token na URL
        Mail::to($user->email)->send(new CreatePasswordMail($token));

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso. Um e-mail foi enviado para criar a senha.');
    }

    public function edit(User $user)
    {
        $roles = Role::all(); // Busca todas as roles para o formulário
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|boolean',
        ]);

        $user->update($validatedData);
        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuário removido com sucesso.');
    }

    public function toggleStatus(User $user)
    {
        $user->status = !$user->status; // Altera o status
        $user->save();
        return redirect()->route('users.index')->with('success', 'Status do usuário alterado com sucesso.');
    }

    public function sendResetPassword(Request $request, User $user)
    {
        // Gerar o token aleatório
        $token = Str::random(60);

        // Salvar o token no banco de dados
        $user->reset_token = $token; // Atualiza o usuário com o novo token
        $user->status = 0; // Atualiza o usuário com o novo token
        $user->save(); // Salva a mudança no banco

        // Enviar o e-mail com o link para redefinir a senha
        Mail::to($user->email)->send(new \App\Mail\ResetPasswordMail($token));

        return redirect()->route('users.index')->with('success', 'Email de reset de senha enviado com sucesso.');
    }
}
