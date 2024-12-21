<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Auction;
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
            'username' => 'required|unique:User|max:50',
            'email' => 'required|email|unique:User|max:100',
            'fullname' => 'required|max:100',
            'password' => 'required|min:8|confirmed',
            'nif' => 'nullable|max:100',
            'is_admin' => 'required|boolean',
        ]);

        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'fullname' => $validated['fullname'],
            'nif' => $validated['nif'] ?? null,
            'password_hash' => bcrypt($validated['password']),
            'is_admin' => $validated['is_admin'],
            'is_blocked' => false, // Definido diretamente como falso
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
        $searchTerm = $request->input('query'); // Obtém o termo da pesquisa

        // Realizar a busca nos campos com as condições agrupadas corretamente
        $users = User::where(function ($query) use ($searchTerm) {
            $query->where('username', 'LIKE', "%{$searchTerm}%")
                ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                ->orWhere('fullname', 'LIKE', "%{$searchTerm}%");
        })
            ->where('is_admin', false) // Garante que exclui admins
            ->get();

        // Retorna a view com os resultados filtrados
        return view('admin.users.index', ['users' => $users, 'searchTerm' => $searchTerm]);
    }


    /**
     * Apagar um utilizador da base de dados.
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        try {
            DB::transaction(function () use ($id, $user) {
                // Apagar dependências relacionadas
                DB::table('blockeduser')->where('blocked_user_id', $id)->orWhere('admin_id', $id)->delete();
                DB::table('Auction')->where('user_id', $id)->delete();
                DB::table('Bid')->where('user_id', $id)->delete();
                DB::table('chatparticipant')->where('user_id', $id)->delete();
                DB::table('follow_auctions')->where('user_id', $id)->delete();
                DB::table('notification')->where('user_id', $id)->delete();
                DB::table('watchlist')->where('user_id', $id)->delete();
                DB::table('message')->where('sender_id', $id)->delete();

                // Apagar o utilizador
                $user->delete();
            });
            return response()->json(['success' => true, 'message' => 'Utilizador apagado com sucesso.']);
        } catch (\Exception $e) {
            \Log::error(__('Error deleting user: ') . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao apagar utilizador.', 'error' => $e->getMessage()], 500);
        }
    }


    public function block(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            \Log::error('Utilizador não encontrado', ['user_id' => $id]);
            return redirect()->back()->with('error', 'Utilizador não encontrado.');
        }

        // Verifica se o utilizador já está bloqueado
        if (DB::table('blockeduser')->where('blocked_user_id', $user->user_id)->exists()) {
            \Log::info(__('User is already blocked'), ['user_id' => $user->user_id]);
            return redirect()->back()->with('error', 'O utilizador já está bloqueado.');
        }

        // Verifica se o utilizador é administrador
        if ($user->is_admin) {
            \Log::warning(__('Not possible to block an admin'), ['user_id' => $user->user_id]);
            return redirect()->back()->with('error', 'Não é possível bloquear um administrador.');
        }

        // Valida a razão do bloqueio
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        try {
            // Insere os dados na tabela blockeduser
            DB::table('blockeduser')->insert([
                'admin_id' => auth()->id(),
                'blocked_user_id' => $user->user_id,
                'reason' => $validated['reason'],
                'blocked_at' => Carbon::now(),
            ]);

            // Marca o utilizador como bloqueado
            $user->is_blocked = true;
            $user->save();

            \Log::info(__('User successfully blocked'), ['user_id' => $user->user_id]);
            return redirect()->back()->with('success', 'O utilizador foi bloqueado com sucesso.');
        } catch (\Exception $e) {
            \Log::error(__('Error deleting user'), ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erro ao bloquear utilizador.');
        }
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
