<?php

class User {
    // Database connection
    private $conn;

    // User properties
    private $f_name;
    private $l_name;
    private $lcn;
    private $nat_id;
    private $email;
    private $phone;
    private $pwd;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Validate location before registration
    private function validateLocation($location_id) {
      try {
          $query = "SELECT COUNT(*) FROM location WHERE location_id = :location_id";
          $stmt = $this->conn->prepare($query);
          $stmt->bindValue(':location_id', $location_id);
          $stmt->execute();
          
          return $stmt->fetchColumn() > 0;
      } catch (PDOException $e) {
          error_log("Location validation error: " . $e->getMessage());
          return false;
      }
  }
    public function register(array $user_data) {
      if (!$this->validateLocation($user_data['lcn'])) {
         return [
             'success' => false,
             'message' => 'Invalid location selected'
         ];
     }
        // Clean and validate input data
        $this->f_name = htmlspecialchars(strip_tags($user_data['fname']));
        $this->l_name = htmlspecialchars(strip_tags($user_data['lname']));
        $this->lcn = htmlspecialchars(strip_tags($user_data['lcn']));
        $this->nat_id = htmlspecialchars(strip_tags($user_data['nat_id']));
        $this->email = htmlspecialchars(strip_tags($user_data['email']));
        $this->phone = htmlspecialchars(strip_tags($user_data['phone']));
        $this->pwd = htmlspecialchars(strip_tags($user_data['pwd']));

        // Check if email or national ID already exists
        if ($this->emailExists($this->email)) {
            return ['success' => false, 'message' => 'Email already registered'];
        }
        if ($this->natIdExists($this->nat_id)) {
            return ['success' => false, 'message' => 'National ID already registered'];
        }

        $query = "INSERT INTO users (f_name, l_name, location_id, nat_id, email, phone, password)
                 VALUES (:fname, :lname, :lct, :nat, :email, :phone, :pwd)
                 RETURNING user_id";

        try {
            $stmt = $this->conn->prepare($query);

            // Hash password before saving
            $hashed_password = password_hash($this->pwd, PASSWORD_BCRYPT);

            // Bind values
            $stmt->bindValue(':fname', $this->f_name);
            $stmt->bindValue(':lname', $this->l_name);
            $stmt->bindValue(':lct', $this->lcn);
            $stmt->bindValue(':nat', $this->nat_id);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':phone', $this->phone);
            $stmt->bindValue(':pwd', $hashed_password);

            $stmt->execute();
            $user_id = $stmt->fetchColumn();

            return [
                'success' => true,
                'user_id' => $user_id,
                'message' => 'Registration successful'
            ];
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ];
        }
    }

    private function emailExists($email) {
        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Email check error: " . $e->getMessage());
            return false;
        }
    }

    private function natIdExists($nat_id) {
        $query = "SELECT COUNT(*) FROM users WHERE nat_id = :nat_id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':nat_id', $nat_id);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("National ID check error: " . $e->getMessage());
            return false;
        }
    }

    private function getWithEmail($email) {
        $query = "SELECT 
                    u.user_id,
                    u.f_name,
                    u.l_name,
                    u.location_id,
                    l.location_desc,
                    u.nat_id,
                    u.email,
                    u.phone,
                    u.password,
                    u.created_at
                 FROM users u
                 LEFT JOIN location l ON u.location_id = l.location_id
                 WHERE u.email = :email";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get user error: " . $e->getMessage());
            return false;
        }
    }

    public function logIn($email, $password) {
        try {
            $user = $this->getWithEmail($email);
            
            if (!$user) {
                return [
                    'isLogged' => false,
                    'message' => 'User not found',
                    'userObject' => null
                ];
            }

            if (password_verify($password, $user['password'])) {
                // Remove password from user object before returning
                unset($user['password']);
                
                return [
                    'isLogged' => true,
                    'message' => 'Login successful',
                    'userObject' => $user
                ];
            }

            return [
                'isLogged' => false,
                'message' => 'Invalid password',
                'userObject' => null
            ];
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return [
                'isLogged' => false,
                'message' => 'Login failed. Please try again.',
                'userObject' => null
            ];
        }
    }

    public function getUserClaims($user_id) {
        $query = "SELECT 
                    c.claim_id,
                    c.claimed_at,
                    b.body_id,
                    b.hospital,
                    b.desc_text,
                    b.ispicked,
                    l.location_desc,
                    g.gender_desc,
                    ar.range_desc
                 FROM claims c
                 JOIN bodies b ON c.body_id = b.body_id
                 LEFT JOIN location l ON b.place_found = l.location_id
                 LEFT JOIN gender g ON b.gender = g.gender_id
                 LEFT JOIN age_range ar ON b.age_range = ar.range_id
                 WHERE c.claimer_id = :user_id
                 ORDER BY c.claimed_at DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute();

            return [
                'success' => true,
                'claims' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            error_log("Get claims error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to retrieve claims'
            ];
        }
    }
}