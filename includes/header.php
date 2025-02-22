<?php
require_once 'connect.php';
require_once 'auth.php';
require_once 'functions.php';

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : "Event Management System"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="/evs/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <?php if(is_logged_in()): ?>
            <div class="container">
                <a class="nav-brand text-decoration-none text-white" href="/evs/">
                    <h1 class="fs-2 fw-bold mb-0">EVS</h1>
                    <p class="mb-0">Event Management System</p>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav m-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/evs/">Home</a>
                        </li>
                        <?php if (is_logged_in()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/evs/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/evs/pages/events/create.php">Create Event</a>
                        </li>
                        <?php if (is_admin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/evs/attendees.php">Attendees</a>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav">
                        <?php if (is_logged_in()): ?>
                        <!-- Search Form -->
                        <form class="d-flex me-0 me-lg-2 order-1 order-lg-0 mb-2 mb-lg-0" action="/evs/search.php" method="GET">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" 
                                    placeholder="Search events and attendees..." 
                                    value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                                <button class="btn btn-outline-light" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                        <li class="nav-item">
                            <span class="nav-link">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/evs/logout.php">Logout</a>
                        </li>
                        <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/evs/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/evs/register.php">Register</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <div class="container d-flex justify-content-between align-items-center">
                <a class="nav-brand text-decoration-none text-white" href="/evs/">
                    <h1 class="fs-2 fw-bold mb-0">EVS</h1>
                    <p class="mb-0">Event Management System</p>
                </a>
                <div class="nav-buttons">
                    <a href="/evs/login.php" class="btn btn-secondary me-sm-2">Login</a>
                    <a href="/evs/register.php" class="btn btn-primary">Register</a>
                </div>
            </div>
        <?php endif; ?>
    </nav>

    <?php if ($current_page !== 'index.php'): ?>
        <div class="container my-5">
        <?php display_flash_messages(); ?>
    <?php endif; ?>