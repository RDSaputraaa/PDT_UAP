<?php

class ParkingArea {
    private $conn;
    private $table = 'parking_areas';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " WHERE status = 'active' ORDER BY area_code ASC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getWithStatus($id) {
        $query = "SELECT pa.*, 
                  (SELECT COUNT(*) FROM parking_transactions WHERE parking_area_id = pa.id AND is_active = TRUE AND DATE(entry_time) = CURDATE()) as transactions_today
                  FROM " . $this->table . " pa WHERE pa.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getAllWithStatus() {
        $query = "SELECT 
                    pa.*,
                    (SELECT COUNT(*) FROM parking_transactions WHERE parking_area_id = pa.id AND is_active = TRUE) as current_vehicles,
                    (SELECT COUNT(*) FROM parking_transactions pt 
                        JOIN vehicles v ON pt.vehicle_id = v.id 
                        JOIN vehicle_types vt ON v.vehicle_type_id = vt.id
                        WHERE pt.parking_area_id = pa.id AND pt.is_active = TRUE AND vt.type_code = 'MC') as current_motorcycles,
                    (SELECT COUNT(*) FROM parking_transactions pt 
                        JOIN vehicles v ON pt.vehicle_id = v.id 
                        JOIN vehicle_types vt ON v.vehicle_type_id = vt.id
                        WHERE pt.parking_area_id = pa.id AND pt.is_active = TRUE AND vt.type_code = 'CAR') as current_cars
                  FROM " . $this->table . " pa 
                  WHERE pa.status = 'active'
                  ORDER BY pa.area_code ASC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (area_code, area_name, location, total_capacity, motorcycle_capacity, car_capacity, status)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssiiiis", 
            $data['area_code'],
            $data['area_name'],
            $data['location'],
            $data['total_capacity'],
            $data['motorcycle_capacity'],
            $data['car_capacity'],
            $data['status']
        );
        
        return $stmt->execute();
    }
    
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET area_code = ?, area_name = ?, location = ?, total_capacity = ?, 
                      motorcycle_capacity = ?, car_capacity = ?, status = ?
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssiiiis", 
            $data['area_code'],
            $data['area_name'],
            $data['location'],
            $data['total_capacity'],
            $data['motorcycle_capacity'],
            $data['car_capacity'],
            $data['status'],
            $id
        );
        
        return $stmt->execute();
    }
    
    public function checkCapacity($area_id, $vehicle_type) {
        $area = $this->getById($area_id);
        
        if ($vehicle_type == 'MC') {
            return $area['current_motorcycle'] < $area['motorcycle_capacity'];
        } else {
            return $area['current_car'] < $area['car_capacity'];
        }
    }
}

?>
