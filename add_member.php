<?php
include 'config.php';

/* Handle form submission */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $membership_id = (int)$_POST['membership_id'];
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $phone = $conn->real_escape_string($_POST['phone_number']);
    $email = $conn->real_escape_string($_POST['email']);
    $type = $_POST['type'];

    $sql = "
        INSERT INTO member 
            (membership_id, member_first_name, member_last_name, member_birth_date, member_gender, member_phone_number, member_email, membership_type, membership_status)
        VALUES
            ('$membership_id', '$first_name', '$last_name', '$birth_date', '$gender', '$phone', '$email', '$type', 'active')
    ";

    if ($conn->query($sql)) {
        echo "<script>
            alert('Member added successfully!');
            window.location.href='index.php?page=members';
        </script>";
        exit;
    } else {
        echo "<script>alert('Error adding member: ".$conn->error."');</script>";
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
    <h2>Add New Member</h2>
    <form method="POST">
        <!-- Membership ID -->
        <label>Membership ID</label>
        <input type="number" name="membership_id" placeholder="Enter Membership ID" required>

        <!-- First Name -->
        <label>First Name</label>
        <input type="text" name="first_name" placeholder="Enter First Name" required>

        <!-- Last Name -->
        <label>Last Name</label>
        <input type="text" name="last_name" placeholder="Enter Last Name" required>

        <!-- Birth Date -->
        <label>Birth Date</label>
        <input type="date" name="birth_date" required>

        <!-- Gender -->
        <label>Gender</label>
        <select name="gender" required>
            <option value="" selected disabled>Select gender</option>
            <option value="M">Male</option>
            <option value="F">Female</option>
        </select>

        <!-- Phone Number -->
        <label>Phone Number</label>
        <input type="tel" name="phone_number" placeholder="e.g. 60123456789" required>

        <!-- Email -->
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter Email" required>

        <!-- Membership Type -->
        <label>Membership Type</label>
        <select name="type" required>
            <option value="" selected disabled>Select membership type</option>
            <option value="M">Monthly</option>
            <option value="Q">Quarterly</option>
            <option value="A">Annual</option>
        </select>

        <!-- Buttons -->
        <div class="button-group">
            <button type="submit" class="btn btn-add">Add Member</button>
            <a href="index.php?page=members" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>
