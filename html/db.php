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
?>
