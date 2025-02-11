<?php

$DB_HOST = 'db'; // Docker service name for db
$DB_USER = getenv('MYSQL_USER');
$DB_PASS = getenv('MYSQL_PASSWORD');
$DB_NAME = getenv('MYSQL_DATABASE');

$db = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
