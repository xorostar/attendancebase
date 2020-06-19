@extends('layouts.portal')

@section('content')
<div class="card col-md-4 p-4">
    <div class="card-body">
        <div class="mb-3">
            <img src="{{ asset("images/logo.png") }}" alt="Attendance Base" class="mx-auto d-block mb-5">
        </div>
        <h2 class="mb-4 h3 font-weight-light">Sign in</h2>
        <form action="{{ route("login") }}" method="post">
            @csrf
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required autocomplete="email" autofocus value="{{ old('email') }}">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember-me">Remember Me</label>
            </div>
            <div class="d-flex justify-content-between mt-5 align-items-center">
                <a href="{{ route("register") }}" class="text-primary">
                    Register
                </a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
