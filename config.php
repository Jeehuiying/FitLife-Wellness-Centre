<?php
$conn = new mysqli("localhost", "root", "", "fwc");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>