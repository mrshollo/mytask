<?php
require_once("config.php");
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan task berdasarkan waktu terdekat
$query = "SELECT id, task_name, due_date, due_time, status FROM tasks WHERE user_id = ? ORDER BY due_date ASC, due_time ASC";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error preparing query: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Simpan task dalam array
$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}
?>
<?php include 'lib/header.php'; ?>

<main class="container p-12 mx-auto">
    <!-- Page Title -->
    <div class="flex flex-wrap items-center gap-2 mb-6 md:justify-between">
        <h4 class="mb-2 text-lg font-medium text-default-900">Task Priority</h4>
    </div>

    <!-- Filter by Priority -->
    <div class="p-4 mb-6 bg-white shadow-lg rounded-xl">
        <h2 class="mb-4 text-xl font-semibold text-gray-800">Tasks Ordered by Nearest Due Date</h2>
        
        <!-- Mobile Card Layout -->
        <div class="space-y-4">
            <?php if (count($tasks) > 0): ?>
                <?php foreach ($tasks as $task): ?>
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
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-4 text-center text-gray-500">No tasks found.</div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'lib/footer.php'; ?>
