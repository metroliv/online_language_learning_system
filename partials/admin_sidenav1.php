<aside class="main-sidebar sidebar-light-primary elevation-4" style="background-color: #ffffff">
    <!-- Brand Logo -->
    <a href="index.html" class="brand-link">
        <img src="../public/img/logo.png" alt="Language Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light">LangLearn</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../public/dist/img/user.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="../views/profile.php" class="d-block">
                    <?php echo $_SESSION['user_names']; ?>
                    <br />
                    <?php echo $_SESSION['user_access_level']; ?>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="dashboard" class="nav-link">
                        <i class="nav-icon fas fa-home text-primary"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="courses" class="nav-link">
                        <i class="nav-icon fas fa-book text-success"></i>
                        <p>My Courses</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-graduation-cap text-warning"></i>
                        <p>
                            Learning Tools
                            <i class="right fas fa-angle-down"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="quizzes" class="nav-link">
                                <i class="fas fa-puzzle-piece nav-icon text-info"></i>
                                <p>Quizzes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="flashcards" class="nav-link">
                                <i class="fas fa-sticky-note nav-icon text-info"></i>
                                <p>Flashcards</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="practice" class="nav-link">
                                <i class="fas fa-microphone nav-icon text-info"></i>
                                <p>Practice</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a href="community" class="nav-link">
                        <i class="nav-icon fas fa-users text-danger"></i>
                        <p>Community</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="settings" class="nav-link">
                        <i class="nav-icon fas fa-cog text-secondary"></i>
                        <p>Settings</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
