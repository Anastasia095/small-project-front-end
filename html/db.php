<?php
// app/db.php

$host     = "db";  // Docker service name for MariaDB
$user     = getenv("MYSQL_USER");
$password = getenv("MYSQL_PASSWORD");
$dbname   = getenv("MYSQL_DATABASE");

$db = new mysqli($host, $user, $password, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
