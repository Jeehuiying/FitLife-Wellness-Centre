<?php
include 'config.php';

$membershipSql = "
SELECT 
    m.membership_type,
    COUNT(DISTINCT m.membership_id) AS total_members,
    f.membership_fee,
    SUM(f.membership_fee) AS total_membership_income
FROM member m
JOIN membership_type_details f 
    ON m.membership_type = f.membership_type
GROUP BY m.membership_type, f.membership_fee
";

$membershipResult = $conn->query($membershipSql);
$membershipTotal = 0;
$membershipData = [];
while ($row = $membershipResult->fetch_assoc()) {
    $membershipData[] = $row;
    $membershipTotal += $row['total_membership_income'];
}

$programSql = "
SELECT 
    p.program_category,
    COUNT(DISTINCT e.membership_id, e.program_id) AS total_enrollments,
    pc.program_fee,
    SUM(pc.program_fee) AS total_program_income
FROM enrollment e
JOIN program p ON e.program_id = p.program_id
JOIN prog_category pc ON p.program_category = pc.program_category
GROUP BY p.program_category, pc.program_fee
";

$programResult = $conn->query($programSql);
$programData = [];
$programTotal = 0;
while ($row = $programResult->fetch_assoc()) {
    $programData[] = $row;
    $programTotal += $row['total_program_income'];
}

$grandTotal = $membershipTotal + $programTotal;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Total Income Report</title>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #fff0f6, #ffe4f0);
    padding: 40px 20px;
}

.report-card {
    background: white;
    border-radius: 18px;
    padding: 26px;
    box-shadow: 0 15px 40px rgba(255,105,180,0.18);
    max-width: 1200px;
    margin: auto;
}

.report-card h2 {
    color: #9d174d;
    font-weight: 700;
    margin-bottom: 24px;
}

/* Summary cards */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.summary-box {
    background: linear-gradient(135deg, #ffc4d0, #ffb7c5);
    padding: 20px;
    border-radius: 14px;
    color: #7a123f;
    box-shadow: 0 8px 20px rgba(255,105,180,0.25);
}

.summary-box h4 {
    font-size: 1rem;
}

.summary-box p {
    font-size: 1.6rem;
    font-weight: 700;
}

/* Table */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 30px;
}

thead {
    background: linear-gradient(135deg, #ffc4d0, #ffb7c5);
}

th {
    padding: 12px;
    color: #9d174d;
    font-size: 0.85rem;
    text-transform: uppercase;
    border: 1px solid #ffd9e5;
}

td {
    padding: 12px;
    border: 1px solid #ffe0ec;
}

tbody tr:hover {
    background: #fff0f6;
}

.total-row {
    font-weight: 700;
    background: #fff5f9;
}
</style>
</head>

<body>

<div class="report-card">
    <h2>💰 Total Income Report</h2>

    <!-- SUMMARY -->
    <div class="summary-grid">
        <div class="summary-box">
            <h4>Membership Fee Income</h4>
            <p>RM <?= number_format($membershipTotal, 2) ?></p>
        </div>

        <div class="summary-box">
            <h4>Program Fee Income</h4>
            <p>RM <?= number_format($programTotal, 2) ?></p>
        </div>

        <div class="summary-box">
            <h4>Grand Total Income</h4>
            <p>RM <?= number_format($grandTotal, 2) ?></p>
        </div>
    </div>

    <!-- MEMBERSHIP BREAKDOWN -->
    <h3 style="color:#9d174d;margin-bottom:10px;">📋 Membership Fee Breakdown</h3>
    <table>
        <thead>
            <tr>
                <th>Membership Type</th>
                <th>Total Members</th>
                <th>Fee per Member (RM)</th>
                <th>Total Income (RM)</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($membershipData as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['membership_type']) ?></td>
                <td><?= $row['total_members'] ?></td>
                <td><?= number_format($row['membership_fee'], 2) ?></td>
                <td><?= number_format($row['total_membership_income'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- PROGRAM BREAKDOWN -->
    <h3 style="color:#9d174d;margin-bottom:10px;">📋 Program Fee Breakdown</h3>
    <table>
        <thead>
            <tr>
                <th>Program Category</th>
                <th>Total Enrollments</th>
                <th>Fee per Program (RM)</th>
                <th>Total Program Income (RM)</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($programData as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['program_category']) ?></td>
                <td><?= $row['total_enrollments'] ?></td>
                <td><?= number_format($row['program_fee'], 2) ?></td>
                <td><?= number_format($row['total_program_income'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>

</body>
</html>
