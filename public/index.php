<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';

$db_connected = false;
try {
    $result = $conn->query("SELECT 1 FROM parking_areas LIMIT 1");
    if ($result) {
        $db_connected = true;
    }
} catch (Exception $e) {
    $db_connected = false;
}

if (!$db_connected) {
    require_once __DIR__ . '/setup.php';
    exit();
}

$request = isset($_GET['url']) ? trim($_GET['url'], '/') : '';

if (empty($request) || $request === 'home') {
    $controller = new HomeController($conn);
    $controller->index();
} else {
    require_once __DIR__ . '/../../app/views/header.php';
    echo '<div class="page-header"><h1>404 - Halaman Tidak Ditemukan</h1></div>';
    require_once __DIR__ . '/../../app/views/footer.php';
}
?>
