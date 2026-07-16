<?php
require_once 'includes/db_connect.php';

$event_id = isset($_GET['event_id']) ? (int) $_GET['event_id'] : (isset($_POST['event_id']) ? (int) $_POST['event_id'] : 0);

// Fetch the event being registered for
$stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE event_id = ?");
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$event = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$event) {
    $pageTitle = "Event Not Found";
    require_once 'includes/header.php';
    echo '<div class="empty-state"><p>Sorry, that event could not be found.</p><a class="btn" href="index.php">Back to Home</a></div>';
    require_once 'includes/footer.php';
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $age       = trim($_POST['age'] ?? '');
    $gender    = $_POST['gender'] ?? '';
    $address   = trim($_POST['address'] ?? '');
    $guests    = trim($_POST['guests'] ?? '0');
    $notes     = trim($_POST['notes'] ?? '');

    // ---- Validation ----
    if ($full_name === '') $errors[] = "Full name is required.";
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid email address is required.";
    if ($phone === '' || !preg_match('/^[0-9+\-\s]{7,15}$/', $phone)) $errors[] = "A valid phone number is required.";
    if ($age === '' || !ctype_digit($age) || (int)$age < 1 || (int)$age > 120) $errors[] = "A valid age is required.";
    if (!in_array($gender, ['Male', 'Female', 'Other'])) $errors[] = "Please select a gender.";
    if ($guests === '' || !ctype_digit($guests)) $guests = "0";

    // Check capacity
    if (empty($errors)) {
        $countStmt = mysqli_prepare($conn, "SELECT COUNT(*) AS c FROM registrations WHERE event_id = ?");
        mysqli_stmt_bind_param($countStmt, "i", $event_id);
        mysqli_stmt_execute($countStmt);
        $current = mysqli_stmt_get_result($countStmt)->fetch_assoc()['c'];
        if ($current >= $event['max_participants']) {
            $errors[] = "Sorry, this event has reached its maximum number of participants.";
        }
    }

    if (empty($errors)) {
        $insert = mysqli_prepare($conn, "INSERT INTO registrations (event_id, full_name, email, phone, age, gender, address, guests, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert, "issississ", $event_id, $full_name, $email, $phone, $age, $gender, $address, $guests, $notes);
        if (mysqli_stmt_execute($insert)) {
            header("Location: success.php?event_id=" . $event_id);
            exit;
        } else {
            $errors[] = "Something went wrong while saving your registration. Please try again.";
        }
    }
}

$pageTitle = "Register - " . $event['event_name'];
require_once 'includes/header.php';
$typeLower = strtolower($event['event_type']);
?>

<div class="section-header">
    <div>
        <h1 class="page-title">Register for <?php echo htmlspecialchars($event['event_name']); ?></h1>
        <p class="page-subtitle">
            📅 <?php echo date("d M Y", strtotime($event['event_date'])); ?>
            &nbsp;|&nbsp; 📍 <?php echo htmlspecialchars($event['venue']); ?>
        </p>
    </div>
</div>

<div class="form-card">
    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <ul style="margin:0; padding-left: 18px;">
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php?event_id=<?php echo $event_id; ?>">
        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" min="1" max="120" required value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="2"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="guests">Number of Additional Guests</label>
            <input type="number" id="guests" name="guests" min="0" value="<?php echo htmlspecialchars($_POST['guests'] ?? '0'); ?>">
        </div>

        <div class="form-group">
            <label for="notes">Special Requests / Notes (optional)</label>
            <textarea id="notes" name="notes" rows="2"><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="btn">Submit Registration</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
