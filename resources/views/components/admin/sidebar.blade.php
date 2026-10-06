<aside class="sidebar">
    <!-- BO'LIM -->
    <a href="#" class="department">
        <div class="department-info">
            <strong>ADMIN PANEL</strong>
        </div>
    </a>

    <!-- MENU -->
    <nav class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-home"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.leads.index') }}" class="menu-item {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
            <i class="fa-solid fa-list-alt"></i>
            <span>Leadlar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-solid fa-users"></i>
            <span>Mijozlar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-solid fa-file-lines"></i>
            <span>Arizalar/ Buyurtmalar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-solid fa-credit-card"></i>
            <span>To‘lovlar</span>
        </a>

        <a href="{{ route('admin.employees.index') }}" class="menu-item {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user-group"></i>
            <span>Hodimlar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-solid fa-folder-open"></i>
            <span>Bo'limlar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-solid fa-chart-column"></i>
            <span>Hisobotlar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-solid fa-list-check"></i>
            <span>Hujjatlar</span>
        </a>

        <a href="{{ route('admin.settings.index') }}" class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i>
            <span>Sozlamalar</span>
        </a>
    </nav>

    <!-- PASTKI QISM -->
    <div class="sidebar-bottom">

        <div class="sidebar-departments">

            <div class="sidebar-section-title">Bo‘limlar</div>

            <div class="department-list">

                @foreach($departments as $department)

                    <a
                        href="{{ route('admin.department.select', $department->id) }}"
                        class="department-item {{ session('admin_department_id') == $department->id ? 'active' : '' }}"
                    >

                        <div class="department-left">

                            @if($department->icon)
                                <img
                                    src="{{ asset('img/'.$department->icon) }}"
                                    alt="{{ $department->name }}"
                                >
                            @endif

                            <span>{{ $department->name }}</span>

                        </div>

                        <i class="fa-solid fa-chevron-right"></i>

                    </a>

                @endforeach

            </div>

        </div>

        <br />
        <a href="{{ route('logout') }}" class="menu-item logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Chiqish</span>
        </a>
    </div>
</aside>