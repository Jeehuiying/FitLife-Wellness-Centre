<?php include 'config.php'; ?>

<style>
/* Existing styles kept unchanged */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #fff0f6 0%, #ffe4f0 100%);
    min-height: 100vh;
    padding: 40px 20px;
}
.overview-card {
    background: white;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 15px 40px rgba(255,105,180,0.18);
    max-width: 1400px;
    margin: 30px auto;
}
.overview-card h4 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #9d174d;
    margin-bottom: 20px;
}
.overview-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 14px;
    overflow: hidden;
}
.overview-table thead {
    background: linear-gradient(135deg, #ffc4d0, #ffb7c5);
}
.overview-table th {
    padding: 12px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #9d174d;
    border: 1px solid #ffd9e5;
}
.overview-table td {
    padding: 12px;
    border: 1px solid #ffe0ec;
    color: #4a4a4a;
    vertical-align: middle;
}
.overview-table tbody tr:hover {
    background: #fff0f6;
}
.status-badge {
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
}
.status-active { background: #22c55e; }
.status-inactive { background: #ef4444; }
.status-suspended { background: #f59e0b; }

.btn-enroll {
    background-color: #f472b6;
    color: white;
    border-radius: 999px;
    padding: 4px 14px;
    font-size: 0.8rem;
    text-decoration: none;
}
.btn-update {
    background-color: #ec4899;
    color: white;
    border-radius: 999px;
    padding: 4px 14px;
    font-size: 0.8rem;
    text-decoration: none;
    margin-right: 6px;
}
.btn-enroll:hover,
.btn-update:hover {
    opacity: 0.9;
}
</style>

<div class="overview-card">
    <h4>🚀 Members Performance Management</h4>

    <table class="overview-table">
        <thead>
            <tr>
                <th>Membership ID</th>
                <th>Name</th>
                <th>Programs Enrolled</th>
                <th>Enroll Grade</th>
                <th>Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>

<?php
$sql = "
SELECT 
    m.membership_id,
    m.member_first_name,
    m.member_last_name,
    m.membership_status
FROM member m
";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {

    $statusClass = match(strtolower($row['membership_status'])) {
        'active' => 'status-active',
        'inactive' => 'status-inactive',
        default => 'status-suspended'
    };

    // Fetch programs + grades
    $programs_result = $conn->query("
        SELECT 
            p.program_name,
            IFNULL(e.enroll_grade, '-') AS enroll_grade
        FROM enrollment e
        JOIN program p ON e.program_id = p.program_id
        WHERE e.membership_id = {$row['membership_id']}
    ");

    $program_names = [];
    $program_grades = [];

    while ($prog = $programs_result->fetch_assoc()) {
        $program_names[]  = $prog['program_name'];
        $program_grades[] = $prog['enroll_grade'];
    }

    $program_display = $program_names
        ? implode('<br>', $program_names)
        : '<em>No programs enrolled</em>';

    $grade_display = $program_grades
        ? implode('<br>', $program_grades)
        : '-';

    echo "
    <tr>
        <td>{$row['membership_id']}</td>
        <td>{$row['member_first_name']} {$row['member_last_name']}</td>
        <td>{$program_display}</td>
        <td>{$grade_display}</td>
        <td class='text-center'>
            <span class='status-badge {$statusClass}'>
                {$row['membership_status']}
            </span>
        </td>
        <td class='text-center'>
            <a href='update_grade.php?member_id={$row['membership_id']}'
               class='btn-update'>Update Grade</a>

            <a href='enroll_program.php?member_id={$row['membership_id']}'
               class='btn-enroll'>Enroll</a>
        </td>
    </tr>";
}
?>

        </tbody>
    </table>
</div>
