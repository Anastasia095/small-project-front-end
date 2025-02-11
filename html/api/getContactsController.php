<?php

require_once '../db.php'; 

class GetContactsController {
    protected $db;

    public function __construct($db) { 
        $this->db = $db;
    }

    public function getContacts() {
        // Get user_id from request
        $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

        if ($user_id === 0) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid user ID"
            ]);
            exit;
        }

        // SQL query
        $stmt = $this->db->prepare("SELECT ID, FirstName, LastName, Phone, Email FROM Contacts WHERE UserID = ?");
        if (!$stmt) {
            echo json_encode([
                "success" => false,
                "message" => "Database error: " . $this->db->error
            ]);
            exit;
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $contacts = [];
        while ($row = $result->fetch_assoc()) {
            $contacts[] = [
                "id" => $row["ID"],
                "name" => $row["FirstName"] . " " . $row["LastName"],
                "number" => $row["Phone"],
                "email" => $row["Email"]
            ];
        }

        echo json_encode([
            "success" => true,
            "contacts" => $contacts
        ]);

        $stmt->close();
        $this->db->close();
    }
}


$getContactsController = new GetContactsController($db);
$getContactsController->getContacts();

?>
