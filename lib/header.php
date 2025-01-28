<?php

// Fungsi untuk menentukan menu yang aktif
function isActive($page) {
    $current_page = basename($_SERVER['PHP_SELF'], ".php"); // Ambil nama file PHP
    return $current_page === $page ? "hs-accordion-active text-primary bg-primary/10" : "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>MyTask | Aplikasi Pencatat Task</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Aplikasi pencatat task otomatis untuk membantu keberlangsungan." name="description">
    <meta content="Marshep Ollo" name="author">
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Jsvectormap plugin css -->
    <link href="assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css">

    <!-- Icons css  (Mandatory in All Pages) -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">

    <!-- App css  (Mandatory in All Pages) -->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>

<body>
    <div class="wrapper">
        <!-- Start Sidebar -->
        <aside id="app-menu" class="hs-overlay fixed inset-y-0 start-0 z-60 hidden w-sidenav min-w-sidenav -translate-x-full transform overflow-y-auto bg-default-900 transition-all duration-300 hs-overlay-open:translate-x-0 lg:bottom-0 lg:end-auto lg:z-30 lg:block lg:translate-x-0 rtl:translate-x-full rtl:hs-overlay-open:translate-x-0 rtl:lg:translate-x-0 print:hidden [--body-scroll:true] [--overlay-backdrop:true] lg:[--overlay-backdrop:false]">
            <div class="sticky top-0 flex items-center justify-center h-16 px-6">
                <a href="index.php">
                    <img src="assets/images/mytask.png" alt="logo" class="flex h-10">
                </a>
            </div>

            <div class="h-[calc(100%-64px)] p-4" data-simplebar>
                <ul class="admin-menu hs-accordion-group flex w-full flex-col gap-1.5">

                    <!-- Tampilkan Login dan Register hanya jika user belum login -->
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-300 transition-all hover:bg-default-100/5 <?= isActive('login'); ?>"
                                href="login.php">
                                <i class="text-2xl font-light transition-all material-symbols-rounded group-hover:fill-1">login</i>
                                Login
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-300 transition-all hover:bg-default-100/5 <?= isActive('register'); ?>"
                                href="register.php">
                                <i class="text-2xl font-light transition-all material-symbols-rounded group-hover:fill-1">open_in_new</i>
                                Register
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Tampilkan menu lainnya jika user sudah login -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-300 transition-all hover:bg-default-100/5 <?= isActive('index'); ?>"
                                href="index.php">
                                <i class="text-2xl font-light transition-all material-symbols-rounded group-hover:fill-1">home</i>
                                Dashboard
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-300 transition-all hover:bg-default-100/5 <?= isActive('task'); ?>"
                                href="task.php">
                                <i class="text-2xl font-light transition-all material-symbols-rounded group-hover:fill-1">add_circle</i>
                                Add Task
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-300 transition-all hover:bg-default-100/5 <?= isActive('task_priority'); ?>"
                                href="task_priority.php">
                                <i class="text-2xl font-light transition-all material-symbols-rounded group-hover:fill-1">star</i>
                                Task Priority
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-300 transition-all hover:bg-default-100/5 <?= isActive('task_list'); ?>"
                                href="task_list.php">
                                <i class="text-2xl font-light transition-all material-symbols-rounded group-hover:fill-1">article</i>
                                Task List
                            </a>
                        </li>
                        <li class="menu-item">
                            <a class="group flex items-center gap-x-3.5 rounded-md px-3 py-2 text-sm font-medium text-default-300 transition-all hover:bg-default-100/5 <?= isActive('logout'); ?>"
                                href="logout.php">
                                <i class="text-2xl font-light transition-all material-symbols-rounded group-hover:fill-1">logout</i>
                                Logout
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </aside>
        <!-- End Sidebar -->

        <!-- Page Content -->
        <div class="page-content">
            <header class="app-header">
                <div class="flex items-center h-16 gap-4 px-5 bg-white border-b lg:rounded-t-xl border-default-100">
                    <button id="button-toggle-menu" class="p-2 rounded-full cursor-pointer text-default-500 hover:text-default-600"
                        data-hs-overlay="#app-menu" aria-label="Toggle navigation">
                        <i class="text-2xl i-tabler-menu-2"></i>
                    </button>
                </div>
            </header>
