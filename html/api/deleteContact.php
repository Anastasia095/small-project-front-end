<?php

require_once '../db.php';

class deleteContact
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function deleteContactById($contactId)
    {
        // Ensure contact ID is provided
        if (empty($contactId)) {
            return $this->sendError("Contact ID is required");
        }

        // SQL query to delete a contact
        $sql = "DELETE FROM Contacts WHERE ID = ?";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return $this->sendError("Database error: " . $this->db->error);
        }

        $stmt->bind_param("i", $contactId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Contact deleted successfully"]);
        } else {
            $this->sendError("Failed to delete contact");
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

$deleteContactController = new deleteContact($db);
$deleteContactController->deleteContactById($_GET['id']);
