<?php
include 'config.php';

// Get parameters
$id = $_GET['id'] ?? null;
$page = $_GET['table'] ?? null; // the URL passes 'members' or 'enrollments', etc.

if (!$id || !$page) {
    die("Invalid request.");
}

// Map page names to actual database table names
$allowedTables = [
    'members' => 'member',
    'enrollment',
    'monthly_subscriber',
    'quarterly_subscriber',
    'annual_subscriber',
    'class',
    'payment'
];

if (!array_key_exists($page, $allowedTables)) {
    die("Unauthorized table access.");
}

$tableName = $allowedTables[$page];

// Prepare delete query
$sql = "DELETE FROM $tableName WHERE membership_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?page=$page");
    exit;
} else {
    echo "Error deleting record: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
