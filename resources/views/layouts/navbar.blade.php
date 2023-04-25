<nav class="navbar sticky-top navbar-header navbar-expand navbar-light ">
    <a class="sidebar-toggler" href="#"><span class="navbar-toggler-icon"></span></a>
    <button class="btn navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav d-flex align-items-center navbar-light ms-auto">
            <li class="dropdown">
                <a href="#" data-bs-toggle="dropdown"
                   class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <div class="avatar me-1">

                    </div>
                    <div class="d-none d-md-block d-lg-inline-block">{{ auth()->user()->lastname }} {{ auth()->user()->firstname[0] }}.</div>
                </a>
                <div class="dropdown-menu dropdown-menu-end">


                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i data-feather="log-out"></i> {{ __("Logout") }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
