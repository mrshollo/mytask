<?php 
require_once("config.php");
session_start();

$response = "";

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil semua tugas berdasarkan user_id dari sesi
$user_id = $_SESSION['user_id'];

$query = $conn->prepare("SELECT * FROM tasks WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

// Inisialisasi $tasks sebagai array
$tasks = [];
if ($result->num_rows > 0) {
    $tasks = $result->fetch_all(MYSQLI_ASSOC);
}

if (isset($_POST['update_status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];

    // Cek apakah status sudah "Completed"
    $check_query = $conn->prepare("SELECT status FROM tasks WHERE id = ? AND user_id = ?");
    $check_query->bind_param("ii", $task_id, $user_id);
    $check_query->execute();
    $check_result = $check_query->get_result();
    $task = $check_result->fetch_assoc();

    if ($task['status'] === 'Completed') {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'Cannot update a task that is already completed.'
                });
            });
        </script>";
    } else {
        $update_query = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
        $update_query->bind_param("sii", $status, $task_id, $user_id);

        if ($update_query->execute()) {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Task Updated',
                        text: 'The status of the task has been updated successfully.'
                    }).then(() => {
                        window.location = 'task_list.php';
                    });
                });
            </script>";
        } else {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'Failed to update task status. Please try again.'
                    });
                });
            </script>";
        }
    }
}
?>
<?php include 'lib/header.php'; ?>

<main class="container p-6 mx-auto">
    <!-- Page Title -->
    <div class="flex flex-wrap items-center gap-2 mb-6 md:justify-between">
        <h4 class="mb-2 text-lg font-medium text-default-900">Manage Your Task</h4>
    </div>

    <!-- Tabel Daftar Tugas -->
    <div class="card w-full">
        <div class="flex items-center justify-between card-header p-4">
            <h4 class="card-title text-xl font-semibold text-gray-800">Your Tasks</h4>
        </div>
        <div class="card-body">
            <!-- Enable horizontal scroll on small screens -->
            <div class="overflow-x-auto w-full">
                <table class="min-w-full table-auto border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 font-medium text-left text-gray-600">Task</th>
                            <th class="px-4 py-3 font-medium text-left text-gray-600">Due Date</th>
                            <th class="px-4 py-3 font-medium text-left text-gray-600">Due Time</th>
                            <th class="px-4 py-3 font-medium text-left text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($tasks) > 0): ?>
                            <?php foreach ($tasks as $task): ?>
                                <tr class="border-t">
                                    <td class="px-4 py-3"><?= htmlspecialchars($task['task_name']) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($task['due_date']) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($task['due_time']) ?></td>
                                    <td class="px-4 py-3">
                                        <span class="<?= $task['status'] === 'Completed' ? 'text-green-600' : 'text-red-600' ?>">
                                            <?= htmlspecialchars($task['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">No tasks found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Card untuk Update Status -->
    <div class="card w-full mt-6">
        <div class="flex items-center justify-between card-header p-4">
            <h4 class="card-title text-xl font-semibold text-gray-800">Update Task Status</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="space-y-4">
                    <?php if (count($tasks) > 0): ?>
                        <?php foreach ($tasks as $task): ?>
                            <?php if ($task['status'] !== 'Completed'): ?> <!-- Hanya tampilkan form untuk task yang belum completed -->
                                <div class="p-4 bg-gray-50 border rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-700"><?= htmlspecialchars($task['task_name']) ?></h3>
                                    <p class="text-sm text-gray-500">Due Date: <?= htmlspecialchars($task['due_date']) ?></p>
                                    <p class="text-sm text-gray-500">Due Time: <?= htmlspecialchars($task['due_time']) ?></p>
                                    <p class="text-sm text-gray-500">
                                        Status: 
                                        <span class="<?= $task['status'] === 'Completed' ? 'text-green-600' : 'text-red-600' ?>">
                                            <?= htmlspecialchars($task['status']) ?>
                                        </span>
                                    </p>

                                    <!-- Update Status Form -->
                                    <div class="mt-4">
                                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>"> <!-- Memastikan ID task yang benar -->
                                        <select name="status" class="px-2 py-1 border border-gray-300 rounded-lg">
                                            <option value="Pending" <?= $task['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Completed" <?= $task['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                            <option value="Error" <?= $task['status'] === 'Error' ? 'selected' : '' ?>>Error</option>
                                        </select>
                                        <button type="submit" name="update_status" class="px-4 py-2 text-white transition-all bg-blue-600 rounded-lg shadow-lg hover:bg-blue-700 mt-4">
                                            Update Status
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-4 text-center text-gray-500">No tasks found.</div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require 'lib/footer.php'; ?>