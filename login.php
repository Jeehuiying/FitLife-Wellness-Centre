<?php
ini_set('session.cookie_lifetime', 0); // session ends when browser closes
session_start();

// If already logged in, force logout to prevent auto-login
session_unset();
session_destroy();
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'fitlifeWC9988' && $password === 'fwc@0311') {
        session_unset();
        session_regenerate_id(true);

        $_SESSION['logged_in'] = true;

        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FitLife Wellness Centre Database System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #ff69b4, #ffc0cb); min-height:100vh;">

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg" style="max-width:420px; width:100%; border-radius:15px;">
        <div class="card-header text-center bg-light">
            <h4 class="mb-0">FitLife Wellness Centre</h4>
            <small class="text-muted">Database System</small>
        </div>

        <div class="card-body p-4">
            <?php if ($error): ?>
                <div class="alert alert-danger text-center"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text"
                           name="username"
                           class="form-control"
                           autocomplete="off"
                           autocorrect="off"
                           autocapitalize="off"
                           spellcheck="false"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           autocomplete="new-password"
                           required>
                </div>

                <div class="d-grid">
                    <button class="btn btn-lg text-white" style="background-color:#ff69b4;">
                        Login
                    </button>
                </div>
            </form>
        </div>

        <div class="card-footer text-center small text-muted">
            © FitLife Wellness Centre
        </div>
    </div>
</div>

</body>
</html>
