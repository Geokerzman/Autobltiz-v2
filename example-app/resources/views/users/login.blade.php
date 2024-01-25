@include('inc.header')
@php
    $URLROOT = 'http://localhost/autoblitz';
@endphp
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            {{-- {{ flash('register_success') }} --}}
            <h2>Login</h2>
            <p>Please fill in your credentials to log in</p>
            <form action="{{ route('users.login') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="email">Email: <sup>*</sup></label>
                    <input type="email" name="email" class="form-control form-control-lg {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}">
                    <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                </div>
                <div class="form-group">
                    <label for="password">Password: <sup>*</sup></label>
                    <input type="password" name="password" class="form-control form-control-lg {{ $errors->has('password') ? 'is-invalid' : '' }}" value="{{ old('password') }}">
                    <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Login" class="btn btn-success btn-block">
                    </div>
                    <div class="col">
                        <a href="{{ route('users.register') }}" class="btn btn-light btn-block">No account? Register</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('inc.footer')
