@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">{{ __('Register') }}</h4>
                </div>
                <br>
                <br>
                <div class="card-body">
                    <form id ="register" method="POST" action="/register">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="captcha" class="col-md-4 col-form-label text-md-right">{{ __('Captcha') }}</label>

                            <div class="col-md-6">
                                <input id="captcha" type="text" class="form-control @error('captcha') is-invalid @enderror" name="captcha" required autocomplete="off">
                                
                                @php
                                    $num1 = rand(1, 9);
                                    $num2 = rand(1, 9);
                                    $operators = ['+', '-', '*', '/'];
                                    $operator = $operators[rand(0, count($operators) - 1)];
                                
                                
                                    // Calculate the expected answer
                                    switch ($operator) {
                                        case '+':
                                            $expectedAnswer = $num1 + $num2;
                                            break;
                                        case '-':
                                            $expectedAnswer = $num1 - $num2;
                                            break;
                                        case '*':
                                            $expectedAnswer = $num1 * $num2;
                                            break;
                                        case '/':
                                            $expectedAnswer = $num1 / $num2;
                                            break;
                                        default:
                                            $expectedAnswer = $num1 + $num2; // Default to addition
                                    }
                                @endphp

                                <input type="hidden" name="num1" value="{{ $num1 }}">
                                <input type="hidden" name="num2" value="{{ $num2 }}">
                                <input type="hidden" name="operator" value="{{ $operator }}">

                                <span>{!! $num1 .' '. $operator .' '. $num2 .' =' !!}</span>
                            
                                @error('captcha')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                {{-- Put the expected answer in the session --}}
                                @php
                                    session()->put('captcha_answer', $expectedAnswer);
                                @endphp
                            </div>
                        </div>
            
                   
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
                <div>
                Already have an account?
                    <a href="{{ route('login') }}" class="btn btn-link"> Login here</a>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
