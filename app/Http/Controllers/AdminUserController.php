<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    /**
     * Mostrar a lista de utilizadores.
     */
    public function index()
    {
        $users = User::all(); // Obter todos os utilizadores.
        return view('admin.users.index', compact('users')); // Retorna a vista com os utilizadores.
    }

    /**
     * Mostrar o formulário de criação de utilizadores.
     */
    public function create()
    {
        return view('admin.users.create'); // Retorna a vista com o formulário.
    }

    /**
     * Guardar o novo utilizador na base de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:User|max:50',
            'email' => 'required|email|unique:User|max:100',
            'fullname' => 'required|max:100',
            'password' => 'required|min:8|confirmed',
            'is_admin' => 'required|boolean',
            'is_enterprise' => 'required|boolean',
        ]);

        // Criar o novo utilizador.
        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'fullname' => $validated['fullname'],
            'nif' => $validated['nif'],
            'password_hash' => bcrypt($validated['password']),
            'is_admin' => $validated['is_admin'],
            'is_enterprise' => $validated['is_enterprise'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilizador criado com sucesso.');
    }

    /**
     * Mostrar o formulário de edição para um utilizador específico.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id); // Obter o utilizador pelo ID.
        return view('admin.users.edit', compact('user')); // Retorna a vista de edição.
    }

    /**
     * Atualizar os dados de um utilizador na base de dados.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'fullname' => 'required|max:100',
            'email' => 'required|email|max:100|unique:User,email,' . $id . ',user_id',
            'username' => 'required|max:50|unique:User,username,' . $id . ',user_id',
            'is_admin' => 'required|boolean',
            'is_enterprise' => 'required|boolean',
            'nif' => 'required|max:20',
        ]);

        // Encontrar e atualizar o utilizador.
        $user = User::findOrFail($id);
        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Utilizador atualizado com sucesso.');
    }


    /**
     * Apagar um utilizador da base de dados.
     */
    public function destroy($id)
    {
        // Apagar registos relacionados na tabela BlockedUser
        \DB::table('blockeduser')->where('blocked_user_id', $id)->orWhere('admin_id', $id)->delete();

        // Agora podes apagar o utilizador
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Utilizador apagado com sucesso.');
    }

}
