<?php
include '../config/config.php';
include '../config/auth.php';
include('../partials/head.php');

// Fetch user data
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id']; // Ensure user_id is stored in the session

// Fetch available languages
$languages_query = "SELECT * FROM languages";
$languages_result = mysqli_query($db, $languages_query);

// Fetch recent activity
$recent_query = "SELECT lessons.title FROM lessons 
                 INNER JOIN user_progress ON lessons.lesson_id = user_progress.lesson_id 
                 WHERE user_progress.user_id = " . intval($user_id) . " 
                 ORDER BY user_progress.progress_id DESC LIMIT 5";
$recent_result = mysqli_query($db, $recent_query);

// Fetch user progress
$query = "SELECT lessons.title, lessons.lesson_id, user_progress.score, user_progress.status 
          FROM user_progress
          JOIN lessons ON user_progress.lesson_id = lessons.lesson_id
          WHERE user_progress.user_id = " . intval($user_id);
$result = mysqli_query($db, $query);
?>


<body class="hold-transition sidebar-mini layout-fixed">
	
        
        <?php include('../partials/header.php'); ?>
        
        
        <div class="wrapper">

    <?php include('../partials/admin_sidenav.php'); ?>
        
    <!--Container Main start-->
    <div class="content-wrapper">
        
   
        <div class="row">
            <?php while ($language = mysqli_fetch_assoc($languages_result)): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"> <?php echo htmlspecialchars($language['lang_name']); ?> </h5>
                            <a href="lessons.php?lang_id=<?php echo $language['lang_id']; ?>" class="btn btn-primary">Start Learning</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Recent Activity -->
        <h4 class="mt-4">Recent Activity</h4>
        <ul class="list-group">
            <?php while ($recent = mysqli_fetch_assoc($recent_result)): ?>
                <li class="list-group-item"> <?php echo htmlspecialchars($recent['title']); ?> </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <!-- Progress Modal -->
    <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="progressModalLabel">Your Progress</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Lesson</th>
                                <th>Score</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo $row['score'] !== null ? $row['score'] : 'N/A'; ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'completed') {
                                            echo '<span class="badge bg-success">Completed</span>';
                                        } elseif ($row['status'] === 'in_progress') {
                                            echo '<span class="badge bg-warning text-dark">In Progress</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">Not Started</span>';
                                        } ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    

    </div>
    <!--Container Main end-->
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");:root{--header-height: 3rem;--nav-width: 68px;--first-color: #4723D9;--first-color-light: #AFA5D9;--white-color: #F7F6FB;--body-font: 'Nunito', sans-serif;--normal-font-size: 1rem;--z-fixed: 100}*,::before,::after{box-sizing: border-box}body{position: relative;margin: var(--header-height) 0 0 0;padding: 0 1rem;font-family: var(--body-font);font-size: var(--normal-font-size);transition: .5s}a{text-decoration: none}.header{width: 100%;height: var(--header-height);position: fixed;top: 0;left: 0;display: flex;align-items: center;justify-content: space-between;padding: 0 1rem;background-color: var(--white-color);z-index: var(--z-fixed);transition: .5s}.header_toggle{color: var(--first-color);font-size: 1.5rem;cursor: pointer}.header_img{width: 35px;height: 35px;display: flex;justify-content: center;border-radius: 50%;overflow: hidden}.header_img img{width: 40px}.l-navbar{position: fixed;top: 0;left: -30%;width: var(--nav-width);height: 100vh;background-color: var(--first-color);padding: .5rem 1rem 0 0;transition: .5s;z-index: var(--z-fixed)}.nav{height: 100%;display: flex;flex-direction: column;justify-content: space-between;overflow: hidden}.nav_logo, .nav_link{display: grid;grid-template-columns: max-content max-content;align-items: center;column-gap: 1rem;padding: .5rem 0 .5rem 1.5rem}.nav_logo{margin-bottom: 2rem}.nav_logo-icon{font-size: 1.25rem;color: var(--white-color)}.nav_logo-name{color: var(--white-color);font-weight: 700}.nav_link{position: relative;color: var(--first-color-light);margin-bottom: 1.5rem;transition: .3s}.nav_link:hover{color: var(--white-color)}.nav_icon{font-size: 1.25rem}.show{left: 0}.body-pd{padding-left: calc(var(--nav-width) + 1rem)}.active{color: var(--white-color)}.active::before{content: '';position: absolute;left: 0;width: 2px;height: 32px;background-color: var(--white-color)}.height-100{height:100vh}@media screen and (min-width: 768px){body{margin: calc(var(--header-height) + 1rem) 0 0 0;padding-left: calc(var(--nav-width) + 2rem)}.header{height: calc(var(--header-height) + 1rem);padding: 0 2rem 0 calc(var(--nav-width) + 2rem)}.header_img{width: 40px;height: 40px}.header_img img{width: 45px}.l-navbar{left: 0;padding: 1rem 1rem 0 0}.show{width: calc(var(--nav-width) + 156px)}.body-pd{padding-left: calc(var(--nav-width) + 188px)}}
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
   
   const showNavbar = (toggleId, navId, bodyId, headerId) =>{
   const toggle = document.getElementById(toggleId),
   nav = document.getElementById(navId),
   bodypd = document.getElementById(bodyId),
   headerpd = document.getElementById(headerId)
   
   // Validate that all variables exist
   if(toggle && nav && bodypd && headerpd){
   toggle.addEventListener('click', ()=>{
   // show navbar
   nav.classList.toggle('show')
   // change icon
   toggle.classList.toggle('bx-x')
   // add padding to body
   bodypd.classList.toggle('body-pd')
   // add padding to header
   headerpd.classList.toggle('body-pd')
   })
   }
   }
   
   showNavbar('header-toggle','nav-bar','body-pd','header')
   
   /*===== LINK ACTIVE =====*/
   const linkColor = document.querySelectorAll('.nav_link')
   
   function colorLink(){
   if(linkColor){
   linkColor.forEach(l=> l.classList.remove('active'))
   this.classList.add('active')
   }
   }
   linkColor.forEach(l=> l.addEventListener('click', colorLink))
   
    // Your code to run since DOM is loaded and ready
   });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>