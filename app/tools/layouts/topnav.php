<!-- include links to crud example -->



        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark" style="margin-bottom: 70px;">
            <a class="navbar-brand" href="<?php url_to('') ?>">
                <?php echo APP_NAME; ?>
            </a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a class="nav-link" href="<?php url_to('menus') ?>">Our Menus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php url_to('customers') ?>">Customer Orders</a>
                </li>
                

                <li class="nav-item">
                    <a class="nav-link" href="<?php url_to('reports') ?>">Reports</a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo proper_case(get_sess('logged_in_user_name')) ?> <i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="<?php url_to('users/settings') ?>">Settings</a>
                        <a class="dropdown-item" href="<?php url_to('users/activitys') ?>">Activity Log</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php url_to('users/logout') ?>">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>




        <div class="main-body" style="margin-top:70px;">
            
        




