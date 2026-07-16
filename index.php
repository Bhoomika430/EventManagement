<?php
$pageTitle = "Home";
require_once 'includes/db_connect.php';
require_once 'includes/header.php';

// Count upcoming events per type
$counts = ['Marriage' => 0, 'Birthday' => 0, 'Sportsday' => 0];
$res = mysqli_query($conn, "SELECT event_type, COUNT(*) AS c FROM events WHERE event_date >= CURDATE() GROUP BY event_type");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $counts[$row['event_type']] = (int) $row['c'];
    }
}
?>
<section class="hero">
    <h1>Event Registration Made Simple</h1>
    <p>Browse upcoming Marriage, Birthday, and Sports Day events, and register in just a few clicks.</p>
</section>

<section class="event-type-grid">
    <div class="type-card marriage">
        <div class="icon">💍</div>
        <h2>Marriage</h2>
        <p><?php echo $counts['Marriage']; ?> upcoming wedding event(s)</p>
        <a class="btn" href="events.php?type=Marriage">View Events</a>
    </div>
    <div class="type-card birthday">
        <div class="icon">🎂</div>
        <h2>Birthday</h2>
        <p><?php echo $counts['Birthday']; ?> upcoming birthday event(s)</p>
        <a class="btn" href="events.php?type=Birthday">View Events</a>
    </div>
    <div class="type-card sportsday">
        <div class="icon">🏆</div>
        <h2>Sports Day</h2>
        <p><?php echo $counts['Sportsday']; ?> upcoming sports day event(s)</p>
        <a class="btn" href="events.php?type=Sportsday">View Events</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
