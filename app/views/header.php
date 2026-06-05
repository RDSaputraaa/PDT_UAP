<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>AmanParkir - Sistem Manajemen Parkir FMIPA</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <i class="fas fa-parking"></i>
                <span>AmanParkir</span>
            </div>
            <ul class="navbar-menu">
                <li><a href="./" class="nav-link">Dashboard</a></li>
                <li><a href="./entry.php" class="nav-link">Masuk Kendaraan</a></li>
                <li><a href="./exit.php" class="nav-link">Keluar Kendaraan</a></li>
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <li><a href="./reports.php" class="nav-link">Laporan</a></li>
                <li><a href="./backup.php" class="nav-link">Backup</a></li>
                <?php endif; ?>
                <li><a href="./logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
