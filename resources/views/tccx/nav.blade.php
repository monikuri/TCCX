<!-- Navigation -->
<nav class="navbar navbar-expand-md navbar-light navbar-tccx mb-3">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img class="d-inline-block align-top" src="/storage/images/tccxlogo.png" width="32" height="32" alt="TCCX">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="/"><i class="fas fa-home"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/scoreboard"><i class="fas fa-star"></i> Scoreboard</a>
                </li>
                <li class="nav-item dropdown"><a id="menu-quest" class="nav-link dropdown-toggle" href="#"
                                                 role="button"
                                                 data-toggle="dropdown"
                                                 aria-haspopup="true" aria-expanded="false"><i
                                class="fas fa-list-alt"></i>
                        Quest</a>
                    <div class="dropdown-menu" aria-labelledby="menu-quest">
                        <a class="dropdown-item" href="/quest">Quests</a>
                        <a class="dropdown-item" href="/quest/locations">Locations</a>
                        <a class="dropdown-item" href="/quest/tracking">Tracking</a>
                    </div>
                </li>
                <li class="nav-item"><a class="nav-link" href="/gate-land"><i class="fas fa-trophy"></i> GATE Land</a>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fas fa-user"></i> {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>