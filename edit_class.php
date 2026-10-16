<?php
include 'config.php';

/* Validate ID */
if (!isset($_GET['id'])) {
    die('Class ID not provided.');
}

$id = (int) $_GET['id'];
$rooms = [60, 61, 62, 63, 64, 65];
$statuses = ['active', 'completed', 'cancelled'];

/* Fetch class info using LEFT JOINs */
$sql = "
    SELECT c.*, p.program_name, t.trainer_first_name, t.trainer_last_name
    FROM class c
    LEFT JOIN program p ON c.program_id = p.program_id
    LEFT JOIN trainer t ON p.trainer_id = t.trainer_id
    WHERE c.class_id = $id
";

$result = $conn->query($sql);

if (!$result) {
    die('SQL Error (SELECT): ' . $conn->error);
}

$class = $result->fetch_assoc();

if (!$class) {
    die('Class not found.');
}

/* Handle edit submission */
if (isset($_POST['edit_class'])) {

    $date   = $_POST['class_date'];
    $start  = $_POST['start_time'];
    $end    = $_POST['end_time'];
    $room   = $_POST['room'];
    $status = $_POST['status'];

    /* Check room availability */
    $checkSql = "
        SELECT 1 FROM class
        WHERE class_id != $id
        AND class_date = '$date'
        AND class_room_num = '$room'
        AND (
            ('$start' BETWEEN class_start_time AND class_end_time)
            OR ('$end' BETWEEN class_start_time AND class_end_time)
            OR (class_start_time BETWEEN '$start' AND '$end')
            OR (class_end_time BETWEEN '$start' AND '$end')
        )
    ";

    $check = $conn->query($checkSql);

    if (!$check) {
        die('SQL Error (CHECK): ' . $conn->error);
    }

    if ($check->num_rows == 0) {

        $updateSql = "
            UPDATE class
            SET class_date = '$date',
                class_start_time = '$start',
                class_end_time = '$end',
                class_room_num = '$room',
                class_status = '$status'
            WHERE class_id = $id
        ";

        if ($conn->query($updateSql)) {
            echo "<script>
                alert('Class information has been successfully edited!');
                window.location.href='index.php?page=class_schedule';
            </script>";
            exit;
        } else {
            echo "<script>alert('Update error: {$conn->error}');</script>";
        }

    } else {
        echo "<script>alert('Room $room is not available at this time.');</script>";
    }
}
?>

<!-- HTML Form -->
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
    .form-container input[disabled] {
        background-color: #f3f3f3;
        color: #555;
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
    .btn-save {
        background-color: #ff69b4;
        color: white;
        box-shadow: 0 4px 10px rgba(255,105,180,0.3);
    }
    .btn-save:hover {
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
    .time-row {
        display: flex;
        gap: 10px;
        align-items: center;
    }
</style>

<div class="form-container">
    <h2>Edit Class</h2>

    <form method="POST">
        <!-- Program -->
        <label>Program</label>
        <input type="text" value="<?= htmlspecialchars($class['program_name'] ?? 'N/A') ?>" disabled>

        <!-- Trainer -->
        <label>Trainer</label>
        <input type="text" value="<?= htmlspecialchars(trim(($class['trainer_first_name'] ?? '') . ' ' . ($class['trainer_last_name'] ?? 'N/A'))) ?>" disabled>

        <!-- Date -->
        <label>Date</label>
        <input type="date" name="class_date" value="<?= $class['class_date'] ?>" required>

        <!-- Time -->
        <label>Time</label>
        <div class="time-row">
            <input type="time" name="start_time" value="<?= $class['class_start_time'] ?>" required>
            <span>to</span>
            <input type="time" name="end_time" value="<?= $class['class_end_time'] ?>" required>
        </div>

        <!-- Room -->
        <label>Room</label>
        <select name="room" required>
            <?php foreach ($rooms as $r): ?>
                <option value="<?= $r ?>" <?= $class['class_room_num'] == $r ? 'selected' : '' ?>><?= $r ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Status -->
        <label>Status</label>
        <select name="status" required>
            <?php foreach ($statuses as $s): ?>
                <option value="<?= $s ?>" <?= $class['class_status'] == $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Buttons -->
        <div class="button-group">
            <button type="submit" name="edit_class" class="btn btn-save">Save Changes</button>
            <a href="index.php?page=class_schedule" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>
