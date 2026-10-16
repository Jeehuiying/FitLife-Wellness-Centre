<?php
$page = $_GET['page'] ?? 'members';
?>

<!DOCTYPE html>
<html>
<head>
  <title>FitLife Wellness Centre</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
  <h2 class="text-center">FitLife Wellness Centre</h2>

  <ul class="nav nav-tabs mt-4">
    <li class="nav-item">
      <a class="nav-link <?= $page=='members'?'active':'' ?>" href="?page=members">Members</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $page=='member_performance'?'active':'' ?>" href="?page=member_performance">Member Performance</a>
    </li>
   
    <li class="nav-item">
      <a class="nav-link <?= $page=='reports'?'active':'' ?>" href="?page=reports">Member Reports</a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?= $page=='trainers'?'active':'' ?>" href="?page=trainers">Trainers</a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?= $page=='trainer_performance'?'active':'' ?>" href="?page=trainer_performance">Trainer Performance</a>
    </li>

 <li class="nav-item">
      <a class="nav-link <?= $page=='class_schedule'?'active':'' ?>" href="?page=class_schedule">Class Schedule</a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?= $page=='top5_programs'?'active':'' ?>" href="?page=top5_programs">Top 5 Most Popular Porgrams</a>
    </li>


    <li class="nav-item">
      <a class="nav-link <?= $page=='membership_fee_summary'?'active':'' ?>" href="?page=membership_fee_summary">Membership Fees Summary</a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?= $page=='total_income'?'active':'' ?>" href="?page=total_income">Finance Report</a>
    </li>

  </ul>

  <div class="mt-4">
    <?php
      if ($page == 'members') include 'members.php';
      if ($page == 'member_performance') include 'member_performance.php';
      if ($page == 'trainers') include 'trainers.php';
      if ($page == 'class_schedule') include 'class_schedule.php';
      if ($page == 'top5_programs') include 'top5_programs.php';
      if ($page == 'reports') include 'reports.php';
      if ($page == 'trainer_performance') include 'trainer_performance.php';
      if ($page == 'membership_fee_summary') include 'membership_fee_summary.php';
      if ($page == 'total_income') include 'total income.php';
    ?>
  </div>
</div>

</body>
</html>
