<?php

class ParkingTransaction {
    private $conn;
    private $table = 'parking_transactions';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function recordEntry($vehicle_id, $parking_area_id) {
        $check = $this->getActiveTransaction($vehicle_id);
        if ($check) {
            return array('success' => false, 'message' => 'Kendaraan sudah terdaftar sedang parkir');
        }
        
        $query = "INSERT INTO " . $this->table . " 
                  (vehicle_id, parking_area_id, entry_time, is_active)
                  VALUES (?, ?, NOW(), TRUE)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $vehicle_id, $parking_area_id);
        
        if ($stmt->execute()) {
            return array('success' => true, 'id' => $this->conn->insert_id, 'message' => 'Kendaraan berhasil masuk area parkir');
        } else {
            return array('success' => false, 'message' => 'Error: ' . $stmt->error);
        }
    }
    
    public function recordExit($vehicle_id, $parking_area_id) {
        $query = "UPDATE " . $this->table . " 
                  SET exit_time = NOW(), is_active = FALSE,
                      duration_minutes = TIMESTAMPDIFF(MINUTE, entry_time, NOW())
                  WHERE vehicle_id = ? AND parking_area_id = ? AND is_active = TRUE";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $vehicle_id, $parking_area_id);
        
        if ($stmt->execute()) {
            return array('success' => true, 'message' => 'Kendaraan berhasil keluar dari area parkir');
        } else {
            return array('success' => false, 'message' => 'Error: ' . $stmt->error);
        }
    }
    
    public function getActiveTransaction($vehicle_id) {
        $query = "SELECT pt.*, v.plate_number, v.vehicle_type_id, pa.area_name
                  FROM " . $this->table . " pt
                  JOIN vehicles v ON pt.vehicle_id = v.id
                  JOIN parking_areas pa ON pt.parking_area_id = pa.id
                  WHERE pt.vehicle_id = ? AND pt.is_active = TRUE";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $vehicle_id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getById($id) {
        $query = "SELECT pt.*, v.plate_number, v.owner_name, pa.area_name
                  FROM " . $this->table . " pt
                  JOIN vehicles v ON pt.vehicle_id = v.id
                  JOIN parking_areas pa ON pt.parking_area_id = pa.id
                  WHERE pt.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getAll($limit = 100, $offset = 0) {
        $query = "SELECT pt.*, v.plate_number, v.owner_name, vt.type_name, pa.area_name
                  FROM " . $this->table . " pt
                  JOIN vehicles v ON pt.vehicle_id = v.id
                  JOIN vehicle_types vt ON v.vehicle_type_id = vt.id
                  JOIN parking_areas pa ON pt.parking_area_id = pa.id
                  ORDER BY pt.created_at DESC
                  LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getActiveInArea($parking_area_id) {
        $query = "SELECT pt.*, v.plate_number, v.owner_name, vt.type_name
                  FROM " . $this->table . " pt
                  JOIN vehicles v ON pt.vehicle_id = v.id
                  JOIN vehicle_types vt ON v.vehicle_type_id = vt.id
                  WHERE pt.parking_area_id = ? AND pt.is_active = TRUE
                  ORDER BY pt.entry_time DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $parking_area_id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getStatistics($start_date, $end_date) {
        $query = "SELECT 
                    pa.id,
                    pa.area_code,
                    pa.area_name,
                    COUNT(CASE WHEN vt.type_code = 'MC' THEN 1 END) as total_motorcycles,
                    COUNT(CASE WHEN vt.type_code = 'CAR' THEN 1 END) as total_cars,
                    COUNT(*) as total_vehicles,
                    AVG(CASE WHEN pt.duration_minutes > 0 THEN pt.duration_minutes ELSE NULL END) as avg_duration
                  FROM " . $this->table . " pt
                  JOIN parking_areas pa ON pt.parking_area_id = pa.id
                  JOIN vehicles v ON pt.vehicle_id = v.id
                  JOIN vehicle_types vt ON v.vehicle_type_id = vt.id
                  WHERE DATE(pt.entry_time) BETWEEN ? AND ? AND pt.exit_time IS NOT NULL
                  GROUP BY pa.id, pa.area_code, pa.area_name";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

?>
