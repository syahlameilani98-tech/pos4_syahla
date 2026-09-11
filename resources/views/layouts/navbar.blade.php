<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #0d6efd;">
    <div class="container">

        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <i class="bi bi-shop me-2"></i>Senja Cafe
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard') ? 'active fw-bold text-white' : '' }}"
                       href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/users*') ? 'active fw-bold text-white' : '' }}"
                       href="{{ route('admin.users.index') }}">
                        Users
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('produk*') ? 'active fw-bold text-white' : '' }}"
                       href="{{ route('produk.index') }}">
                        Produk
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('jenis*') ? 'active fw-bold text-white' : '' }}"
                       href="{{ route('jenis.index') }}">
                        Jenis
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('penjualan*') ? 'active fw-bold text-white' : '' }}"
                       href="{{ route('penjualan.index') }}">
                        Penjualan
                    </a>
                </li>

            </ul>

            <div class="ms-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-danger btn-sm px-3 fw-semibold">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>