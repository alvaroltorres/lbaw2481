<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    /**
     * Mostrar a lista de utilizadores.
     */
    public function index()
    {
        // Obter apenas utilizadores que não sejam administradores
        $users = User::where('is_admin', false)->get();

        return view('admin.users.index', compact('users'));
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
            'username' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:100',
            'fullname' => 'required|max:100',
            'password' => 'required|min:8|confirmed',
            'nif' => 'nullable|max:100',
            'is_admin' => 'required|boolean',
        ]);

        // Criar o novo utilizador com is_enterprise sempre como falso
        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'fullname' => $validated['fullname'],
            'nif' => $validated['nif'] ?? null,
            'password_hash' => bcrypt($validated['password']),
            'is_admin' => $validated['is_admin'],
            'is_enterprise' => false, // Definido diretamente como falso
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
            'username' => 'required|max:50|unique:users,username,' . $id,
            'email' => 'required|email|max:100|unique:users,email,' . $id,
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

    public function show($id)
    {
        $user = User::with('auctions')->findOrFail($id);
        return view('user.show', compact('user'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('query'); // Parâmetro 'query' da URL

        // Realizar a busca nos campos 'username', 'email' e 'fullname'
        $users = User::where('username', 'LIKE', "%{$searchTerm}%")
            ->orWhere('email', 'LIKE', "%{$searchTerm}%")
            ->orWhere('fullname', 'LIKE', "%{$searchTerm}%")
            ->get();

        // Retorna a mesma view da lista de utilizadores com os resultados filtrados
        return view('admin.users.index', ['users' => $users, 'searchTerm' => $searchTerm]);
    }

    /**
     * Apagar um utilizador da base de dados.
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        try {
            DB::table('blockeduser')->where('blocked_user_id', $id)->orWhere('admin_id', $id)->delete();
            $user->delete();

            return response()->json(['success' => true, 'message' => 'Utilizador apagado com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao apagar utilizador.'], 500);
        }
    }

    public function block(Request $request, $id)
    {
        $user = User::find($id);



        // Verifica se o utilizador já está bloqueado
        if (DB::table('blockeduser')->where('blocked_user_id', $user->user_id)->exists()) {
            return redirect()->back()->with('error', 'O utilizador já está bloqueado.');
        }

        // Verifica se o utilizador é administrador
        if ($user->is_admin) {
            return redirect()->back()->with('error', 'Não é possível bloquear um administrador.');
        }
        // Valida a razão do bloqueio
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);


        // Insere os dados na tabela blockeduser
        DB::table('blockeduser')->insert([
            'admin_id' => auth()->id(),
            'blocked_user_id' => $user->user_id, // O $user->id será usado como a chave primária
            'reason' => $request->reason,
            'blocked_at' =>     Carbon::now(),
        ]);

        // Marca o utilizador como bloqueado
        $user->is_blocked = true;
        $user->save();

        return redirect()->back()->with('success', 'O utilizador foi bloqueado com sucesso.');
    }



    public function unblock($id)
    {
        $user = User::findOrFail($id);

        // Verifica se o utilizador já está desbloqueado
        if (!$user->is_blocked) {
            return redirect()->back()->with('error', 'O utilizador já está desbloqueado.');
        }

        // Inicia a transação
        DB::beginTransaction();

        try {
            // Remove da tabela `blockeduser`
            DB::table('blockeduser')->where('blocked_user_id', $user->user_id)->delete();

            // Atualiza o estado do utilizador
            $user->is_blocked = false;
            $user->save();

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'Utilizador desbloqueado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocorreu um erro ao desbloquear o utilizador.');
        }
    }
}
