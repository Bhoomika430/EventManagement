<?php
require_once '../includes/db_connect.php';
require_once '../includes/admin_guard.php';

// Stats
$totalEvents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM events"))['c'];
$totalRegs   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM registrations"))['c'];
$upcoming    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM events WHERE event_date >= CURDATE()"))['c'];

$deleted = isset($_GET['deleted']);
$added = isset($_GET['added']);

$pageTitle = "Admin Dashboard";
$basePath = "../";
$cssPath = "../css/style.css";
require_once '../includes/header.php';

$events = mysqli_query($conn, "
    SELECT e.*, (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.event_id) AS reg_count
    FROM events e
    ORDER BY e.event_date ASC
");
?>

<div class="section-header">
    <div>
        <h1 class="page-title">Admin Dashboard</h1>
        <p class="page-subtitle">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>. Manage events and view registrations below.</p>
    </div>
    <a class="btn" href="add_event.php">+ Add New Event</a>
</div>

<?php if ($deleted): ?><div class="alert success">Event deleted successfully.</div><?php endif; ?>
<?php if ($added): ?><div class="alert success">Event added successfully.</div><?php endif; ?>

<div class="stats-grid">
    <div class="stat-card"><div class="num"><?php echo $totalEvents; ?></div><div class="label">Total Events</div></div>
    <div class="stat-card"><div class="num"><?php echo $upcoming; ?></div><div class="label">Upcoming Events</div></div>
    <div class="stat-card"><div class="num"><?php echo $totalRegs; ?></div><div class="label">Total Registrations</div></div>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Type</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Registrations</th>
                <th>Capacity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($e = mysqli_fetch_assoc($events)): ?>
            <tr>
                <td><?php echo htmlspecialchars($e['event_name']); ?></td>
                <td><span class="tag <?php echo strtolower($e['event_type']); ?>"><?php echo htmlspecialchars($e['event_type']); ?></span></td>
                <td><?php echo date("d M Y", strtotime($e['event_date'])); ?></td>
                <td><?php echo htmlspecialchars($e['venue']); ?></td>
                <td><?php echo $e['reg_count']; ?></td>
                <td><?php echo $e['max_participants']; ?></td>
                <td>
                    <a class="btn small" href="view_registrations.php?event_id=<?php echo $e['event_id']; ?>">View</a>
                    <a class="btn small danger" href="delete_event.php?event_id=<?php echo $e['event_id']; ?>" onclick="return confirm('Delete this event and all its registrations?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>
