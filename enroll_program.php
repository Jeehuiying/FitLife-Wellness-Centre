<?php
include 'config.php';

// Get member ID from GET
$member_id = $_GET['member_id'] ?? null;

if (!$member_id) {
    echo "No member selected.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $program_id = $_POST['program_id'];

    // Check if already enrolled
    $check = $conn->prepare("SELECT * FROM enrollment WHERE membership_id = ? AND program_id = ?");
    $check->bind_param("ii", $member_id, $program_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows == 0) {
        $insert = $conn->prepare("INSERT INTO enrollment (membership_id, program_id) VALUES (?, ?)");
        $insert->bind_param("ii", $member_id, $program_id);
        if ($insert->execute()) {
            $message = "Successfully enrolled in the program!";
        } else {
            $message = "Enrollment failed. Try again.";
        }
    } else {
        $message = "Member is already enrolled in this program.";
    }
}

// Fetch member info
$member = $conn->query("SELECT * FROM member WHERE membership_id = $member_id")->fetch_assoc();

// Fetch all programs
$programs = $conn->query("SELECT * FROM program ORDER BY program_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Enroll Program</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#fff0f6; padding:40px;}
.card { max-width:600px; margin:auto; padding:20px; border-radius:18px; box-shadow:0 10px 30px rgba(255,105,180,0.2); background:white;}
.btn-enroll { background:#6b21a8; color:white; border-radius:999px; padding:6px 18px; border:none; }
.btn-enroll:hover { opacity:0.9; }
</style>
</head>
<body>

<div class="card">
    <h3>Enroll Program for <?= htmlspecialchars($member['member_first_name'] . ' ' . $member['member_last_name']); ?></h3>
    <?php if(!empty($message)) echo "<div class='alert alert-info mt-3'>$message</div>"; ?>
    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label for="program_id" class="form-label">Select Program</label>
            <select name="program_id" id="program_id" class="form-select" required>
                <option value="">-- Choose Program --</option>
                <?php while($prog = $programs->fetch_assoc()): ?>
                    <option value="<?= $prog['program_id']; ?>"><?= htmlspecialchars($prog['program_name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-enroll">Enroll</button>
        <a href="index.php?page=member_performance" class="btn btn-secondary ms-2">Back</a>
    </form>
</div>

</body>
</html>
