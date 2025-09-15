<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
                <img src="./../assets/img/logo2.svg" alt="navbar brand" class="navbar-brand" height="30" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item <?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
                    <a href="dashboard.php" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>

                    </a>

                </li>

                <li class="nav-item <?= $current_page === 'users.php' ? 'active' : '' ?>">
                    <a href="users.php" aria-expanded="false">
                        <i class="fas fa-users"></i>
                        <p>Manage Users</p>

                    </a>

                </li>

            </ul>
        </div>
    </div>
</div>