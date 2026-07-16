<?php
require_once '../includes/db_connect.php';
require_once '../includes/admin_guard.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = trim($_POST['event_name'] ?? '');
    $event_type = $_POST['event_type'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $event_time = $_POST['event_time'] ?? '';
    $venue      = trim($_POST['venue'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $max_participants = trim($_POST['max_participants'] ?? '100');

    if ($event_name === '') $errors[] = "Event name is required.";
    if (!in_array($event_type, ['Marriage', 'Birthday', 'Sportsday'])) $errors[] = "Please select a valid event type.";
    if ($event_date === '') $errors[] = "Event date is required.";
    if ($venue === '') $errors[] = "Venue is required.";
    if (!ctype_digit($max_participants) || (int)$max_participants < 1) $errors[] = "Max participants must be a positive number.";

    if (empty($errors)) {
        $timeVal = $event_time !== '' ? $event_time : null;
        $stmt = mysqli_prepare($conn, "INSERT INTO events (event_name, event_type, event_date, event_time, venue, description, max_participants) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssssi", $event_name, $event_type, $event_date, $timeVal, $venue, $description, $max_participants);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: dashboard.php?added=1");
            exit;
        } else {
            $errors[] = "Failed to add event. Please try again.";
        }
    }
}

$pageTitle = "Add New Event";
$basePath = "../";
$cssPath = "../css/style.css";
require_once '../includes/header.php';
?>

<div class="section-header">
    <div>
        <h1 class="page-title">Add New Event</h1>
        <p class="page-subtitle">Create a new Marriage, Birthday, or Sports Day event.</p>
    </div>
    <a class="btn outline" href="dashboard.php">&larr; Back to Dashboard</a>
</div>

<div class="form-card">
    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <ul style="margin:0; padding-left:18px;">
                <?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="add_event.php">
        <div class="form-group">
            <label for="event_name">Event Name</label>
            <input type="text" id="event_name" name="event_name" required value="<?php echo htmlspecialchars($_POST['event_name'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="event_type">Event Type</label>
            <select id="event_type" name="event_type" required>
                <option value="">Select</option>
                <option value="Marriage">Marriage</option>
                <option value="Birthday">Birthday</option>
                <option value="Sportsday">Sports Day</option>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="event_date">Event Date</label>
                <input type="date" id="event_date" name="event_date" required value="<?php echo htmlspecialchars($_POST['event_date'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="event_time">Event Time (optional)</label>
                <input type="time" id="event_time" name="event_time" value="<?php echo htmlspecialchars($_POST['event_time'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="venue">Venue</label>
            <input type="text" id="venue" name="venue" required value="<?php echo htmlspecialchars($_POST['venue'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="max_participants">Max Participants</label>
            <input type="number" id="max_participants" name="max_participants" min="1" value="<?php echo htmlspecialchars($_POST['max_participants'] ?? '100'); ?>">
        </div>

        <button type="submit" class="btn">Save Event</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
