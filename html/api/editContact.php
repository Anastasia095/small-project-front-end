<?php

require_once '../db.php';

class editContact
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function updateContact()
    {
        // Get POST data
        $contactId = isset($_POST['contact_id']) ? intval($_POST['contact_id']) : 0;
        $firstName = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
        $lastName = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';

        // Validate inputs
        if ($contactId === 0 || empty($firstName) || empty($lastName) || empty($phone)) {
            echo json_encode([
                "success" => false,
                "message" => "All fields are required."
            ]);
            exit;
        }

        // SQL query to update an existing contact
        $stmt = $this->db->prepare("UPDATE Contacts SET FirstName = ?, LastName = ?, Phone = ?, Email = ? WHERE ID = ?");
        if (!$stmt) {
            echo json_encode([
                "success" => false,
                "message" => "Database error: " . $this->db->error
            ]);
            exit;
        }

        $stmt->bind_param("ssssi", $firstName, $lastName, $phone, $email, $contactId);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Contact updated successfully."
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Failed to update contact."
            ]);
        }

        $stmt->close();
        $this->db->close();
    }
}

$editContactController = new editContact($db);
$editContactController->updateContact();
