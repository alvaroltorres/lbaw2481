<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Password Reset') }}</title>
</head>
<body>
<div class="container">
    <h2>{{ __('Reset Your Password') }}</h2>

    <!-- Check for success message -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <!-- Form to reset password -->
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Hidden Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">{{ __('Email Address') }}</label>
            <input type="email" name="email" id="email" value="{{ old('email', $request->email) }}" required placeholder="{{ __('Enter your email address') }}">
            <!-- Error message for email -->
            @if ($errors->has('email'))
                <div class="error">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">{{ __('New Password') }}</label>
            <input type="password" name="password" id="password" required placeholder="{{ __('Enter your password') }}">
            <!-- Error message for password -->
            @if ($errors->has('password'))
                <div class="error">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="{{ __('Enter your password again') }}">
            <!-- Error message for confirmation password -->
            @if ($errors->has('password_confirmation'))
                <div class="error">{{ $errors->first('password_confirmation') }}</div>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="form-group">
            <button type="submit">{{ __('Reset Password') }}</button>
        </div>
    </form>
</div>
</body>
</html>