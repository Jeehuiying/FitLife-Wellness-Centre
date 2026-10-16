<?php 
include 'config.php';

// Query to get membership statistics and fees
$sql = "
SELECT 
    mtd.membership_type,
    mtd.membership_type_name,
    mtd.membership_fee,
    m.membership_status,
    COUNT(DISTINCT m.membership_id) AS member_count,
    (COUNT(DISTINCT m.membership_id) * mtd.membership_fee) AS total_fees
FROM membership_type_details mtd
LEFT JOIN member m ON m.membership_type = mtd.membership_type
GROUP BY mtd.membership_type, mtd.membership_type_name, mtd.membership_fee
ORDER BY 
    CASE mtd.membership_type 
        WHEN 'M' THEN 1 
        WHEN 'Q' THEN 2 
        WHEN 'A' THEN 3 
    END
";


$result = $conn->query($sql);

if (!$result) {
    die('SQL Error: ' . $conn->error);
}

$fees = [];
$grandTotal = 0;
$totalMembers = 0;

while ($row = $result->fetch_assoc()) {
    $fees[] = $row;
    $grandTotal += $row['total_fees'];
    $totalMembers += $row['member_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Membership Fee Summary</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #fff0f6, #ffe4f0);
    min-height: 100vh;
    padding: 32px 16px;
}

/* Main container card */
.dashboard-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(255, 182, 193, 0.25);
    overflow: hidden;
}

/* Header */
.dashboard-header {
    background: linear-gradient(135deg, #ffc4d0, #ffb7c5);
    padding: 20px 28px;
}

.dashboard-header h3 {
    color: #8b3a62;
    font-weight: 700;
    margin: 0;
}

/* Summary cards */
.summary-card {
    border-radius: 18px;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 8px 24px rgba(255, 182, 193, 0.25);
}

.summary-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 34px rgba(255, 105, 180, 0.35);
}

.monthly-card { background: #eef5ff; border-left: 6px solid #3b82f6; }
.quarterly-card { background: #fff7ed; border-left: 6px solid #f59e0b; }
.annual-card { background: #ecfdf5; border-left: 6px solid #22c55e; }

.summary-card h5 {
    color: #9d174d;
    font-weight: 600;
}

.summary-card hr {
    border-top: 1px dashed #f9a8d4;
}

/* Badges */
.badge {
    font-size: 0.85rem;
    padding: 6px 12px;
}

/* Grand total */
.grand-total-card {
    background: linear-gradient(135deg, #ec4899, #f472b6);
    border-radius: 22px;
    color: white;
    box-shadow: 0 16px 45px rgba(236, 72, 153, 0.45);
}

.grand-total-card h2 {
    font-weight: 700;
}

.display-4 {
    font-size: 2.4rem;
}

/* Responsive tweaks */
@media (max-width: 768px) {
    .display-4 {
        font-size: 1.8rem;
    }
}
</style>
</head>

<body>
<div class="container">
    <div class="dashboard-card">

        <!-- Header -->
        <div class="dashboard-header">
            <h3>💰 Membership Fees Summary</h3>
        </div>

        <div class="p-4">

            <!-- Membership Summary Cards -->
            <div class="row mb-5">
                <?php 
                $cardClasses = ['monthly-card', 'quarterly-card', 'annual-card'];
                $icons = ['📅', '📆', '🎯'];
                foreach ($fees as $index => $fee): 
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card summary-card <?= $cardClasses[$index]; ?>">
                        <div class="card-body">
                            <h5>
                                <?= $icons[$index]; ?>
                                <?= htmlspecialchars($fee['membership_type_name']); ?>
                            </h5>

                            <p class="mt-3 mb-2">
                                <strong>Total Members:</strong>
                                <span class="badge bg-primary"><?= $fee['member_count']; ?></span>
                            </p>

                            <p class="mb-2">
                                <strong>Fee per Member:</strong>
                                RM <?= number_format($fee['membership_fee'], 2); ?>
                            </p>

                            <hr>

                            <h4 class="mb-0 text-success">
                                RM <?= number_format($fee['total_fees'], 2); ?>
                            </h4>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Grand Total Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card grand-total-card text-center py-5">
                        <h3 class="mb-4">📊 Overall Summary</h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h5>Total Registered Members</h5>
                                <h2 class="display-4"><?= $totalMembers; ?></h2>
                            </div>
                            <div class="col-md-6">
                                <h5>Total Membership Fees</h5>
                                <h2 class="display-4">
                                    RM <?= number_format($grandTotal, 2); ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>
