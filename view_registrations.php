<?php
require_once '../includes/db_connect.php';
require_once '../includes/admin_guard.php';

$event_id = isset($_GET['event_id']) ? (int) $_GET['event_id'] : 0;

$stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE event_id = ?");
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$event = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$event) {
    header("Location: dashboard.php");
    exit;
}

$regStmt = mysqli_prepare($conn, "SELECT * FROM registrations WHERE event_id = ? ORDER BY registered_at DESC");
mysqli_stmt_bind_param($regStmt, "i", $event_id);
mysqli_stmt_execute($regStmt);
$registrations = mysqli_stmt_get_result($regStmt);

$pageTitle = "Registrations - " . $event['event_name'];
$basePath = "../";
$cssPath = "../css/style.css";
require_once '../includes/header.php';
?>

<div class="section-header">
    <div>
        <h1 class="page-title">Registrations: <?php echo htmlspecialchars($event['event_name']); ?></h1>
        <p class="page-subtitle">
            <?php echo mysqli_num_rows($registrations); ?> of <?php echo $event['max_participants']; ?> spots filled
            &nbsp;|&nbsp; 📅 <?php echo date("d M Y", strtotime($event['event_date'])); ?>
            &nbsp;|&nbsp; 📍 <?php echo htmlspecialchars($event['venue']); ?>
        </p>
    </div>
    <a class="btn outline" href="dashboard.php">&larr; Back to Dashboard</a>
</div>

<?php if (mysqli_num_rows($registrations) === 0): ?>
    <div class="empty-state"><p>No one has registered for this event yet.</p></div>
<?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Guests</th>
                    <th>Address</th>
                    <th>Notes</th>
                    <th>Registered On</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1; while ($r = mysqli_fetch_assoc($registrations)): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($r['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($r['email']); ?></td>
                    <td><?php echo htmlspecialchars($r['phone']); ?></td>
                    <td><?php echo htmlspecialchars($r['age']); ?></td>
                    <td><?php echo htmlspecialchars($r['gender']); ?></td>
                    <td><?php echo htmlspecialchars($r['guests']); ?></td>
                    <td><?php echo htmlspecialchars($r['address']); ?></td>
                    <td><?php echo htmlspecialchars($r['notes']); ?></td>
                    <td><?php echo date("d M Y, g:i A", strtotime($r['registered_at'])); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>
