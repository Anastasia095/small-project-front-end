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
        $data = json_decode(file_get_contents("php://input"), true);

        // Check for missing fields
        $requiredFields = ['id', 'fname', 'lname', 'phone', 'email'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            return $this->sendError("The following fields are missing: " . implode(", ", $missingFields));
        }


        // Sanitize
        $id = trim($data['id']);
        $fname = trim($data['fname']);
        $lname = trim($data['lname']);
        $phone = trim($data['phone']);
        $email = trim($data['email']);

        $sql = "UPDATE Contacts SET FirstName = ?, LastName = ?, Phone = ?, Email = ? WHERE ID = ?";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return $this->sendError("Database error: " . $this->db->error);
        }

        $stmt->bind_param("ssssi", $fname, $lname, $phone, $email, $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Contact updated successfully"]);
        } else {
            $this->sendError("Failed to update contact");
        }

        $stmt->close();
        $this->db->close();
    }

    private function sendError($message)
    {
        echo json_encode(["success" => false, "message" => $message]);
        exit;
    }
}

$editContactController = new editContact($db);
$editContactController->updateContact();
