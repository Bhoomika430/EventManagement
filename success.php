<?php
require_once 'includes/db_connect.php';
$pageTitle = "Registration Successful";
require_once 'includes/header.php';

$event_id = isset($_GET['event_id']) ? (int) $_GET['event_id'] : 0;
$stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE event_id = ?");
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$event = mysqli_stmt_get_result($stmt)->fetch_assoc();
?>

<div class="form-card" style="text-align:center;">
    <div style="font-size:3rem; margin-bottom:10px;">✅</div>
    <h1 class="page-title">You're Registered!</h1>
    <p class="page-subtitle">
        Thank you for registering<?php if ($event) echo ' for <strong>' . htmlspecialchars($event['event_name']) . '</strong>'; ?>.
        A confirmation has been recorded. We look forward to seeing you there!
    </p>
    <a class="btn" href="index.php">Back to Home</a>
</div>

<?php require_once 'includes/footer.php'; ?>
