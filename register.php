<?php
session_start();
require_once("config.php");

// Jika user sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah username sudah ada
    $check_query = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_query->bind_param("s", $username);
    $check_query->execute();
    $check_query->store_result();

    if ($check_query->num_rows > 0) {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Username Taken',
                    text: 'The username \"$username\" is already taken. Please choose another.'
                });
            });
        </script>";
    } else {
        // Lakukan insert jika username belum ada
        $query = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $query->bind_param("sss", $username, $email, $password);

        if ($query->execute()) {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful',
                        text: 'You have successfully registered. Please log in!'
                    }).then(() => {
                        window.location = 'login.php';
                    });
                });
            </script>";
        } else {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: 'Something went wrong. Please try again later.'
                    });
                });
            </script>";
        }
    }
}
?>

<?php include 'lib/header.php'; ?>

<main class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-6 bg-white shadow-lg rounded-xl">
        <h1 class="mb-6 text-3xl font-bold text-center text-gray-900">Create an Account</h1>
        <?php if (!empty($error)): ?>
            <div class="mb-4 font-medium text-center text-red-600"><?= $error; ?></div>
        <?php endif; ?>
        <form method="POST" class="space-y-5">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-field focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Enter username" required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-field focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Enter email" required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg input-field focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Enter password" required>
            </div><br>
            <button type="submit" class="w-full px-4 py-2 font-semibold text-white transition-all duration-200 bg-blue-600 rounded-lg shadow-lg hover:bg-blue-700">Register</button>
        </form>
        <p class="mt-6 text-sm text-center text-gray-700">
            Already have an account? <a href="login.php" class="font-semibold text-blue-600 hover:underline">Login</a>
        </p>
    </div>
</main>

<?php include 'lib/footer.php'; ?>
