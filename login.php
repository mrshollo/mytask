<?php 
session_start();
require_once("config.php");

// Jika user sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = trim(mysqli_real_escape_string($conn, $_POST['password']));

    $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Welcome back, $username!'
                    }).then(() => {
                        window.location = 'index.php';
                    });
                });
            </script>";
        } else {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Credentials',
                        text: 'The username or password is incorrect.'
                    });
                });
            </script>";
        }
    } else {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'User Not Found',
                    text: 'No account found with that username.'
                });
            });
        </script>";
    }
}
?>

<?php include 'lib/header.php'; ?>

<!-- Form Login -->
<main class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-6 bg-white shadow-lg rounded-xl">
        <h1 class="mb-6 text-3xl font-bold text-center text-gray-900">Welcome Back</h1>
        <form method="POST" class="space-y-5">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-field focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Enter username" required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-field focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Enter password" required>
            </div>
            <!-- Tombol Submit dengan Warna Biru -->
            <button type="submit" class="w-full px-4 py-2 mt-4 font-semibold text-white transition-all duration-200 bg-blue-600 rounded-lg shadow-lg hover:bg-blue-700">
                Login
            </button>
        </form>
        <p class="mt-6 text-sm text-center text-gray-700">
            Don't have an account? <a href="register.php" class="font-semibold text-blue-800 hover:underline">Register</a>
        </p>
    </div>
</main>

<?php include 'lib/footer.php'; ?>
