<?php
require_once("config.php");
session_start();

// Pastikan user telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data status tugas
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT status, COUNT(*) as count FROM tasks WHERE user_id = ? GROUP BY status");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

// Inisialisasi data untuk Chart.js
$task_status = [];
$task_counts = [];
while ($row = $result->fetch_assoc()) {
    $task_status[] = $row['status'];
    $task_counts[] = $row['count'];
}
?>
<?php include 'lib/header.php'; ?>

<main class="container p-6 mx-auto">
    <!-- Page Title -->
    <div class="flex flex-wrap items-center gap-2 mb-6 md:justify-between">
        <h4 class="mb-2 text-lg font-medium text-default-900">Dashboard</h4>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="p-4 card group">
            <div class="flex items-center justify-between">
                <span>
                    <div class="block font-semibold text-slate-600">Total Tasks</div>
                    <div class="mt-2 text-2xl font-semibold text-slate-800"><?= array_sum($task_counts) ?></div>
                </span>
                <span class="flex items-center justify-center rounded-full size-16 bg-primary/10 text-primary">
                    <i class="text-4xl transition-all material-symbols-rounded group-hover:fill-1">task</i>
                </span>
            </div>
        </div>
        <div class="p-4 card group">
            <div class="flex items-center justify-between">
                <span>
                    <div class="block font-semibold text-slate-600">Pending Tasks</div>
                    <div class="mt-2 text-2xl font-semibold text-slate-800">
                        <?= in_array('Pending', $task_status) ? $task_counts[array_search('Pending', $task_status)] : 0 ?>
                    </div>
                </span>
                <span class="flex items-center justify-center rounded-full size-16 bg-warning/10 text-warning">
                    <i class="text-4xl transition-all material-symbols-rounded group-hover:fill-1">hourglass_empty</i>
                </span>
            </div>
        </div>
        <div class="p-4 card group">
            <div class="flex items-center justify-between">
                <span>
                    <div class="block font-semibold text-slate-600">Completed Tasks</div>
                    <div class="mt-2 text-2xl font-semibold text-slate-800">
                        <?= in_array('Completed', $task_status) ? $task_counts[array_search('Completed', $task_status)] : 0 ?>
                    </div>
                </span>
                <span class="flex items-center justify-center rounded-full size-16 bg-success/10 text-success">
                    <i class="text-4xl transition-all material-symbols-rounded group-hover:fill-1">done</i>
                </span>
            </div>
        </div>
        <div class="p-4 card group">
            <div class="flex items-center justify-between">
                <span>
                    <div class="block font-semibold text-slate-600">Error Tasks</div>
                    <div class="mt-2 text-2xl font-semibold text-slate-800">
                        <?= in_array('Error', $task_status) ? $task_counts[array_search('Error', $task_status)] : 0 ?>
                    </div>
                </span>
                <span class="flex items-center justify-center rounded-full size-16 bg-danger/10 text-danger">
                    <i class="text-4xl transition-all material-symbols-rounded group-hover:fill-1">error</i>
                </span>
            </div>
        </div>
    </div>

    <!-- Grafik Pie Chart -->
    <div class="card">
        <div class="flex items-center justify-between card-header">
            <h4 class="card-title">Task Status Chart</h4>
        </div>
        <div class="card-body">
            <div id="taskStatusChart"></div>
        </div>
    </div>
</main>

<?php include 'lib/footer.php'; ?>

<!-- Tambahkan script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const options = {
            series: <?= json_encode($task_counts) ?>,
            chart: {
                type: 'pie',
                height: 'auto', // Agar otomatis menyesuaikan dengan kontainer
                width: '100%'   // Responsif penuh
            },
            labels: <?= json_encode($task_status) ?>,
            colors: ['#f87171', '#34d399', '#60a5fa', '#facc15'], // Warna tambahan jika diperlukan
            legend: {
                position: 'bottom'
            },
            responsive: [
                {
                    breakpoint: 768, // Di bawah 768px
                    options: {
                        chart: {
                            width: '100%',
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            ]
        };

        const chart = new ApexCharts(document.querySelector("#taskStatusChart"), options);
        chart.render();
    });
</script>
