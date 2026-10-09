<aside class="sidebar">

    <!-- BO'LIM -->
    <div class="department">
        <img src="{{ asset('img/'.$department->icon) }}">
        <div class="department-info">
            <strong>{{ $department->name }}</strong>
            <p>{{ $department->description }}</p>
        </div>
    </div>


    <!-- MENU -->
    <nav class="sidebar-menu">

        <a href="{{ route('employee.leads.index') }}" class="menu-item active">
            <i class="fa-solid fa-user-group"></i>
            <span>Leadlar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-solid fa-users"></i>
            <span>Mijozlar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-solid fa-file-lines"></i>
            <span>Arizalar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-solid fa-credit-card"></i>
            <span>To‘lovlar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-solid fa-folder-open"></i>
            <span>Hujjatlar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-solid fa-plane-departure"></i>
            <span>Safarlar</span>
        </a>

        <a href="#" class="menu-item">
            <i class="fa-solid fa-list-check"></i>
            <span>Vazifalar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-regular fa-calendar-days"></i>
            <span>Kalendar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-solid fa-chart-column"></i>
            <span>Hisobotlar</span>
        </a>

        <a href="#" class="menu-item" style="color: #666">
            <i class="fa-solid fa-gear"></i>
            <span>Sozlamalar</span>
        </a>

    </nav>


    <!-- PASTKI QISM -->
    <div class="sidebar-bottom">

        <a href="#" class="menu-item">
            <i class="fa-solid fa-user"></i>
            <span>{{ $user?->name ?? 'Xodim' }}</span>
        </a>
        
        <a href="{{ route('employee.department.select') }}" class="menu-item">
            <i class="fa-solid fa-arrow-right-arrow-left"></i>
            <span>Bo‘lim almashtirish</span>
        </a>

        <a href="{{ route('logout') }}" class="menu-item logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Chiqish</span>
        </a>

    </div>

</aside>
