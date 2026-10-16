<?php
include 'config.php';

/* Handle form submission */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $class_id        = $_POST['class_id'];
    $program_id      = $_POST['program_id'];
    $class_date      = $_POST['class_date'];
    $class_start     = $_POST['class_start_time'];
    $class_end       = $_POST['class_end_time'];
    $class_room_num  = $_POST['class_room_num'];
    $class_status    = $_POST['class_status'];

    $sql = "
        INSERT INTO class 
            (class_id, program_id, class_date, class_start_time, class_end_time, class_room_num, class_status)
        VALUES
            ('$class_id', '$program_id', '$class_date', '$class_start', '$class_end', '$class_room_num', '$class_status')
    ";

    if ($conn->query($sql)) {
        echo "<script>
            alert('New class added successfully!');
            window.location.href='index.php?page=class_schedule';
        </script>";
        exit;
    } else {
        echo "<script>alert('Error adding class: ".$conn->error."');</script>";
    }
}

/* Fetch programs dynamically */
$programs = $conn->query("SELECT program_id, program_name FROM program ORDER BY program_id ASC");
$rooms = [60, 61, 62, 63, 64, 65];
$statuses = ['active', 'completed', 'cancelled'];
?>

<!-- CSS -->
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
    .form-container input, 
    .form-container select {
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
        text-decoration: none;
        display: inline-block;
    }
    .btn-add {
        background-color: #ff69b4;
        color: white;
        box-shadow: 0 4px 10px rgba(255,105,180,0.3);
    }
    .btn-add:hover {
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
    <h2>Add New Class</h2>
    <form method="POST">
        <!-- Class ID -->
        <label>Class ID</label>
        <input type="number" name="class_id" placeholder="Enter Class ID" required>

        <!-- Program -->
        <label>Program Name</label>
        <select name="program_id" required>
            <option value="" selected disabled>Select program</option>
            <?php while ($p = $programs->fetch_assoc()): ?>
                <option value="<?= $p['program_id'] ?>"><?= htmlspecialchars($p['program_name']) ?></option>
            <?php endwhile; ?>
        </select>

        <!-- Class Date -->
        <label>Class Date</label>
        <input type="date" name="class_date" required>

        <!-- Class Time -->
        <label>Time</label>
        <div style="display:flex; gap:10px; margin-bottom:16px;">
            <input type="time" name="class_start_time" required style="flex:1;">
            <span style="align-self:center;">to</span>
            <input type="time" name="class_end_time" required style="flex:1;">
        </div>

        <!-- Room Number -->
        <label>Room Number</label>
        <select name="class_room_num" required>
            <option value="" selected disabled>Select room number</option>
            <?php foreach ($rooms as $r): ?>
                <option value="<?= $r ?>"><?= $r ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Class Status -->
        <label>Class Status</label>
        <select name="class_status" required>
            <option value="" selected disabled>Select class status</option>
            <?php foreach ($statuses as $s): ?>
                <option value="<?= $s ?>"><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Buttons -->
        <div class="button-group">
            <button type="submit" class="btn btn-add">Add Class</button>
            <a href="index.php?page=class_schedule" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>
