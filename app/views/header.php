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
                <li><a href="./" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Dashboard</a></li>
                <li><a href="./entry.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'entry') !== false ? 'active' : ''; ?>">Masuk Kendaraan</a></li>
                <li><a href="./exit.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'exit') !== false ? 'active' : ''; ?>">Keluar Kendaraan</a></li>
                <li><a href="./reports.php" class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'reports') !== false ? 'active' : ''; ?>">Laporan</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
