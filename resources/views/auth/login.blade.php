<!-- resources/views/auth/login.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="container mt-4">

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="custom-card">
                    <div class="custom-card-header">{{ __('Login') }}</div>

                    <div class="custom-card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Campo de Email -->
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 custom-form-label">{{ __('Email Address') }}</label>

                                <div class="col-md-8">
                                    <input id="email" type="email"
                                           class="custom-input @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email') }}" required
                                           autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Campo de Senha -->
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 custom-form-label">{{ __('Password') }}</label>

                                <div class="col-md-8">
                                    <input id="password" type="password"
                                           class="custom-input @error('password') is-invalid @enderror"
                                           name="password" required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Lembrar-me -->
                            <div class="row mb-3">
                                <div class="col-md-8 offset-md-4">
                                    <div class="form-check">
                                        <input class="custom-checkbox" type="checkbox"
                                               name="remember" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label custom-form-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Botão de Login e Link para Esqueci minha Senha -->
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn--primary">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
