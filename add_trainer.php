<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST values
    $trainerId      = (int)$_POST['trainer_id'];
    $firstName      = $_POST['tr_first_name'] ?? '';
    $lastName       = $_POST['tr_last_name'] ?? '';
    $specialisation = $_POST['specialization'] ?? '';
    $certLevel      = $_POST['certification'] ?? '';

    if (!$trainerId || !$firstName || !$lastName) {
        die("Trainer ID, First and Last Name are required.");
    }

    $conn->begin_transaction();

    try {
        // 1) Insert into trainer table
        $stmt = $conn->prepare("INSERT INTO trainer (trainer_id, trainer_first_name, trainer_last_name) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $trainerId, $firstName, $lastName);
        $stmt->execute();

        // 2) Insert into specialisation table (if provided)
        if ($specialisation) {
            $stmt2 = $conn->prepare("INSERT INTO specialisation (trainer_id, trainer_specialisation, trainer_certificate_level) VALUES (?, ?, ?)");
            $stmt2->bind_param("iss", $trainerId, $specialisation, $certLevel);
            $stmt2->execute();
        }

        $conn->commit();
        echo "<script>
                alert('Trainer successfully added!');
                window.location.href='index.php?page=trainers';
              </script>";
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        die("Insert failed: " . htmlspecialchars($e->getMessage()));
    }
}
?>

<!-- Form Design -->
<div class="form-container">
    <h2>Add New Trainer</h2>
    <form method="POST">
        <!-- Trainer ID -->
        <label>Trainer ID</label>
        <input type="number" name="trainer_id" placeholder="Enter Trainer ID" required>

        <!-- First Name -->
        <label>First Name</label>
        <input type="text" name="tr_first_name" placeholder="Enter first name" required>

        <!-- Last Name -->
        <label>Last Name</label>
        <input type="text" name="tr_last_name" placeholder="Enter last name" required>

        <!-- Specialisation -->
        <label>Specialisation</label>
        <input type="text" name="specialization" placeholder="Enter specialisation">

        <!-- Certification Level -->
        <label>Certification Level</label>
        <input type="text" name="certification" placeholder="Enter certification level">

        <!-- Buttons -->
        <div class="button-group">
            <button type="submit" class="btn btn-update">Add Trainer</button>
            <a href="index.php?page=trainers" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<!-- Same styling as Edit Trainer -->
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
