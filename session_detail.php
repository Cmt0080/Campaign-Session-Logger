<?php
include 'includes/header.php';

// If not logged in, redirect to login
if (!isset($_SESSION['username'])) {
    header('Location: /campaign-logger/login.php');
    exit();
}

// Get the session number from the URL (?id=1)
$id = $_GET['id'];

// Read all sessions from JSON
$sessions = json_decode(file_get_contents('data/sessions.json'), true);

// Find the session that matches the id
$current_session = null;

foreach ($sessions as $session) {
    if ($session['session_number'] == $id) {
        $current_session = $session;
    }
}

// If no session was found, show an error
if ($current_session === null) {
    echo '<p>Session not found.</p>';
    include 'includes/footer.php';
    exit();
}
?>

<h1>Session <?php echo $current_session['session_number']; ?>: <?php echo $current_session['title']; ?></h1>
<p class="session-date"><?php echo $current_session['date']; ?></p>

<p class="session-body"><?php echo nl2br($current_session['summary']); ?></p>

<a href="/campaign-logger/sessions.php" class="back-link">&larr; Back to Session Log</a>

<?php include 'includes/footer.php'; ?>
