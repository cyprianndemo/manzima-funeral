<?php

class Attendant {
    private $conn;

    private $fname;
    private $lname;
    private $email;
    private $pwd;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register(array $user_data) {
        $this->fname = htmlspecialchars(strip_tags($user_data['fname']));
        $this->lname = htmlspecialchars(strip_tags($user_data['lname']));
        $this->email = htmlspecialchars(strip_tags($user_data['email']));
        $this->pwd = htmlspecialchars(strip_tags($user_data['pwd']));

        if ($this->emailExists($this->email)) {
            return ['success' => false, 'message' => 'Email already registered'];
        }

        $query = "INSERT INTO attendants (f_name, l_name, email, password)
                 VALUES (:fname, :lname, :email, :pwd)
                 RETURNING att_id";

        try {
            $stmt = $this->conn->prepare($query);

            // Hash password before saving
            $hashed_password = password_hash($this->pwd, PASSWORD_BCRYPT);

            // Bind values
            $stmt->bindValue(':fname', $this->fname);
            $stmt->bindValue(':lname', $this->lname);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':pwd', $hashed_password);

            $stmt->execute();
            $att_id = $stmt->fetchColumn();

            return [
                'success' => true,
                'attendant_id' => $att_id,
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
        $query = "SELECT COUNT(*) FROM attendants WHERE email = :email";
        
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

    private function getWithEmail($email) {
        $query = "SELECT 
                    att_id,
                    f_name,
                    l_name,
                    email,
                    password,
                    created_at
                 FROM attendants 
                 WHERE email = :email";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get attendant error: " . $e->getMessage());
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

    public function updateProfile($att_id, array $data) {
        $updateFields = [];
        $bindValues = [];

        // Only update fields that are provided
        if (!empty($data['fname'])) {
            $updateFields[] = "f_name = :fname";
            $bindValues[':fname'] = htmlspecialchars(strip_tags($data['fname']));
        }
        if (!empty($data['lname'])) {
            $updateFields[] = "l_name = :lname";
            $bindValues[':lname'] = htmlspecialchars(strip_tags($data['lname']));
        }
        if (!empty($data['email'])) {
            $updateFields[] = "email = :email";
            $bindValues[':email'] = htmlspecialchars(strip_tags($data['email']));
        }
        if (!empty($data['pwd'])) {
            $updateFields[] = "password = :pwd";
            $bindValues[':pwd'] = password_hash(
                htmlspecialchars(strip_tags($data['pwd'])), 
                PASSWORD_BCRYPT
            );
        }

        if (empty($updateFields)) {
            return ['success' => false, 'message' => 'No fields to update'];
        }

        $query = "UPDATE attendants SET " . 
                 implode(", ", $updateFields) . 
                 " WHERE att_id = :att_id";
        
        $bindValues[':att_id'] = $att_id;

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($bindValues);

            return [
                'success' => true,
                'message' => 'Profile updated successfully'
            ];
        } catch (PDOException $e) {
            error_log("Update profile error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Profile update failed. Please try again.'
            ];
        }
    }
}