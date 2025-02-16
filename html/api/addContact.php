<?php

require_once '../db.php';

class addContact
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createContact()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['fname']) || !isset($data['lname']) || !isset($data['phone']) || !isset($data['email'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "these fields are required"]);
            exit;
        }

        $email = trim($data['email']);
        $phone = trim($data['phone']);
        $fname = trim($data['fname']);
        $lname = trim($data['lname']);
        $userId = trim($data['userId']);

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

        // SQL query to insert a new contact (ID is auto-generated)
        $stmt = $this->db->prepare("INSERT INTO Contacts (FirstName, LastName, Phone, Email, UserID) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            echo json_encode([
                "success" => false,
                "message" => "Database error: " . $this->db->error
            ]);
            exit;
        }

        $stmt->bind_param("sssss", $fname, $lname, $phone, $email, $userId);

        if ($stmt->execute()) {
            // Get the ID of the newly inserted contact (auto-generated)
            $newContactId = $stmt->insert_id;

            echo json_encode([
                "success" => true,
                "message" => "Contact added successfully.",
                "contact_id" => $newContactId  // Return the ID of the newly created contact
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Failed to add contact."
            ]);
        }

        $stmt->close();
        $this->db->close();
    }
}

$addContactController = new addContact($db);
$addContactController->createContact();
