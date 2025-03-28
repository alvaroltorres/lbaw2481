@extends('layouts.app')

@section('content')
    <h1>{{__("Edit User")}}</h1>

    <!-- Exibir Erros de Validação -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
        @csrf
        @method('PATCH')

        <label for="fullname">{{__("Full name:")}}</label>
        <input type="text" name="fullname" id="fullname" class="@error('fullname') is-invalid @enderror" value="{{ old('fullname', $user->fullname) }}" required placeholder= "Enter the full name">
        @error('fullname')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="email">{{__("Email:")}}</label>
        <input type="email" name="email" id="email" class="@error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required placeholder= "Enter the email address">
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="username">{{__("Username:")}}</label>
        <input type="text" name="username" id="username" class="@error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required placeholder="Enter the username">
        @error('username')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="is_admin">{{__("Is Admin?")}}</label>
        <select name="is_admin" id="is_admin" class="@error('is_admin') is-invalid @enderror" required>
            <option value="1" {{ $user->is_admin ? 'selected' : '' }}>Sim</option>
            <option value="0" {{ !$user->is_admin ? 'selected' : '' }}>Não</option>
        </select>
        @error('is_admin')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <label for="nif">{{__("NIF:")}}</label>
        <input type="text" name="nif" id="nif" class="@error('nif') is-invalid @enderror" value="{{ old('nif', $user->nif) }}" required placeholder="Enter the NIF">
        @error('nif')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <button type="submit">{{__("Save Changes")}}</button>
    </form>
@endsection
