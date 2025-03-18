<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Handle notification dropdown toggle
    const notificationLink = document.querySelector('.nav-item.dropdown .nav-link');
    const notificationDropdown = document.querySelector('.dropdown-menu');

    if (notificationLink && notificationDropdown) {
      notificationLink.addEventListener('click', (e) => {
        e.preventDefault();
        notificationDropdown.classList.toggle('show');
      });

      // Handle click outside of dropdown to close it
      document.addEventListener('click', (e) => {
        if (!notificationLink.contains(e.target) && !notificationDropdown.contains(e.target)) {
          notificationDropdown.classList.remove('show');
        }
      });
    }

    // Fullscreen toggle functionality
    const fullscreenLink = document.querySelector('a[data-widget="fullscreen"]');
    if (fullscreenLink) {
      fullscreenLink.addEventListener('click', (e) => {
        e.preventDefault();
        if (!document.fullscreenElement) {
          document.documentElement.requestFullscreen();
        } else if (document.exitFullscreen) {
          document.exitFullscreen();
        }
      });
    }
  });
</script>
<script>
 $(document).ready(function() {
    // Expand/collapse functionality for nav items
    $('.nav-item.has-treeview > a').on('click', function(e) {
        e.preventDefault(); // Prevent default link behavior
        var $this = $(this);
        
        // Toggle the active class for the clicked item
        $this.parent().toggleClass('menu-open');

        // Slide toggle the associated submenu with proper timing
        $this.next('.nav-treeview').stop(true, true).slideToggle();

        // Optionally, close other open submenus if you want only one open at a time
        // $('.nav-treeview').not($this.next()).slideUp();
        // $('.nav-item').not($this.parent()).removeClass('menu-open');
    });
});

</script>
