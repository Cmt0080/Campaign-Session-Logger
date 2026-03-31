<?php
// This file is included at the top of every page.
// It starts the session so we can check if the user is logged in.
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Campaign Session Tool</title>
    <link rel="stylesheet" href="/campaign-logger/css/style.css">
</head>
<body>

<?php if (isset($_SESSION['username'])): ?>
<nav>
    <a href="/campaign-logger/index.php">Home</a>
    <a href="/campaign-logger/sessions.php">Session Log</a>
    <a href="/campaign-logger/npcs.php">NPC Tracker</a>
    <a href="/campaign-logger/loot.php">Loot Log</a>

    <?php if ($_SESSION['role'] === 'dm'): ?>
        <a href="/campaign-logger/dm_notes.php">DM Notes</a>
    <?php endif; ?>

    <a href="/campaign-logger/logout.php">Logout</a>
</nav>
<?php endif; ?>

<main>