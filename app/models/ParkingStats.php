<?php
/**
 * P2: Database Views Integration
 * Provides comprehensive parking statistics using database views
 */

class ParkingStats {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Get comprehensive parking statistics with joins and aggregations
     * P2: Uses SQL JOINs and aggregation functions
     */
    public function getComprehensiveStats($start_date = null, $end_date = null) {
        if (!$start_date) $start_date = date('Y-m-d');
        if (!$end_date) $end_date = date('Y-m-d');
        
        $query = "SELECT 
                    pa.id,
                    pa.area_code,
                    pa.area_name,
                    COUNT(DISTINCT pt.id) as total_transactions,
                    COUNT(DISTINCT CASE WHEN pt.is_active = TRUE THEN pt.id END) as active_vehicles,
                    COUNT(DISTINCT CASE WHEN pt.is_active = FALSE THEN pt.id END) as completed_transactions,
                    COUNT(DISTINCT CASE WHEN vt.type_code = 'MC' THEN pt.id END) as motorcycle_count,
                    COUNT(DISTINCT CASE WHEN vt.type_code = 'CAR' THEN pt.id END) as car_count,
                    ROUND(AVG(CASE WHEN pt.duration_minutes > 0 THEN pt.duration_minutes ELSE NULL END), 2) as avg_duration_minutes,
                    MAX(pt.entry_time) as last_entry
                  FROM parking_areas pa
                  LEFT JOIN parking_transactions pt ON pa.id = pt.parking_area_id 
                    AND DATE(pt.entry_time) BETWEEN ? AND ?
                  LEFT JOIN vehicles v ON pt.vehicle_id = v.id
                  LEFT JOIN vehicle_types vt ON v.vehicle_type_id = vt.id
                  WHERE pa.status = 'active'
                  GROUP BY pa.id, pa.area_code, pa.area_name
                  ORDER BY pa.area_code ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get summary statistics across all areas
     * P2: Uses SQL aggregation with UNION-like operations
     */
    public function getSummaryStats() {
        $query = "SELECT 
                    'SUMMARY' as type,
                    COUNT(DISTINCT pa.id) as total_areas,
                    COUNT(DISTINCT CASE WHEN pt.is_active = TRUE THEN pt.id END) as current_vehicles,
                    COUNT(DISTINCT CASE WHEN pt.is_active = FALSE THEN pt.id END) as completed_today,
                    COUNT(DISTINCT v.id) as unique_vehicles,
                    ROUND(AVG(CASE WHEN pt.duration_minutes > 0 THEN pt.duration_minutes ELSE NULL END), 2) as avg_duration
                  FROM parking_areas pa
                  LEFT JOIN parking_transactions pt ON pa.id = pt.parking_area_id 
                    AND DATE(pt.entry_time) = CURDATE()
                  LEFT JOIN vehicles v ON pt.vehicle_id = v.id
                  WHERE pa.status = 'active'";
        
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }
}

?>
