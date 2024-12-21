@extends('layouts.app')

@section('content')
    <div class="container credits-page">
        <h2>{{ __('Add Credits') }}</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form id="addCreditsForm" action="{{ route('credits.add.post') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="credits" class="form-label">{{ __('Amount to Add') }} (€)</label>
                <input type="number" name="credits" id="credits" class="form-control" step="0.01" min="0.01" required>
            </div>

            <button id="payButton" class="btn btn-primary">{{ __('Pay') }}</button>
        </form>
    </div>

    <!-- Modal de pagamento mock -->
    <div id="paymentModal" class="payment-modal">
        <div class="payment-modal-content">
            <span id="paymentModalClose" class="payment-modal-close">&times;</span>
            <h3>{{ __('Select Payment Method') }}</h3>
            <p>{{ __('Choose your preferred payment method to confirm the transaction:') }}</p>
            <div class="payment-options">
                <button class="payment-option">PayPal</button>
                <button class="payment-option">Apple Pay</button>
                <button class="payment-option">Credit Card</button>
            </div>
            <p id="processingMessage" style="display:none; margin-top:10px; color:#333;">{{ __('Processing your payment...') }}</p>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/add_credits.js') }}"></script>
@endpush
