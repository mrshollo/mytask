<?php
require_once("config.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_name = mysqli_real_escape_string($conn, $_POST['task_name']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
    $due_time = mysqli_real_escape_string($conn, $_POST['due_time']);
    $user_id = $_SESSION['user_id'];

    $query = $conn->prepare("INSERT INTO tasks (task_name, due_date, due_time, user_id) VALUES (?, ?, ?, ?)");
    $query->bind_param("sssi", $task_name, $due_date, $due_time, $user_id);

    if ($query->execute()) {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Task Added',
                    text: 'Your task has been added successfully.'
                }).then(() => {
                    window.location = 'task.php';
                });
            });
        </script>";
    } else {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Add Task',
                    text: 'Something went wrong. Please try again.'
                });
            });
        </script>";
    }
}
?>

<?php include 'lib/header.php'; ?>

<main class="container p-4 mx-auto md:p-6">
    <h1 class="mb-10 text-3xl font-bold text-center text-gray-900">Task Management</h1>
    
    <!-- Form Tambah Tugas -->
    <div class="w-full p-6 mb-10 bg-white shadow-lg rounded-xl">
        <h2 class="mb-6 text-xl font-semibold text-gray-800">Add New Task</h2>
        <form method="POST" class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="col-span-1">
                <label for="task_name" class="block mb-2 text-sm font-medium text-gray-700">Task Name</label>
                <input type="text" id="task_name" name="task_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500" placeholder="Enter task name" required>
            </div>
            <div class="col-span-1">
                <label for="due_date" class="block mb-2 text-sm font-medium text-gray-700">Due Date</label>
                <input type="date" id="due_date" name="due_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500" required>
            </div>
            <div class="col-span-1">
                <label for="due_time" class="block mb-2 text-sm font-medium text-gray-700">Due Time</label>
                <input type="time" id="due_time" name="due_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500" required>
            </div>
            <div class="flex justify-center col-span-1 md:col-span-3">
                <button type="submit" class="w-full px-6 py-2 text-white transition-all bg-blue-600 rounded-lg shadow-lg md:w-auto hover:bg-blue-700">
                    Add Task
                </button>
            </div>
        </form>
    </div>
</main>


<?php include 'lib/footer.php'; ?>
