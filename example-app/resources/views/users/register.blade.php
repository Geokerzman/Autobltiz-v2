@include('inc.header')

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2>Create An Account</h2>
            <p>Please fill out this form to register with us</p>
            <form action="{{ route('users.register') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="name">Name: <sup>*</sup></label>
                    <input type="text" name="name" class="form-control form-control-lg {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}">
                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                </div>
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
                <div class="form-group">
                    <label for="confirm_password">Confirm Password: <sup>*</sup></label>
                    <input type="password" name="confirm_password" class="form-control form-control-lg {{ $errors->has('confirm_password') ? 'is-invalid' : '' }}" value="{{ old('confirm_password') }}">
                    <span class="invalid-feedback">{{ $errors->first('confirm_password') }}</span>
                </div>

                <div class="row">
                    <div class="col">
                        <input type="submit" value="Register" class="btn btn-success btn-block">
                    </div>
                    <div class="col">
                        <a href="{{ route('users.login') }}" class="btn btn-light btn-block">Have an account? Login</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('inc.footer')
