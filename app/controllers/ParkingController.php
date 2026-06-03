<?php
require_once __DIR__ . '/../models/ParkingArea.php';
require_once __DIR__ . '/../models/ParkingTransaction.php';
require_once __DIR__ . '/../models/Vehicle.php';

class ParkingController {
    private $parkingArea;
    private $parkingTransaction;
    private $vehicle;
    
    public function __construct($db) {
        $this->parkingArea = new ParkingArea($db);
        $this->parkingTransaction = new ParkingTransaction($db);
        $this->vehicle = new Vehicle($db);
    }
    
    public function entry() {
        $parkingAreas = $this->parkingArea->getAll();
        $vehicleTypes = $this->vehicle->getVehicleTypes();
        
        $data = [
            'parkingAreas' => $parkingAreas,
            'vehicleTypes' => $vehicleTypes,
            'message' => null
        ];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->processEntry($_POST);
        }
        
        require_once __DIR__ . '/../views/entry.php';
    }
    
    public function exit() {
        $parkingAreas = $this->parkingArea->getAll();
        $vehicleTypes = $this->vehicle->getVehicleTypes();
        
        $data = [
            'parkingAreas' => $parkingAreas,
            'vehicleTypes' => $vehicleTypes,
            'message' => null,
            'transaction' => null
        ];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->processExit($_POST);
        }
        
        require_once __DIR__ . '/../views/exit.php';
    }
    
    private function processEntry($formData) {
        $plateNumber = strtoupper(trim($formData['plate_number'] ?? ''));
        $vehicleTypeId = intval($formData['vehicle_type_id'] ?? 0);
        $parkingAreaId = intval($formData['parking_area_id'] ?? 0);
        $ownerName = $formData['owner_name'] ?? null;
        $ownerPhone = $formData['owner_phone'] ?? null;
        $ownerEmail = $formData['owner_email'] ?? null;
        
        if (empty($plateNumber)) {
            return ['message' => ['type' => 'error', 'text' => 'Nomor plat harus diisi'], 'parkingAreas' => $this->parkingArea->getAll(), 'vehicleTypes' => $this->vehicle->getVehicleTypes()];
        }
        
        if ($vehicleTypeId <= 0) {
            return ['message' => ['type' => 'error', 'text' => 'Tipe kendaraan harus dipilih'], 'parkingAreas' => $this->parkingArea->getAll(), 'vehicleTypes' => $this->vehicle->getVehicleTypes()];
        }
        
        if ($parkingAreaId <= 0) {
            return ['message' => ['type' => 'error', 'text' => 'Area parkir harus dipilih'], 'parkingAreas' => $this->parkingArea->getAll(), 'vehicleTypes' => $this->vehicle->getVehicleTypes()];
        }
        
        $vehicleTypes = $this->vehicle->getVehicleTypes();
        $typeCode = null;
        foreach ($vehicleTypes as $vt) {
            if ($vt['id'] == $vehicleTypeId) {
                $typeCode = $vt['type_code'];
                break;
            }
        }
        
        if (!$this->parkingArea->checkCapacity($parkingAreaId, $typeCode)) {
            return ['message' => ['type' => 'error', 'text' => 'Kapasitas area parkir sudah penuh untuk tipe kendaraan ini'], 'parkingAreas' => $this->parkingArea->getAll(), 'vehicleTypes' => $this->vehicle->getVehicleTypes()];
        }
        
        $vehicle = $this->vehicle->getOrCreate($plateNumber, $vehicleTypeId, $ownerName, $ownerPhone, $ownerEmail);
        
        if (!$vehicle) {
            return ['message' => ['type' => 'error', 'text' => 'Error membuat data kendaraan'], 'parkingAreas' => $this->parkingArea->getAll(), 'vehicleTypes' => $this->vehicle->getVehicleTypes()];
        }
        
        $result = $this->parkingTransaction->recordEntry($vehicle['id'], $parkingAreaId);
        
        if ($result['success']) {
            return [
                'message' => ['type' => 'success', 'text' => $result['message']],
                'parkingAreas' => $this->parkingArea->getAll(),
                'vehicleTypes' => $this->vehicle->getVehicleTypes(),
                'transaction' => ['plate' => $plateNumber, 'type' => $typeCode, 'area' => 'OK']
            ];
        } else {
            return ['message' => ['type' => 'error', 'text' => $result['message']], 'parkingAreas' => $this->parkingArea->getAll(), 'vehicleTypes' => $this->vehicle->getVehicleTypes()];
        }
    }
    
    private function processExit($formData) {
        $plateNumber = strtoupper(trim($formData['plate_number'] ?? ''));
        $parkingAreaId = intval($formData['parking_area_id'] ?? 0);
        
        if (empty($plateNumber)) {
            return ['message' => ['type' => 'error', 'text' => 'Nomor plat harus diisi'], 'parkingAreas' => $this->parkingArea->getAll(), 'vehicleTypes' => $this->vehicle->getVehicleTypes(), 'transaction' => null];
        }
        
        if ($parkingAreaId <= 0) {
            return ['message' => ['type' => 'error', 'text' => 'Area parkir harus dipilih'], 'parkingAreas' => $this->parkingArea->getAll(), 'vehicleTypes' => $this->vehicle->getVehicleTypes(), 'transaction' => null];
        }
        
        $vehicle = $this->vehicle->getByPlateNumber($plateNumber);
        
        if (!$vehicle) {
            return ['message' => ['type' => 'error', 'text' => 'Kendaraan dengan nomor plat ini tidak ditemukan'], 'parkingAreas' => $this->parkingArea->getAll(), 'vehicleTypes' => $this->vehicle->getVehicleTypes(), 'transaction' => null];
        }
        
        $transaction = $this->parkingTransaction->getActiveTransaction($vehicle['id']);
        
        if (!$transaction) {
            return ['message' => ['type' => 'error', 'text' => 'Kendaraan tidak sedang parkir di area ini'], 'parkingAreas' => $this->parkingArea->getAll(), 'vehicleTypes' => $this->vehicle->getVehicleTypes(), 'transaction' => null];
        }
        
        $result = $this->parkingTransaction->recordExit($vehicle['id'], $parkingAreaId);
        
        if ($result['success']) {
            $exitedTransaction = $this->parkingTransaction->getById($transaction['id']);
            
            return [
                'message' => ['type' => 'success', 'text' => $result['message']],
                'parkingAreas' => $this->parkingArea->getAll(),
                'vehicleTypes' => $this->vehicle->getVehicleTypes(),
                'transaction' => $exitedTransaction
            ];
        } else {
            return ['message' => ['type' => 'error', 'text' => $result['message']], 'parkingAreas' => $this->parkingArea->getAll(), 'vehicleTypes' => $this->vehicle->getVehicleTypes(), 'transaction' => null];
        }
    }
}

?>
