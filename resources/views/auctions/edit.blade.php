@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('Editar Leilão') }}</h1>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>{{ __('Houve alguns problemas com a sua entrada.') }}</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Your form here -->
        <form method="POST" action="{{ route('auctions.update', $auction) }}">
            @csrf
            @method('PATCH')


            <!-- Título -->
            <div class="form-group">
                <label for="title">{{ __('Título') }}</label>
                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $auction->title) }}" required>

                @error('title')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Descrição -->
            <div class="form-group">
                <label for="description">{{ __('Descrição') }}</label>
                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" required>{{ old('description', $auction->description) }}</textarea>

                @error('description')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Categoria -->
            <div class="form-group">
                <label for="category_id">{{ __('Categoria') }}</label>
                <select id="category_id" class="form-control @error('category_id') is-invalid @enderror" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ $auction->category_id == $category->category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Preço Inicial -->
            <div class="form-group">
                <label for="starting_price">{{ __('Preço Inicial') }}</label>
                <input id="starting_price" type="number" step="0.01" class="form-control @error('starting_price') is-invalid @enderror" name="starting_price" value="{{ old('starting_price', $auction->starting_price) }}" required>

                @error('starting_price')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- The starting price field is required.
The reserve price field is required.
The minimum bid increment field is required.
The starting date field is required.
The ending date field is required.
The location field is required.
The status field is required. -->
            <!-- Preço Reserva -->
            <div class="form-group">
                <label for="reserve_price">{{ __('Preço Reserva') }}</label>
                <input id="reserve_price" type="number" step="0.01" class="form-control @error('reserve_price') is-invalid @enderror" name="reserve_price" value="{{ old('reserve_price', $auction->reserve_price) }}" required>

                @error('reserve_price')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Incremento Mínimo -->
            <div class="form-group">
                <label for="minimum_bid_increment">{{ __('Incremento Mínimo') }}</label>
                <input id="minimum_bid_increment" type="number" step="0.01" class="form-control @error('minimum_bid_increment') is-invalid @enderror" name="minimum_bid_increment" value="{{ old('minimum_bid_increment', $auction->minimum_bid_increment) }}" required>

                @error('minimum_bid_increment')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Data de Início -->
            <div class="form-group">
                <label for="starting_date">{{ __('Data de Início') }}</label>
                <input id="starting_date" type="datetime-local" class="form-control @error('starting_date') is-invalid @enderror" name="starting_date" value="{{ old('starting_date', $auction->starting_date) }}" required>

                @error('starting_date')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Data de Fim -->
            <div class="form-group">
                <label for="ending_date">{{ __('Data de Fim') }}</label>
                <input id="ending_date" type="datetime-local" class="form-control @error('ending_date') is-invalid @enderror" name="ending_date" value="{{ old('ending_date', $auction->ending_date) }}" required>

                @error('ending_date')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Localização -->
            <div class="form-group">
                <label for="location">{{ __('Localização') }}</label>
                <input id="location" type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location', $auction->location) }}" required>

                @error('location')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Estado -->
            <div class="form-group">
                <label for="status">{{ __('Estado') }}</label>
                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                    <option value="Active" {{ $auction->status == 'Active' ? 'selected' : '' }}>Ativo</option>
                    <option value="Upcoming" {{ $auction->status == 'Upcoming' ? 'selected' : '' }}>Inativo</option>
                </select>

                @error('status')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>


            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">
                    {{ __('Atualizar Leilão') }}
                </button>
            </div>
        </form>
    </div>
@endsection
