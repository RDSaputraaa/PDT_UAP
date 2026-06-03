<?php
require_once __DIR__ . '/../models/ParkingArea.php';
require_once __DIR__ . '/../models/ParkingTransaction.php';
require_once __DIR__ . '/../models/Vehicle.php';

class HomeController {
    private $parkingArea;
    private $parkingTransaction;
    private $vehicle;
    
    public function __construct($db) {
        $this->parkingArea = new ParkingArea($db);
        $this->parkingTransaction = new ParkingTransaction($db);
        $this->vehicle = new Vehicle($db);
    }
    
    public function index() {
        $parkingAreas = $this->parkingArea->getAllWithStatus();
        
        $totalParking = 0;
        $totalMotorcycles = 0;
        $totalCars = 0;
        
        foreach ($parkingAreas as $area) {
            $totalParking += $area['current_vehicles'];
            $totalMotorcycles += $area['current_motorcycles'];
            $totalCars += $area['current_cars'];
        }
        
        $recentTransactions = $this->parkingTransaction->getAll(10, 0);
        
        $data = [
            'parkingAreas' => $parkingAreas,
            'totalParking' => $totalParking,
            'totalMotorcycles' => $totalMotorcycles,
            'totalCars' => $totalCars,
            'recentTransactions' => $recentTransactions
        ];
        
        require_once __DIR__ . '/../views/dashboard.php';
    }
}
?>
