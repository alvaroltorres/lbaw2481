<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Importa Auth após o namespace

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o utilizador está autenticado e se é administrador
        if (Auth::check() && Auth::user()->is_admin) { // Substituí `role` por `is_admin` se usares este campo
            return $next($request);
        }

        // Redireciona para a página inicial com uma mensagem de erro
        return redirect('/')->with('error', 'Acesso não autorizado');
    }
}
