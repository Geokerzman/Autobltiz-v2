@include('inc.header')
@include('inc.background_main')

<div class="jumbotron jumbotron-fluid text-center">
    <div class="container">
        <div>
            <div class="container hero">
                <div class="row">
                    <div class="col-12 col-lg-6 col-xl-5 offset-xl-1">
                        <h1>The revolution is here.</h1>
                        <p>Find your dream car as easy as it could only be. </p>
                        <a class="btn btn-light btn-lg action-button" href="{{ route('pages.about') }}">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('inc.footer')
