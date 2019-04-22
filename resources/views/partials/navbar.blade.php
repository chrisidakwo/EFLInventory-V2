<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-success logo" href="{{ url('/') }}">
                <img src="{{ asset('img/ownerdirect.png') }}" alt="Logo" class="align-self-center img-fluid">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarToggle">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:void(0);"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ auth()->user()->name }}
                        </a>
                        <div class="dropdown-menu">
                            <a href="" class="dropdown-item">My Account</a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" onclick="$('#logout').submit();" title="Logout">
                            <i class="mdi mdi-power mdi-18px"></i>
                        </a>
                        <form id="logout" action="{{ route('logout') }}" method="post">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
