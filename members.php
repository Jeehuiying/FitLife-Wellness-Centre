<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Member Management</title>

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

/* Buttons styling */
.btn-update, .btn-delete, .btn-enroll {
    border-radius: 999px;
    padding: 6px 14px;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: white;
}

.btn-update { background-color: #f472b6; }
.btn-delete { background-color: #fb7185; }
.btn-enroll { background-color: #6b21a8; }

.btn-update:hover, .btn-delete:hover, .btn-enroll:hover {
    opacity: 0.9;
}

/* Align all three buttons in the same row */
.action-group {
    display: flex;
    gap: 6px;
    justify-content: center;
    align-items: center;
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
    <h3>🔋 Member Management</h3>
    <a href="add_member.php" class="add-btn">
        + Add Member
    </a>
</div>

<div class="table-responsive">
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Membership ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Birth Date</th>
            <th>Gender</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Member Type</th>
            <th>Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
<?php
$result = $conn->query("SELECT * FROM member");
while ($row = $result->fetch_assoc()):
?>
<tr>
    <td><?= $row['membership_id']; ?></td>
    <td><?= $row['member_first_name']; ?></td>
    <td><?= $row['member_last_name']; ?></td>
    <td><?= $row['member_birth_date']; ?></td>
    <td><?= $row['member_gender']; ?></td>
    <td><?= $row['member_phone_number']; ?></td>
    <td><?= $row['member_email']; ?></td>
    <td><?= $row['membership_type']; ?></td>
    <td><?= $row['membership_status']; ?></td>
    <td class="text-center">
        <div class="action-group">
            <!-- Update Button -->
            <a href="update_member.php?table=member&id=<?= $row['membership_id']; ?>"
               class="btn-update">
                Update
            </a>

            <!-- Delete Button -->
            <a href="delete_member.php?table=member&id=<?= $row['membership_id']; ?>"
               class="btn-delete"
               onclick="return confirm('Are you sure you want to delete this member?')">
                Delete
            </a>
        </div>
    </td>
</tr>
<?php endwhile; ?>
    </tbody>
</table>
</div>

</div>

</body>
</html>
