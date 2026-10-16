<?php
include 'config.php';

/* Validate ID */
if (!isset($_GET['id'])) {
    die('Membership ID not provided.');
}

$id = (int) $_GET['id'];

/* Fetch member info */
$sql = "SELECT * FROM member WHERE membership_id = $id";
$result = $conn->query($sql);

if (!$result) {
    die('SQL Error: ' . $conn->error);
}

$member = $result->fetch_assoc();

if (!$member) {
    die('Member not found.');
}

/* Handle edit submission */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $phone = $conn->real_escape_string($_POST['phone_number']);
    $email = $conn->real_escape_string($_POST['email']);
    $type = $_POST['type'];
    $status = $_POST['status'];

    $updateSql = "
        UPDATE member
        SET member_first_name = '$first_name',
            member_last_name = '$last_name',
            member_birth_date = '$birth_date',
            member_gender = '$gender',
            member_phone_number = '$phone',
            member_email = '$email',
            membership_type = '$type',
	    membership_status = '$status'
        WHERE membership_id = $id
    ";

    if ($conn->query($updateSql)) {
        echo "<script>
            alert('Member information updated successfully!');
            window.location.href='index.php?page=members';
        </script>";
        exit;
    } else {
        echo "<script>alert('Update error: {$conn->error}');</script>";
    }
}
?>

<!-- CSS -->
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
.btn-add {
    background-color: #ff69b4;
    color: white;
    box-shadow: 0 4px 10px rgba(255,105,180,0.3);
}
.btn-add:hover {
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
</style>

<!-- Form HTML -->
<div class="form-container">
    <h2>Edit Member</h2>
    <form method="POST">
        <!-- Membership ID (disabled) -->
        <label>Membership ID</label>
        <input type="number" value="<?= $member['membership_id'] ?>" disabled class="bg-gray-100">

        <!-- First Name -->
        <label>First Name</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($member['member_first_name']) ?>" required>

        <!-- Last Name -->
        <label>Last Name</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($member['member_last_name']) ?>" required>

        <!-- Birth Date -->
        <label>Birth Date</label>
        <input type="date" name="birth_date" value="<?= $member['member_birth_date'] ?>" required>

        <!-- Gender -->
        <label>Gender</label>
        <select name="gender" required>
            <option value="M" <?= $member['member_gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="F" <?= $member['member_gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
        </select>

        <!-- Phone Number -->
        <label>Phone Number</label>
        <input type="tel" name="phone_number" value="<?= $member['member_phone_number'] ?>" required>

        <!-- Email -->
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($member['member_email']) ?>" required>

        <!-- Membership Type -->
        <label>Membership Type</label>
        <select name="type" required>
            <option value="M" <?= $member['membership_type'] == 'M' ? 'selected' : '' ?>>Monthly</option>
            <option value="Q" <?= $member['membership_type'] == 'Q' ? 'selected' : '' ?>>Quarterly</option>
            <option value="A" <?= $member['membership_type'] == 'A' ? 'selected' : '' ?>>Annual</option>
        </select>

	<!-- Membership Status -->
        <label>Membership Status</label>
        <select name="status" required>
            <option value="active" <?= $member['membership_status'] == 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $member['membership_status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            <option value="suspended" <?= $member['membership_status'] == 'suspended' ? 'selected' : '' ?>>Suspended</option>
        </select>

        <!-- Buttons -->
        <div class="button-group">
            <button type="submit" class="btn btn-add">Save Changes</button>
            <a href="index.php?page=members" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>
