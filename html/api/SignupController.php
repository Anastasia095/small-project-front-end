<?php

require_once '../db.php'; 

header("Content-Type: application/json");

// Set a global exception handler to return JSON errors
set_exception_handler(function ($e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Server error: " . $e->getMessage()
    ]);
    exit;
});


// SignupController class
class SignupController {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function signup() {
        // Ensure the request is a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(["success" => false, "message" => "Invalid request method"]);
            exit;
        }

        // Get JSON data from request body
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

        // Check if email already exists using a prepared statement
        $stmt = $this->db->prepare("SELECT 1 FROM Users WHERE Login = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            http_response_code(409);
            echo json_encode(["success" => false, "message" => "Email is already taken"]);
            exit;
        }
        $stmt->close();

        // Hash the password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user using prepared statement
        $stmt = $this->db->prepare("INSERT INTO Users (Login, password, FirstName, LastName) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $hashedPassword, $fname, $lname); // Fix: "ssss" for 4 string params
        
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

// Instantiate the controller and call signup
$signupController = new SignupController($db);
$signupController->signup();
