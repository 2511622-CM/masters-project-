<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//variables to hold databse login info
$dbhost = getenv('DB_SERVER_HOST') ?:'localhost';
$dbuser = getenv('DB_USERNAME') ?: 'your_local_user';
$dbpass = getenv('DB_PASSWORD') ?: 'your_local_password';
$dbname = getenv('DB_NAME') ?:'pixel_pantry';

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

//Attempt to connect to database
if ($conn->connect_error) {
    die("Failed to connect!" . $conn->connect_error);
}

?>