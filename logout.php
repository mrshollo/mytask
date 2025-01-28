<?php
session_start();
session_unset();
session_destroy();
require("config.php");

// Redirect ke halaman login dengan SweetAlert
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Logged Out',
                text: 'You have been logged out successfully!'
            }).then(() => {
                window.location = 'login.php';
            });
        });
    </script>
</body>
</html>";
exit;
?>
