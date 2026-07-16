<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . " | EventReg" : "EventReg"; ?></title>
    <link rel="stylesheet" href="<?php echo isset($cssPath) ? $cssPath : 'css/style.css'; ?>">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a href="<?php echo isset($basePath) ? $basePath : ''; ?>index.php" class="logo">🎉 EventReg</a>
        <nav>
            <a href="<?php echo isset($basePath) ? $basePath : ''; ?>index.php">Home</a>
            <a href="<?php echo isset($basePath) ? $basePath : ''; ?>events.php?type=Marriage">Marriage</a>
            <a href="<?php echo isset($basePath) ? $basePath : ''; ?>events.php?type=Birthday">Birthday</a>
            <a href="<?php echo isset($basePath) ? $basePath : ''; ?>events.php?type=Sportsday">Sports Day</a>
            <?php if (!empty($_SESSION['admin_logged_in'])): ?>
                <a href="<?php echo isset($basePath) ? $basePath : ''; ?>admin/dashboard.php">Dashboard</a>
                <a href="<?php echo isset($basePath) ? $basePath : ''; ?>admin/logout.php">Logout</a>
            <?php else: ?>
                <a href="<?php echo isset($basePath) ? $basePath : ''; ?>admin/login.php">Admin Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
