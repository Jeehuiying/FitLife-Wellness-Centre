<?php
include 'config.php';

$member_id = $_GET['member_id'] ?? null;
if (!$member_id) die("Invalid member.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $program_id = $_POST['program_id'];
    $grade = $_POST['enroll_grade'];

    $stmt = $conn->prepare("
        UPDATE enrollment
        SET enroll_grade = ?
        WHERE membership_id = ? AND program_id = ?
    ");
    $stmt->bind_param("iii", $grade, $member_id, $program_id);
    $stmt->execute();

    header("Location: index.php?page=member_performance");
    exit;
}

// Fetch enrolled programs
$programs = $conn->query("
    SELECT p.program_id, p.program_name, e.enroll_grade
    FROM enrollment e
    JOIN program p ON e.program_id = p.program_id
    WHERE e.membership_id = $member_id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Update Enroll Grade</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma;
    background: linear-gradient(135deg, #fff0f6, #ffe4f0);
    padding: 40px;
}
.form-card {
    background: white;
    padding: 24px;
    border-radius: 18px;
    max-width: 500px;
    margin: auto;
    box-shadow: 0 15px 40px rgba(255,105,180,0.18);
}
.form-card h3 {
    color: #9d174d;
    margin-bottom: 20px;
}
select, input {
    width: 100%;
    padding: 10px;
    margin-bottom: 14px;
    border-radius: 8px;
    border: 1px solid #ffd9e5;
}
button {
    background: #f472b6;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 999px;
    width: 100%;
    font-weight: 600;
}
button:hover { opacity: 0.9; }
</style>
</head>

<body>

<div class="form-card">
    <h3>✏️ Update Enroll Grade</h3>

    <form method="POST">
        <label>Program</label>
        <select name="program_id" required>
            <?php while ($p = $programs->fetch_assoc()): ?>
                <option value="<?= $p['program_id'] ?>">
                    <?= $p['program_name'] ?> (Current: <?= $p['enroll_grade'] ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <label>New Grade</label>
        <input type="number" name="enroll_grade" min="0" max="100" required>

        <button type="submit">Update Grade</button>
    </form>
</div>

</body>
</html>
