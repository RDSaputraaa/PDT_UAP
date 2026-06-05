<?php
/**
 * Backup Scheduler - Automatic backup every day
 * Usage: Add this to cron job: curl http://localhost/UAP_PDT/public/scheduler.php
 * Or visit manually: http://localhost/UAP_PDT/public/scheduler.php?token=YOUR_SECRET_TOKEN
 */

require_once dirname(__DIR__) . '/config/database.php';

date_default_timezone_set('Asia/Jakarta');

// Simple token for security (change this!)
$SCHEDULER_TOKEN = 'aman_parkir_backup_2026';
$requestToken = $_GET['token'] ?? '';

// Allow scheduler requests with token or from localhost
$isLocalhost = $_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === 'localhost';
$hasValidToken = !empty($requestToken) && hash_equals($SCHEDULER_TOKEN, $requestToken);

if (!$isLocalhost && !$hasValidToken) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check if backup already done today
$checkQuery = "SELECT id FROM backup_logs WHERE DATE(backup_date) = CURDATE() LIMIT 1";
$result = $conn->query($checkQuery);

if ($result->num_rows > 0) {
    echo json_encode([
        'success' => true,
        'message' => 'Backup sudah dilakukan hari ini',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

// Perform backup
$backupDir = dirname(__DIR__) . '/storage/backups/';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

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
    
    echo json_encode([
        'success' => true,
        'message' => 'Backup otomatis berhasil',
        'file' => $fileName,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Backup gagal',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}

$conn->close();
?>
