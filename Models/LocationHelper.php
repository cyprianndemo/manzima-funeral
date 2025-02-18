<?php

class LocationHelper {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllLocations() {
        try {
            $query = "SELECT location_id, location_desc FROM location ORDER BY location_desc";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return [
                'success' => true,
                'locations' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            error_log("Error fetching locations: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to retrieve locations',
                'locations' => []
            ];
        }
    }

    public function getLocationById($id) {
        try {
            $query = "SELECT location_id, location_desc FROM location WHERE location_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            
            return [
                'success' => true,
                'location' => $stmt->fetch(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            error_log("Error fetching location: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to retrieve location'
            ];
        }
    }

    public function searchLocations($searchTerm) {
        try {
            $query = "SELECT location_id, location_desc 
                     FROM location 
                     WHERE location_desc ILIKE :search 
                     ORDER BY location_desc";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':search', '%' . $searchTerm . '%');
            $stmt->execute();
            
            return [
                'success' => true,
                'locations' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            error_log("Error searching locations: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to search locations',
                'locations' => []
            ];
        }
    }
}