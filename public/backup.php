<?php
session_start();

require_once dirname(__DIR__) . '/config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

date_default_timezone_set('Asia/Jakarta');
$backupDir = dirname(__DIR__) . '/storage/backups/';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

function performBackup($conn, $backupDir) {
    $date = date('Y-m-d_H-i-s');
    $fileName = "amanparkir_backup_" . $date . ".sql";
    $backupFile = $backupDir . $fileName;
    
    $mysqldump = "C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe";
    $dbUser = "root";
    $dbPass = "";
    $dbName = "aman_parkir";
    
    $command =
        "\"$mysqldump\" " .
        "-u$dbUser " .
        ($dbPass ? "-p$dbPass " : "") .
        "$dbName " .
        "--result-file=\"$backupFile\"";
    exec($command, $output, $result);
    
    if ($result === 0 && file_exists($backupFile)) {
        $stmt = $conn->prepare(
            "INSERT INTO backup_logs (backup_file)
             VALUES (?)"
        );
        $stmt->bind_param("s", $fileName);
        $stmt->execute();
        return true;
    }
    return false;
}

// Check if this is scheduled backup or manual
$isScheduled = isset($_GET['scheduled']) && $_GET['scheduled'] == '1';

if (performBackup($conn, $backupDir)) {
    if ($isScheduled) {
        echo json_encode(['success' => true, 'message' => 'Backup terjadwal berhasil']);
        exit;
    }
    echo "
    <script>
    alert('Backup berhasil!');
    window.location.href='index.php';
    </script>
    ";
} else {
    if ($isScheduled) {
        echo json_encode(['success' => false, 'message' => 'Backup terjadwal gagal']);
        exit;
    }
    echo "
    <script>
        alert('Backup gagal! Periksa konfigurasi mysqldump.');
        window.location.href='index.php';
    </script>
    ";
}
?>
