<?php
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/app/controllers/ParkingController.php';

$controller = new ParkingController($conn);
$controller->entry();
?>
