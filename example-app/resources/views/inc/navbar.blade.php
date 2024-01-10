<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}"><i class="fa fa-home fa-lg"
                                                                 style="color: #d1d1d1;"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/pages/about') }}"><i class="fa fa-users"
                                                                            style="color: #d1d1d1;"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="#"> {{ auth()->user()->name }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/users/logout') }}">Logout</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/users/register') }}">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/users/login') }}">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
