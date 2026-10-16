<?php
include 'config.php';

// Fetch trainer performance
$sql = "
SELECT 
    t.trainer_id,
    CONCAT(t.trainer_first_name, ' ', t.trainer_last_name) AS name,
    COUNT(c.class_id) AS totalClasses,
    SUM(CASE WHEN c.class_status = 'completed' THEN 1 ELSE 0 END) AS completedClasses,
    SUM(CASE WHEN c.class_status = 'cancelled' THEN 1 ELSE 0 END) AS cancelledClasses
FROM trainer t
LEFT JOIN program p ON t.trainer_id = p.trainer_id
LEFT JOIN `class` c ON p.program_id = c.program_id
GROUP BY t.trainer_id
ORDER BY t.trainer_id ASC
";

$result = $conn->query($sql);
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #fff0f6 0%, #ffe4f0 100%);
    min-height: 100vh;
    padding: 40px 20px;
}

.trainer-card {
    background: white;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 15px 40px rgba(255,105,180,0.18);
    max-width: 1400px;
    margin: 30px auto;
}

.trainer-card h3 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #9d174d;
    margin-bottom: 18px;
}

.trainer-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 14px;
    overflow: hidden;
}

.trainer-table thead {
    background: linear-gradient(135deg, #ffc4d0, #ffb7c5);
}

.trainer-table th {
    padding: 12px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #9d174d;
    border: 1px solid #ffd9e5;
}

.trainer-table td {
    padding: 12px;
    border: 1px solid #ffe0ec;
    color: #4a4a4a;
    vertical-align: middle;
}

.trainer-table tbody tr {
    transition: background 0.25s ease, transform 0.15s ease;
}

.trainer-table tbody tr:hover {
    background: #fff0f6;
    transform: scale(1.002);
}

.stat {
    font-weight: 600;
    color: #ff5c8a;
}

.empty-row {
    color: #9ca3af;
    padding: 16px;
}

@media (max-width: 768px) {
    .trainer-card {
        padding: 16px;
    }

    .trainer-table th,
    .trainer-table td {
        padding: 8px;
        font-size: 0.85rem;
    }
}
</style>

<div class="trainer-card">
    <h3>🏆 Trainer Performance</h3>

    <div class="overflow-x-auto">
        <table class="trainer-table">
            <thead>
                <tr>
                    <th>Trainer ID</th>
                    <th>Trainer Name</th>
                    <th>Total Classes</th>
                    <th>Completed</th>
                    <th>Cancelled / Missed</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($trainer = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($trainer['trainer_id']) ?></td>
                    <td><?= htmlspecialchars($trainer['name']) ?></td>
                    <td class="stat"><?= $trainer['totalClasses'] ?></td>
                    <td class="stat"><?= $trainer['completedClasses'] ?></td>
                    <td class="stat"><?= $trainer['cancelledClasses'] ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center empty-row">
                        No trainers found
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
