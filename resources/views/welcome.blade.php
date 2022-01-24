
@extends("layouts.master")
@section("title","STEER | Platform")
@section("content")
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">              
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                @if (Route::has('login'))
                <div class="navbar-nav">
                    @auth
                    <a href="{{ url('/home') }}" class="nav-link">Home</a>
                    @else
                    <a href="{{ route('login') }}" class="nav-link">Log in</a>

                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                    @endif
                    @endauth

                </div>
                @endif
            </div>
        </div>
    </nav>
    <div class="row justify-content-md-center">
        <div class="col col-lg-2">
            1 of 3
        </div>
        <div class="col-md-auto">
            Variable width content
        </div>
        <div class="col col-lg-2">
            3 of 3
        </div>
    </div>
    <div class="row">
        <div class="col">
            1 of 3
        </div>
        <div class="col-md-auto">
            Variable width content
        </div>
        <div class="col col-lg-2">
            3 of 3
        </div>
    </div>

</div>

@endsection