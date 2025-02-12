<?php

require_once '../db.php'; 

header("Content-Type: application/json");

//make sure errors are returned in json format
set_exception_handler(function ($e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Server error: " . $e->getMessage()
    ]);
    exit;
});


class Register {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function signup() {

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['email']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Email and password are required"]);
            exit;
        }

        $email = trim($data['email']);
        $password = trim($data['password']);
        $fname = trim($data['FirstName']);
        $lname = trim($data['LastName']);

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Invalid email format"]);
            exit;
        }

        // Check if email already used
        $stmt = $this->db->prepare("SELECT 1 FROM Users WHERE Login = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            http_response_code(409);
            echo json_encode(["success" => false, "message" => "Email is already taken"]);
            exit;
        }
        $stmt->close();

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the table
        $stmt = $this->db->prepare("INSERT INTO Users (Login, password, FirstName, LastName) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $hashedPassword, $fname, $lname); 
        
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["success" => true, "message" => "User registered successfully"]);
        } else {
            throw new Exception("Database error: " . $this->db->error);
        }
        
        $stmt->close();
        

        exit;
    }
}

$signupController = new Register($db);
$signupController->signup();
