<?php

class Body {
    private $conn;
    private $place;
    private $cause;
    private $hospital;
    private $b_gender;
    private $age_range;
    private $desc;
    private $creator;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addBody(array $user_data, $att_id) {
        $this->place = htmlspecialchars(strip_tags($user_data['place']));
        $this->cause = htmlspecialchars(strip_tags($user_data['cause']));
        $this->hospital = htmlspecialchars(strip_tags($user_data['hospital']));
        $this->b_gender = htmlspecialchars(strip_tags($user_data['gender']));
        $this->age_range = htmlspecialchars(strip_tags($user_data['age_range']));
        $this->desc = htmlspecialchars(strip_tags($user_data['desc']));
        $this->creator = $att_id;

        $query = "INSERT INTO bodies (place_found, cause, hospital, gender, age_range, desc_text, created_by)
                 VALUES (:plc, :cause, :hsp, :gnd, :agr, :desc, :creator)
                 RETURNING body_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':plc', $this->place);
        $stmt->bindValue(':cause', $this->cause);
        $stmt->bindValue(':hsp', $this->hospital);
        $stmt->bindValue(':gnd', $this->b_gender);
        $stmt->bindValue(':agr', $this->age_range);
        $stmt->bindValue(':desc', $this->desc);
        $stmt->bindValue(':creator', $this->creator);

        try {
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error adding body: " . $e->getMessage());
            return false;
        }
    }

    public function getBodies() {
        $query = "SELECT 
                    b.body_id,
                    l.location_desc,
                    c.cause_desc,
                    b.hospital,
                    g.gender_desc,
                    ar.age_range,
                    ar.range_desc,
                    b.desc_text,
                    b.created_on,
                    b.isclaimed,
                    a.f_name,
                    a.l_name
                FROM bodies b
                LEFT JOIN location l ON b.place_found = l.location_id
                LEFT JOIN causes c ON b.cause = c.cause_id
                LEFT JOIN gender g ON b.gender = g.gender_id
                LEFT JOIN age_range ar ON b.age_range = ar.range_id
                LEFT JOIN attendants a ON b.created_by = a.att_id
                ORDER BY b.created_on DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching bodies: " . $e->getMessage());
            return [];
        }
    }

    public function claimBody($u_id, $b_id) {
        $this->conn->beginTransaction();
        
        try {
            // Insert claim
            $query = "INSERT INTO claims (body_id, claimer_id) VALUES (:b_id, :u_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':b_id', $b_id);
            $stmt->bindValue(':u_id', $u_id);
            $stmt->execute();

            // Update body status
            $query = "UPDATE bodies SET isclaimed = TRUE WHERE body_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $b_id);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error claiming body: " . $e->getMessage());
            return false;
        }
    }

   private function changeClaim($b_id) {
      $query = "UPDATE bodies
               SET isClaimed = true
               WHERE body_id = :id";

      $stmt = $this->conn->prepare($query);
      $stmt->bindValue(':id', $b_id);
      $stmt->execute();
   }

   public function pickBody($b_id) {
      $query = "UPDATE bodies
               SET isPicked = true
               WHERE body_id = $b_id";
            
      $stmt = $this->conn->prepare($query);
      $stmt->bindValue(':id', $b_id);
      $stmt->execute();
   }
}