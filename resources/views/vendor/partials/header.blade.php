<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="{{ route('vendor.dashboard') }}" class="nav-link">Dashboard</a></li>
            <li class="nav-item d-none d-md-block"><a href="{{ route('home') }}" class="nav-link" target="_blank">Visit Website</a></li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display:none"></i>
                </a>
            </li>
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="/vendor/assets/img/user2-160x160.jpg" class="user-image rounded-circle shadow" alt="Vendor">
                    <span class="d-none d-md-inline">{{ auth('vendor')->user()?->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-header text-bg-primary">
                        <img src="/vendor/assets/img/user2-160x160.jpg" class="rounded-circle shadow" alt="Vendor">
                        <p>{{ auth('vendor')->user()?->name }}<small>{{ auth('vendor')->user()?->shop_name }}</small></p>
                    </li>
                    <li class="user-footer">
                        <a href="{{ route('home') }}" class="btn btn-default btn-flat" target="_blank">Website</a>
                        <form class="d-inline" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-default btn-flat float-end" type="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
