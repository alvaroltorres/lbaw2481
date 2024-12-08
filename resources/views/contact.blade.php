@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('Contact Us') }}</h1>
        <section class="contact-section">
            <h2>{{ __('Get in Touch') }}</h2>
            <p>{{ __('We would love to hear from you! Please reach out to us using the following contact information:') }}</p>
            
            <h3>{{ __('For Information') }}</h3>
            <p>{{ __('Email:') }} <a href="mailto:info@bidzenith.com">info@bidzenith.com</a></p>
            
            <h3>{{ __('For General Inquiries') }}</h3>
            <p>{{ __('Email:') }} <a href="mailto:general@bidzenith.com">general@bidzenith.com</a></p>
            
            <h3>{{ __('For Help') }}</h3>
            <p>{{ __('Email:') }} <a href="mailto:help@bidzenith.com">help@bidzenith.com</a></p>
        </section>
    </div>
@endsection