<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color:black !important;">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fa fa-child"></i>
        </div>
        <div class="sidebar-brand-text mx-3">DaycareMS</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="/dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'parent'): ?>
        <li class="nav-item">
            <a class="nav-link" href="/dashboard.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/parent/parent_daily_reports.php">
                <i class="fas fa-book-medical"></i>
                <span>My Child Daily Reports</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/children">
                <i class="fa fa-baby"></i>
                <span>My Child Info</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'babysitter'): ?>
        <li class="nav-item">
            <a class="nav-link" href="/daily_reports/daily_reports.php">
                <i class="fas fa-book-medical"></i>
                <span>My Assigned Children & Daily Reports</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/children">
                <i class="fa fa-baby"></i>
                <span>My Assigned Children</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    <?php else: ?>
        <!-- Users -->
        <hr class="sidebar-divider">
        <div class="sidebar-heading">User Management</div>

        <li class="nav-item">
            <a class="nav-link" href="/users">
                <i class="fas fa-users"></i>
                <span>All Users</span>
            </a>
        </li>

        <!-- Children -->
        <li class="nav-item">
            <a class="nav-link" href="/parent">
                <i class="fa fa-baby"></i>
                <span>Parent</span>
            </a>
        </li>

        <!-- Children -->
        <li class="nav-item">
            <a class="nav-link" href="/children">
                <i class="fa fa-baby"></i>
                <span>Children</span>
            </a>
        </li>

        <!-- Daily Reports -->
        <li class="nav-item">
            <a class="nav-link" href="/daily_reports">
                <i class="fas fa-book-medical"></i>
                <span>Daily Reports</span>
            </a>
        </li>

        <!-- Certifications -->
        <li class="nav-item">
            <a class="nav-link" href="/certifications">
                <i class="fas fa-certificate"></i>
                <span>Certifications</span>
            </a>
        </li>

       
        <!-- Payments -->
        <hr class="sidebar-divider">
        <div class="sidebar-heading">Finance</div>

        <li class="nav-item">
            <a class="nav-link" href="/payments">
                <i class="fas fa-money-bill-wave"></i>
                <span>Payments</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    <?php endif; ?>
    <!-- Sidebar Toggler -->
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
