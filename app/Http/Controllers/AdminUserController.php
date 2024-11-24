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
            'nif' => 'nullable|max:100',
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
            'username' => 'required|max:50|unique:User,username,' . $id . ',user_id',
            'email' => 'required|email|max:100|unique:User,email,' . $id . ',user_id',
            'fullname' => 'required|max:100',
            'nif' => 'nullable|max:100',
            'password' => 'nullable|min:8|confirmed',
            'is_admin' => 'required|boolean',
            'is_enterprise' => 'required|boolean',
        ]);

        // Atualizar o utilizador.
        $user = User::findOrFail($id);
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->fullname = $validated['fullname'];
        $user->nif = $validated['nif'] ?? $user->nif;
        if (!empty($validated['password'])) {
            $user->password_hash = bcrypt($validated['password']);
        }
        $user->is_admin = $validated['is_admin'];
        $user->is_enterprise = $validated['is_enterprise'];
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Utilizador atualizado com sucesso.');
    }

    /**
     * Apagar um utilizador da base de dados.
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Apagar registos relacionados na tabela BlockedUser
        \DB::table('blockeduser')->where('blocked_user_id', $id)->orWhere('admin_id', $id)->delete();

        try {
            $user->delete();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Utilizador apagado com sucesso.']);
            }

            return redirect()->route('admin.users.index')->with('success', 'Utilizador apagado com sucesso.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Falha ao apagar o utilizador.'], 500);
            }

            return redirect()->route('admin.users.index')->with('error', 'Falha ao apagar o utilizador.');
        }
    }
}