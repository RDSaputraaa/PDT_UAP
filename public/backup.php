<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

date_default_timezone_set('Asia/Jakarta');

$backupDir = dirname(__DIR__) . '/storage/backups/';

if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$date = date('Y-m-d_H-i-s');

$backupFile = $backupDir . "amanparkir_backup_$date.sql";

$mysqldump = "C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe";

$dbUser = "root";
$dbPass = "";
$dbName = "aman_parkir";

$command =
    "\"$mysqldump\" -u$dbUser " .
    ($dbPass ? "-p$dbPass " : "") .
    "$dbName --result-file=\"$backupFile\"";

exec($command, $output, $result);

if ($result == 0) {

    header("Location: backup_list.php?status=success");

} else {

    header("Location: backup_list.php?status=failed");

}

exit;
?>
