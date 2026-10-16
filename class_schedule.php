<?php
include 'config.php';

// Fetch class schedule with trainer names
$sql = "
SELECT 
    c.class_id,
    c.class_date,
    c.class_start_time,
    c.class_end_time,
    c.class_room_num,
    c.class_status,
    p.program_name,
    p.program_category,
    t.trainer_first_name,
    t.trainer_last_name
FROM `class` c
LEFT JOIN program p ON c.program_id = p.program_id
LEFT JOIN trainer t ON p.trainer_id = t.trainer_id
ORDER BY c.class_date ASC, c.class_start_time ASC
";

$result = $conn->query($sql);

if (!$result) {
    die('SQL Error: ' . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Class Schedule</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #fff0f6 0%, #ffe4f0 100%);
    min-height: 100vh;
    padding: 40px 20px;
}

.page-card {
    background: white;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 15px 40px rgba(255,105,180,0.18);
    max-width: 1400px;
    margin: auto;
}

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.page-header h3 {
    font-size: 1.9rem;
    font-weight: 700;
    color: #9d174d;
}

.add-btn {
    background: linear-gradient(135deg, #ff69b4, #ff85c2);
    color: white;
    padding: 8px 22px;
    border-radius: 999px;
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(255,105,180,0.35);
}

.add-btn:hover {
    opacity: 0.9;
}

.table {
    border-radius: 14px;
    overflow: hidden;
}

.table thead {
    background: linear-gradient(135deg, #ffc4d0 0%, #ffb7c5 100%);
}

.table thead th {
    color: #9d174d;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table tbody tr {
    transition: background 0.25s ease;
}

.table tbody tr:hover {
    background: #fff0f6;
}

.table td {
    vertical-align: middle;
}

.status-badge {
    color: white;
    padding: 4px 14px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
    display: inline-block;
}

.action-group {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-edit {
    background-color: #f472b6;
    color: white;
    border-radius: 999px;
    padding: 5px 14px;
    font-size: 0.8rem;
}

.btn-delete {
    background-color: #fb7185;
    color: white;
    border-radius: 999px;
    padding: 5px 14px;
    font-size: 0.8rem;
}

.btn-edit:hover,
.btn-delete:hover {
    opacity: 0.9;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
}
</style>
</head>

<body>

<div class="page-card">

<div class="page-header">
    <h3>📅 Class Schedule</h3>

    <a href="add_class.php" class="add-btn">
        + Add Class
    </a>
</div>

<div class="table-responsive">
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Class ID</th>
            <th>Date</th>
            <th>Time</th>
            <th>Program</th>
            <th>Trainer</th>
            <th>Room</th>
            <th>Category</th>
            <th>Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>

    <tbody>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($cls = $result->fetch_assoc()): ?>

        <?php
            $status = $cls['class_status'];
            if ($status === 'active') {
                $bg = '#3b82f6';
            } elseif ($status === 'completed') {
                $bg = '#22c55e';
            } elseif ($status === 'cancelled') {
                $bg = '#ef4444';
            } else {
                $bg = '#9ca3af';
            }
        ?>

        <tr>
            <td><?= htmlspecialchars($cls['class_id']) ?></td>
            <td><?= date("d M Y", strtotime($cls['class_date'])) ?></td>
            <td>
                <?= htmlspecialchars($cls['class_start_time']) ?> -
                <?= htmlspecialchars($cls['class_end_time']) ?>
            </td>
            <td><?= htmlspecialchars($cls['program_name'] ?? 'N/A') ?></td>
            <td>
                <?= htmlspecialchars(trim(($cls['trainer_first_name'] ?? '') . ' ' . ($cls['trainer_last_name'] ?? ''))) ?>
            </td>
            <td><?= htmlspecialchars($cls['class_room_num']) ?></td>
            <td><?= htmlspecialchars($cls['program_category'] ?? 'N/A') ?></td>

            <td>
                <span class="status-badge" style="background-color: <?= $bg ?>;">
                    <?= htmlspecialchars($status) ?>
                </span>
            </td>

            <td class="text-center">
                <div class="action-group">
                    <a href="edit_class.php?id=<?= $cls['class_id'] ?>" class="btn-edit">
                        Edit
                    </a>

                    <a href="delete_class.php?id=<?= $cls['class_id'] ?>"
                       class="btn-delete"
                       onclick="return confirm('Are you sure you want to delete this class?')">
                        Delete
                    </a>
                </div>
            </td>
        </tr>

        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="9" class="text-center text-muted p-3">
                No classes scheduled
            </td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</div>

</div>

</body>
</html>
