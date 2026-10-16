<?php 
include 'config.php';

// Get trainer ID from GET
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    die("Invalid trainer ID.");
}

// Delete trainer
$sql = "DELETE FROM trainer WHERE trainer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?page=trainers");
    exit;
} else {
    echo "Error deleting record: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
