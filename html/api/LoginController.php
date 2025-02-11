<?php

require_once '../db.php'; 

// Handle the login logic
class LoginController {
    protected $db;

    public function __construct($db) { 
        $this->db = $db;
    }

    public function login() {
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

        // Sanitize input
        $email = $this->db->real_escape_string($email);

        // Prepare the query to retrieve the user from the database
        $stmt = $this->db->prepare("SELECT id, password FROM  Users WHERE Login = ?");
        if (!$stmt) {
            echo json_encode([
                "success" => false,
                "message" => "Database error: " . $this->db->error
            ]);
            exit;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Bind the results
            $stmt->bind_result($userId, $hashedPassword);
            $stmt->fetch();

            // Verify the provided password
            if (password_verify($password, $hashedPassword)) {
                echo json_encode([
                    "success" => true,
                    "message" => "Login successful",
                    "userId" => $userId
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid password"
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "User not found"
            ]);
        }

        // Close the statement and database connection
        $stmt->close();
        $this->db->close();

        exit; // Ensure no additional output is sent
    }
}

// Instantiate the controller and call the login function
$loginController = new LoginController($db);
$loginController->login();
?>
