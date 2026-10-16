<?php
include 'config.php';

$sql = "
SELECT 
    p.program_id,
    p.program_name,
    COUNT(e.membership_id) AS enroll_count,
    CONCAT(t.trainer_first_name, ' ', t.trainer_last_name) AS trainer_name,
    p.program_category,
    pc.program_fee
FROM program p
LEFT JOIN enrollment e ON p.program_id = e.program_id
LEFT JOIN trainer t ON p.trainer_id = t.trainer_id
LEFT JOIN prog_category pc ON p.program_category = pc.program_category
GROUP BY p.program_id, p.program_name, p.program_category, pc.program_fee, trainer_name
ORDER BY enroll_count DESC
LIMIT 5
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
.popular-card {
    background: white;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 15px 40px rgba(255,105,180,0.18);
    max-width: 1400px;
    margin: 30px auto;
}

.popular-card h3 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #9d174d;
    margin-bottom: 18px;
}

.popular-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 14px;
    overflow: hidden;
}

.popular-table thead {
    background: linear-gradient(135deg, #ffc4d0, #ffb7c5);
}

.popular-table th {
    padding: 12px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #9d174d;
    border: 1px solid #ffd9e5;
}

.popular-table td {
    padding: 12px;
    border: 1px solid #ffe0ec;
    color: #4a4a4a;
    vertical-align: middle;
}

.popular-table tbody tr {
    transition: background 0.25s ease, transform 0.15s ease;
}

.popular-table tbody tr:hover {
    background: #fff0f6;
    transform: scale(1.002);
}

.rank-badge {
    font-weight: 700;
    color: #ff5c8a;
    font-size: 1.05rem;
}


.empty-row {
    color: #9ca3af;
    padding: 16px;
}


@media (max-width: 768px) {
    .popular-card {
        padding: 16px;
    }

    .popular-table th,
    .popular-table td {
        padding: 8px;
        font-size: 0.85rem;
    }
}
</style>

<div class="popular-card">
    <h3>📈 Top 5 Most Popular Programs</h3>

    <div class="overflow-x-auto">
        <table class="popular-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Program Name</th>
                    <th>Enrolled Members</th>
                    <th>Trainer</th>
                    <th>Category</th>
                    <th>Fee (RM)</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $rank = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="rank-badge"><?php echo $rank++; ?></td>
                    <td><?php echo htmlspecialchars($row['program_name']); ?></td>
                    <td class="text-center"><?php echo $row['enroll_count']; ?></td>
                    <td><?php echo htmlspecialchars($row['trainer_name'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['program_category']); ?></td>
                    <td class="text-end"><?php echo number_format($row['program_fee'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center empty-row">
                        No program data found
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
