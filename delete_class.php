<?php include 'config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid request.");
}

$sql = "DELETE FROM class WHERE class_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?page=class_schedule");
    exit;
} else {
    echo "Error deleting record.";
}

$stmt->close();
$conn->close();
?>
