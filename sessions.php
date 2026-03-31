<?php
include 'includes/header.php';

// If not logged in, redirect to login
if (!isset($_SESSION['username'])) {
    header('Location: /campaign-logger/login.php');
    exit();
}

$error = '';
$success = '';

// Handle delete (DM only)
if (isset($_GET['delete']) && $_SESSION['role'] === 'dm') {
    $delete_id = $_GET['delete'];
    $sessions = json_decode(file_get_contents('data/sessions.json'), true);

    $updated = [];
    foreach ($sessions as $session) {
        if ($session['session_number'] != $delete_id) {
            $updated[] = $session;
        }
    }

    file_put_contents('data/sessions.json', json_encode($updated, JSON_PRETTY_PRINT));
    header('Location: /campaign-logger/sessions.php');
    exit();
}

// Handle new session form submission (DM only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'dm') {

    $session_number = trim($_POST['session_number']);
    $date           = trim($_POST['date']);
    $title          = trim($_POST['title']);
    $summary        = trim($_POST['summary']);

    if (empty($session_number) || empty($date) || empty($title) || empty($summary)) {
        $error = 'Please fill in all fields.';
    } else {
        $sessions = json_decode(file_get_contents('data/sessions.json'), true);

        $new_session = [
            'session_number' => $session_number,
            'date'           => $date,
            'title'          => $title,
            'summary'        => $summary
        ];

        $sessions[] = $new_session;
        file_put_contents('data/sessions.json', json_encode($sessions, JSON_PRETTY_PRINT));

        $success = 'Session saved successfully!';
    }
}

// Read all sessions and reverse so newest shows first
$sessions = json_decode(file_get_contents('data/sessions.json'), true);
$sessions = array_reverse($sessions);
?>

<div class="title-row">
    <h1>Session Log</h1>

</div>

<?php if ($error) { ?>
    <div class="alert-error"><?php echo $error; ?></div>
<?php } ?>

<?php if ($success) { ?>
    <div class="alert-success"><?php echo $success; ?></div>
<?php } ?>

<!-- Session List -->
<?php if (count($sessions) === 0) { ?>
    <p>No sessions logged yet.</p>
<?php } else { ?>
    <?php foreach ($sessions as $session) { ?>
        <div class="session-item">
            <div class="session-item-row">
                <a href="/campaign-logger/session_detail.php?id=<?php echo $session['session_number']; ?>">
                    Session <?php echo $session['session_number']; ?> - <?php echo $session['title']; ?>
                </a>
                <?php if ($_SESSION['role'] === 'dm') { ?>
                    <a href="/campaign-logger/sessions.php?delete=<?php echo $session['session_number']; ?>"
                       class="btn-delete"
                       onclick="return confirm('Are you sure you want to delete this session?');">
                        Delete
                    </a>
                <?php } ?>
            </div>
            <p><?php echo $session['date']; ?> | Click to view full recap</p>
        </div>
    <?php } ?>
<?php } ?>

<!-- New Session Form (DM Only) -->
<?php if ($_SESSION['role'] === 'dm') { ?>
    <hr>
    <h2 id="new-session-form">Add New Session</h2>

    <div class="form-card">
        <form method="POST" action="">

            <div class="form-row">
                <label for="session_number">Session #:</label>
                <input type="number" id="session_number" name="session_number">
            </div>

            <div class="form-row">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date">
            </div>

            <div class="form-row">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title">
            </div>

            <div class="form-row">
                <label for="summary">Summary:</label>
                <textarea id="summary" name="summary"></textarea>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-navy">Save Session</button>
            </div>

        </form>
    </div>
<?php } ?>

<?php include 'includes/footer.php'; ?>