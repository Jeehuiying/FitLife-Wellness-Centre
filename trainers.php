<?php
include 'config.php';

// Correct SQL: join trainer with specialisation using TRAINER_ID
$sql = "
SELECT
    t.trainer_id,
    t.trainer_first_name,
    t.trainer_last_name,
    s.trainer_specialisation,
    s.trainer_certificate_level
FROM trainer AS t
LEFT JOIN specialisation AS s ON t.trainer_id = s.trainer_id
ORDER BY t.trainer_id, t.trainer_first_name, t.trainer_last_name, s.trainer_specialisation
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
<title>Trainer Management</title>

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
    margin-bottom: 18px;
}

.page-header h3 {
    font-size: 1.9rem;
    font-weight: 700;
    color: #9d174d;
}

.add-btn {
    background: linear-gradient(135deg, #ff69b4, #ff85c2);
    color: white;
    padding: 8px 20px;
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
    letter-spacing: 0.5px;
    font-size: 0.9rem;
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

.action-group {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-update {
    background-color: #f472b6;
    color: white;
    border-radius: 999px;
    padding: 5px 14px;
    font-size: 0.85rem;
    border: none;
}

.btn-delete {
    background-color: #fb7185;
    color: white;
    border-radius: 999px;
    padding: 5px 14px;
    font-size: 0.85rem;
    border: none;
}

.btn-update:hover,
.btn-delete:hover {
    opacity: 0.9;
}

/* Responsive */
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
    <h3>🏋️ Trainer Management</h3>

    <a href="add_trainer.php" class="add-btn">
        + Add Trainer
    </a>
</div>

<div class="table-responsive">
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Trainer ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Specialisation</th>
            <th>Certification Level</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>

<?php if ($result && $result->num_rows > 0): ?>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= (int)$row['trainer_id']; ?></td>
    <td><?= htmlspecialchars($row['trainer_first_name']); ?></td>
    <td><?= htmlspecialchars($row['trainer_last_name']); ?></td>
    <td><?= htmlspecialchars($row['trainer_specialisation'] ?? '—'); ?></td>
    <td><?= htmlspecialchars($row['trainer_certificate_level'] ?? '—'); ?></td>
    <td class="text-center">
        <div class="action-group">
            <a href="update_trainer.php?id=<?= $row['trainer_id']; ?>" class="btn-update">
                Update
            </a>
            <a href="delete_trainer.php?id=<?= $row['trainer_id']; ?>"
               class="btn-delete"
               onclick="return confirm('Are you sure you want to delete this trainer?')">
                Delete
            </a>
        </div>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="6" class="text-center" style="color:#9ca3af;padding:1rem;">
        No trainer data found
    </td>
</tr>
<?php endif; ?>

    </tbody>
</table>
</div>

</div>

</body>
</html>
