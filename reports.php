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
.overview-table tbody tr {
    transition: background 0.25s ease;
}
.overview-table tbody tr:hover {
    background: #fff0f6;
}
.status-badge {
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
    color: white;
    display: inline-block;
}
.status-active { background: #22c55e; }
.status-inactive { background: #ef4444; }
.status-suspended { background: #f59e0b; }
.show-programs-btn {
    background-color: #f472b6;
    color: white;
    border-radius: 999px;
    padding: 3px 10px;
    font-size: 0.75rem;
    border: none;
    cursor: pointer;
}
.show-programs-btn:hover { opacity: 0.9; }
@media (max-width: 768px) {
    .overview-card { padding: 16px; }
    .overview-table th,
    .overview-table td { padding: 8px; font-size: 0.85rem; }
}
.modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0; top: 0; width: 100%; height: 100%;
    overflow: auto; background-color: rgba(0,0,0,0.5);
}
.modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 20px;
    border-radius: 12px;
    max-width: 500px;
}
.modal-close {
    float: right;
    font-size: 1.2rem;
    cursor: pointer;
    font-weight: bold;
    color: #f472b6;
}
</style>

<div class="overview-card">
    <h4>👥 Members Overview</h4>

    <div class="table-responsive">
        <table class="overview-table">
            <thead>
                <tr>
                    <th>Membership ID</th>
                    <th>Name</th>
                    <th>Total Programs</th>
                    <th>Total Classes</th>
                    <th>Total Payments (RM)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "
            SELECT 
                   m.membership_status,
                   m.membership_id,
                   m.member_first_name,
                   m.member_last_name,
                   COUNT(DISTINCT e.program_id) AS programs,
                   COUNT(DISTINCT CASE WHEN c.class_status = 'completed' THEN c.class_id END) AS classes,
                   IFNULL(SUM(p.payment_total_amount),0) AS total
            FROM member m
            LEFT JOIN enrollment e ON m.membership_id = e.membership_id
            LEFT JOIN class c ON e.program_id = c.program_id
            LEFT JOIN payment p ON m.membership_id = p.membership_id
            GROUP BY m.membership_id
            ";

            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {

                $statusClass = match(strtolower($row['membership_status'])) {
                    'active' => 'status-active',
                    'inactive' => 'status-inactive',
                    default => 'status-suspended'
                };

                // Prepare program names for modal
                $programs_result = $conn->query("
                    SELECT p.program_name 
                    FROM enrollment e
                    JOIN program p ON e.program_id = p.program_id
                    WHERE e.membership_id = {$row['membership_id']}
                ");
                $program_list = [];
                while($prog = $programs_result->fetch_assoc()) {
                    $program_list[] = $prog['program_name'];
                }
                $programs_str = $program_list ? implode('<br>', $program_list) : 'No programs enrolled';
                
                echo "<tr>
                    <td>{$row['membership_id']}</td>
                    <td>{$row['member_first_name']} {$row['member_last_name']}</td>
                    <td class='text-center'>
                        <span style='margin-right:8px;'>{$row['programs']}</span>
                        <button class='show-programs-btn' 
                                data-programs='".htmlspecialchars($programs_str, ENT_QUOTES)."'>
                            Show
                        </button>
                    </td>
                    <td class='text-center'>{$row['classes']}</td>
                    <td class='text-end'>".number_format($row['total'],2)."</td>
                    <td class='text-center'>
                        <span class='status-badge {$statusClass}'>
                            {$row['membership_status']}
                        </span>
                    </td>
                </tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="programModal" class="modal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <h5>Programs Enrolled</h5>
        <div id="modalBody"></div>
    </div>
</div>

<script>
const modal = document.getElementById('programModal');
const modalBody = document.getElementById('modalBody');
const closeBtn = document.querySelector('.modal-close');

document.querySelectorAll('.show-programs-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        modalBody.innerHTML = btn.dataset.programs;
        modal.style.display = 'block';
    });
});

closeBtn.addEventListener('click', () => modal.style.display = 'none');
window.addEventListener('click', e => { if(e.target == modal) modal.style.display = 'none'; });
</script>
