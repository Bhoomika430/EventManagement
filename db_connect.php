<?php
/**
 * Database connection
 * Update these four values to match your MySQL/XAMPP/WAMP setup.
 */
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "event_registration_db";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
