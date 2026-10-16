<?php
include 'config.php';

$trainerId = (int)($_GET['id'] ?? 0);

if ($trainerId <= 0) {
    die("Trainer ID not provided.");
}

// Fetch trainer info
$trainerRes = $conn->query("
    SELECT t.trainer_first_name, t.trainer_last_name, s.trainer_specialisation, s.trainer_certificate_level
    FROM trainer t
    LEFT JOIN specialisation s ON t.trainer_id = s.trainer_id
    WHERE t.trainer_id = $trainerId
");

if (!$trainerRes) {
    die("SQL error: " . $conn->error);
}

$trainer = $trainerRes->fetch_assoc();

if (!$trainer) {
    die("Trainer not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName      = $_POST['tr_first_name'] ?? $trainer['trainer_first_name'];
    $lastName       = $_POST['tr_last_name'] ?? $trainer['trainer_last_name'];
    $specialisation = $_POST['specialisation'] ?? $trainer['trainer_specialisation'];
    $certLevel      = $_POST['certification'] ?? $trainer['trainer_certificate_level'];

    if (!$firstName || !$lastName) die('First and Last name are required.');

    $conn->begin_transaction();

    try {
        // Update trainer
        $stmt = $conn->prepare("UPDATE trainer SET trainer_first_name=?, trainer_last_name=? WHERE trainer_id=?");
        $stmt->bind_param("ssi", $firstName, $lastName, $trainerId);
        $stmt->execute();

        // Update or insert specialisation
        $checkStmt = $conn->prepare("SELECT * FROM specialisation WHERE trainer_id=? AND trainer_specialisation=?");
        $checkStmt->bind_param("is", $trainerId, $specialisation);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $updateStmt = $conn->prepare("
                UPDATE specialisation 
                SET trainer_certificate_level=? 
                WHERE trainer_id=? AND trainer_specialisation=?
            ");
            $updateStmt->bind_param("sis", $certLevel, $trainerId, $specialisation);
            $updateStmt->execute();
        } else {
            $insertStmt = $conn->prepare("
                INSERT INTO specialisation (trainer_id, trainer_specialisation, trainer_certificate_level)
                VALUES (?, ?, ?)
            ");
            $insertStmt->bind_param("iss", $trainerId, $specialisation, $certLevel);
            $insertStmt->execute();
        }

        $conn->commit();
        echo "<script>
                alert('Trainer information has been successfully updated!');
                window.location.href='index.php?page=trainers';
              </script>";
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo "Update failed: " . htmlspecialchars($e->getMessage());
    }
}
?>

<!-- CSS for form design -->
<style>
.form-container {
    max-width: 550px;
    margin: 40px auto;
    background: #fff;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    font-family: Arial, sans-serif;
}
.form-container h2 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 24px;
    color: #9d174d;
}
.form-container label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
}
.form-container input {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 16px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
}
.button-group {
    display: flex;
    gap: 16px;
}
.btn {
    flex: 1;
    padding: 12px 0;
    border: none;
    border-radius: 999px;
    font-weight: 600;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s ease;
    text-align: center;
}
.btn-update {
    background-color: #ff69b4;
    color: white;
    box-shadow: 0 4px 10px rgba(255,105,180,0.3);
}
.btn-update:hover {
    background-color: #ec4899;
}
.btn-cancel {
    background-color: #d1d5db;
    color: #1f2937;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.btn-cancel:hover {
    background-color: #9ca3af;
}
</style>

<!-- Form HTML -->
<div class="form-container">
    <h2>Edit Trainer</h2>
    <form method="POST">
        <!-- First Name -->
        <label>First Name</label>
        <input type="text" name="tr_first_name" value="<?= htmlspecialchars($trainer['trainer_first_name']) ?>" required>

        <!-- Last Name -->
        <label>Last Name</label>
        <input type="text" name="tr_last_name" value="<?= htmlspecialchars($trainer['trainer_last_name']) ?>" required>

        <!-- Specialisation -->
        <label>Specialisation</label>
        <input type="text" name="specialisation" value="<?= htmlspecialchars($trainer['trainer_specialisation'] ?? '') ?>">

        <!-- Certification Level -->
        <label>Certification Level</label>
        <input type="text" name="certification" value="<?= htmlspecialchars($trainer['trainer_certificate_level'] ?? '') ?>">

        <!-- Buttons -->
        <div class="button-group">
            <button type="submit" class="btn btn-update">Update Trainer</button>
            <a href="index.php?page=trainers" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>
