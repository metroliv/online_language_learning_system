<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '" . $alert['icon'] . "',
                title: '" . $alert['title'] . "',
                text: '" . $alert['message'] . "',
                showConfirmButton: " . ($alert['confirm'] ? 'true' : 'false') . ",
                timer: " . $alert['timer'] . "
            });
        });
    </script>";

    unset($_SESSION['alert']); // Clear alert after display
}
?>
