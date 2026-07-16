<?php
require_once '../includes/db_connect.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = mysqli_prepare($conn, "SELECT * FROM admin WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $admin = mysqli_stmt_get_result($stmt)->fetch_assoc();

    // NOTE: plain-text comparison used to keep this mini-project simple.
    // In a production app, store password_hash() output and use password_verify().
    if ($admin && $password === $admin['password']) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}

$pageTitle = "Admin Login";
$basePath = "../";
$cssPath = "../css/style.css";
require_once '../includes/header.php';
?>

<div class="login-wrap">
    <div class="form-card">
        <h1 class="page-title" style="text-align:center;">Admin Login</h1>
        <p class="page-subtitle" style="text-align:center;">Sign in to manage events and registrations.</p>

        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn" style="width:100%;">Login</button>
        </form>
        <p style="margin-top:14px; font-size:0.85rem; color:var(--muted); text-align:center;">
            Default: <strong>admin</strong> / <strong>admin123</strong>
        </p>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
