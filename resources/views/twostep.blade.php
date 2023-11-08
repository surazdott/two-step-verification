@extends('layouts.auth')

@section('title') Two Step Verification @endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">

            <div class="d-flex justify-content-center mb-4">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('assets/img/logo.png') }}" 
                        alt="{{ config('app.name') }}"
                        height="45"
                        class="auth-logo-dark">
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="p-2">
                        <div class="text-center">
                            <div class="p-2">

                                <h4 class="mb-3">Two Step Verification</h4>

                                <div class="mb-5">
                                    <p class="mb-0">Please enter the 4 digit code sent to </p>
                                    <p class="fw-semibold">{{ hideEmail(Auth::user()->email) }}</p>
                                </div>

                                <form method="POST" action="{{ route('twostep.verify') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="mb-3">
                                                <label for="digit1-input"
                                                    class="visually-hidden">Dight 1
                                                </label>

                                                <input type="text"
                                                    class="form-control form-control-lg text-center two-step @error('code') is-invalid
                                                    @enderror"
                                                    maxLength="1"
                                                    data-value="1"
                                                    name="code[]" 
                                                    id="digit1-input"
                                                    autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="mb-3">
                                                <label for="digit2-input"
                                                    class="visually-hidden">Dight 2
                                                </label>

                                                <input type="text"
                                                    class="form-control form-control-lg text-center two-step @error('code') is-invalid
                                                    @enderror"
                                                    maxLength="1"
                                                    data-value="2"
                                                    name="code[]" 
                                                    id="digit2-input"
                                                    autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="mb-3">
                                                <label for="digit3-input"
                                                    class="visually-hidden">Dight 3
                                                </label>

                                                <input type="text"
                                                    class="form-control form-control-lg text-center two-step @error('code') is-invalid
                                                    @enderror"
                                                    maxLength="1"
                                                    data-value="3"
                                                    name="code[]" 
                                                    id="digit3-input"
                                                    autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="mb-3">
                                                <label for="digit4-input"
                                                    class="visually-hidden">Dight 4
                                                </label>

                                                <input type="text"
                                                    class="form-control form-control-lg text-center two-step @error('code') is-invalid
                                                    @enderror"
                                                    maxLength="1"
                                                    data-value="4"
                                                    name="code[]" 
                                                    id="digit4-input"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    @error ('code')
                                        <div class="pt-2">
                                            <p class="text-danger">{{ $message }}</p>
                                        </div>
                                    @enderror

                                    @if (Session::has('success'))
                                        <div class="pt-2">
                                            <p class="text-success"><i class="fas fa-check"></i> {{ Session::get('success') }}</p>
                                        </div>
                                    @endif

                                    @if (Session::has('error'))
                                        <div class="pt-2">
                                            <p class="text-danger">{{ Session::get('error') }}</p>
                                        </div>
                                    @endif

                                    <div class="my-4">
                                        <button type="submit" class="btn btn-success w-md">Confirm</button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('twostep.verify.resend') }}">
                                    @csrf

                                    @if (RateLimiter::availableIn('send-message:'.Auth::user()->id))
                                        <div class="text-center">
                                            <p class="text-muted mb-0" data-time={{ RateLimiter::availableIn('send-message:'.Auth::user()->id) }}>You may try again in {{ RateLimiter::availableIn('send-message:'.Auth::user()->id) }} seconds.</p>
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-center align-items-center">
                                        <p class="mb-0">Didn't receive a code ?</p>

                                        <button type="submit" class="btn btn-link fw-medium text-primary px-1">Resend</button>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
            
            <div class="mt-2 text-center">
                © <script>document.write(new Date().getFullYear())</script> <a href="{{ getDeveloperLink() }}">{{ getDeveloper() }}</a>
                <span class="d-none d-sm-inline-block">. All Rights Reserved.</span>
            </div>
        </div>
    </div>
@endsection
