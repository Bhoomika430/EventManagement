<?php
require_once 'includes/db_connect.php';

$allowedTypes = ['Marriage', 'Birthday', 'Sportsday'];
$type = isset($_GET['type']) && in_array($_GET['type'], $allowedTypes) ? $_GET['type'] : 'Marriage';
$pageTitle = "$type Events";

require_once 'includes/header.php';

$stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE event_type = ? AND event_date >= CURDATE() ORDER BY event_date ASC");
mysqli_stmt_bind_param($stmt, "s", $type);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$icons = ['Marriage' => '💍', 'Birthday' => '🎂', 'Sportsday' => '🏆'];
$labels = ['Marriage' => 'Marriage', 'Birthday' => 'Birthday', 'Sportsday' => 'Sports Day'];
?>

<div class="section-header">
    <div>
        <h1 class="page-title"><?php echo $icons[$type]; ?> <?php echo $labels[$type]; ?> Events</h1>
        <p class="page-subtitle">Choose an event below to view details and register.</p>
    </div>
</div>

<?php if (mysqli_num_rows($result) === 0): ?>
    <div class="empty-state">
        <p>No upcoming <?php echo strtolower($labels[$type]); ?> events at the moment. Please check back later.</p>
    </div>
<?php else: ?>
    <div class="event-grid">
        <?php while ($event = mysqli_fetch_assoc($result)): ?>
            <div class="event-card">
                <div class="body">
                    <span class="tag <?php echo strtolower($type); ?>"><?php echo $labels[$type]; ?></span>
                    <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                    <div class="meta">
                        📅 <?php echo date("d M Y", strtotime($event['event_date'])); ?>
                        <?php if ($event['event_time']): ?>
                            &nbsp;|&nbsp; ⏰ <?php echo date("g:i A", strtotime($event['event_time'])); ?>
                        <?php endif; ?>
                        <br>📍 <?php echo htmlspecialchars($event['venue']); ?>
                    </div>
                    <div class="desc"><?php echo nl2br(htmlspecialchars($event['description'])); ?></div>
                    <a class="btn small" href="register.php?event_id=<?php echo $event['event_id']; ?>">Register Now</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
