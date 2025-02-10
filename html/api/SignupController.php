<?php

// Get database credentials from environment variables
$DB_HOST = 'db'; // Docker service name for the database (ensure the container is named 'db' in docker-compose.yml)
$DB_USER = getenv('MYSQL_USER');
$DB_PASS = getenv('MYSQL_PASSWORD');
$DB_NAME = getenv('MYSQL_DATABASE');

// Establish a connection to the database
$db = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check the connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Handle the signup logic
class SignupController {

    protected $db;

    public function __construct($db) {
        // Initialize the database connection
        $this->db = $db;
    }

    public function signup() {
        // Ensure the request is a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                "success" => false,
                "message" => "Invalid request method"
            ]);
            exit;
        }

        // Get POST data from JSON
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if email and password are provided
        if (!isset($data['email']) || !isset($data['password'])) {
            echo json_encode([
                "success" => false,
                "message" => "Email and password are required"
            ]);
            exit;
        }

        $email = $data['email'];
        $password = $data['password'];

        // Sanitize input (just in case)
        $email = $this->db->real_escape_string($email);
        $password = $this->db->real_escape_string($password);

        // Check if email already exists
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $this->db->query($query);

        if ($result->num_rows > 0) {
            echo json_encode([
                "success" => false,
                "message" => "Email is already taken"
            ]);
            exit;
        }

        // Hash the password (for security)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $insertQuery = "INSERT INTO users (email, password) VALUES ('$email', '$hashedPassword')";
        if ($this->db->query($insertQuery) === TRUE) {
            echo json_encode([
                "success" => true,
                "message" => "User registered successfully"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error: " . $this->db->error
            ]);
        }

        // Close database connection
        $this->db->close();

        exit; // Ensure no additional output is sent
    }
}

// Instantiate the controller and call the signup function
$signupController = new SignupController($db);
$signupController->signup();
