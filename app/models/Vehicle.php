<?php

class Vehicle {
    private $conn;
    private $table = 'vehicles';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getOrCreate($plate_number, $vehicle_type_id, $owner_name = null, $owner_phone = null, $owner_email = null) {
        $vehicle = $this->getByPlateNumber($plate_number);
        
        if ($vehicle) {
            return $vehicle;
        }
        
        $query = "INSERT INTO " . $this->table . " 
                  (plate_number, vehicle_type_id, owner_name, owner_phone, owner_email)
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sisss", 
            $plate_number,
            $vehicle_type_id,
            $owner_name,
            $owner_phone,
            $owner_email
        );
        
        if ($stmt->execute()) {
            return $this->getById($this->conn->insert_id);
        }
        
        return false;
    }
    
    public function getByPlateNumber($plate_number) {
        $query = "SELECT v.*, vt.type_name, vt.type_code 
                  FROM " . $this->table . " v
                  JOIN vehicle_types vt ON v.vehicle_type_id = vt.id
                  WHERE v.plate_number = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $plate_number);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getById($id) {
        $query = "SELECT v.*, vt.type_name, vt.type_code 
                  FROM " . $this->table . " v
                  JOIN vehicle_types vt ON v.vehicle_type_id = vt.id
                  WHERE v.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getAll() {
        $query = "SELECT v.*, vt.type_name, vt.type_code 
                  FROM " . $this->table . " v
                  JOIN vehicle_types vt ON v.vehicle_type_id = vt.id
                  ORDER BY v.created_at DESC";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getVehicleTypes() {
        $query = "SELECT * FROM vehicle_types ORDER BY type_name ASC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

?>
