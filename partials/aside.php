<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->


    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3">

            <div class="image">
                <img src="../public/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
            </div>
            </br>
            <div class="info">
                <a href="#" class="d-block">
                    <?php echo $_SESSION['user_names']; ?>
                    <br />
                    <?php echo $_SESSION['user_email']; ?>
                </a>
            </div>
        </div>

        <!-- SidebarSearch Form -->


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if ($_SESSION['user_access_level'] == 'Revenue collector') { ?>
                    <li class="nav-item">
                        <a href="dashboard" class="nav-link">
                            <i class="nav-icon far fa-image"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="collection" class="nav-link">
                            <i class="nav-icon far fa-image"></i>
                            <p>
                                Collections
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/kanban.html" class="nav-link">
                            <i class="nav-icon fas fa-columns"></i>
                            <p>
                                Reporting
                            </p>
                        </a>
                    </li>
                <?php } ?>
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                            Mailbox
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/mailbox/mailbox.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inbox</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/mailbox/compose.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Compose</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/mailbox/read-mail.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Read</p>
                            </a>
                        </li>
                    </ul>
                </li> -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>