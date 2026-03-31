\<?php
include 'includes/header.php';

// If not logged in, redirect to login
if (!isset($_SESSION['username'])) {
    header('Location: /campaign-logger/login.php');
    exit();
}

// If logged in but not a DM, redirect to home with access denied message
if ($_SESSION['role'] !== 'dm') {
    header('Location: /campaign-logger/index.php?error=access_denied');
    exit();
}

$success = '';

// Handle Save Notes form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_notes'])) {

    $session_number = trim($_POST['session_number']);
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

    if (!empty($session_number)) {

        // Read existing notes
        $all_notes = json_decode(file_get_contents('data/dm_notes.json'), true);

        // Save notes keyed by session number
        $all_notes[$session_number] = $notes;

        file_put_contents('data/dm_notes.json', json_encode($all_notes, JSON_PRETTY_PRINT));

        $success = 'Notes saved!';
    }
}

// Read all sessions for the dropdown
$sessions = json_decode(file_get_contents('data/sessions.json'), true);

// Read all existing notes
$all_notes = json_decode(file_get_contents('data/dm_notes.json'), true);

// Figure out which session is selected
$selected = '';
if (isset($_POST['session_number'])) {
    $selected = trim($_POST['session_number']);
}

// Load the notes for the selected session
$current_notes = '';
if ($selected !== '' && isset($all_notes[$selected])) {
    $current_notes = $all_notes[$selected];
}
?>

<h1>DM Notes</h1>

<!-- Warning banner -->
<div class="dm-warning">
    This page is only accessible to DM accounts. Players are redirected if they try to access this URL.
</div>

<?php if ($success) { ?>
    <div class="alert-success"><?php echo $success; ?></div>
<?php } ?>

<?php if (count($sessions) === 0) { ?>
    <p>No sessions have been created yet. Add a session first before writing notes.</p>
<?php } else { ?>

    <form method="POST" action="">

        <div class="form-row">
            <label for="session_number">Select Session:</label>
            <select id="session_number" name="session_number" onchange="loadNotes(this.value)">
                <option value="">-- Select a Session --</option>
                <?php foreach ($sessions as $session) { ?>
                    <option value="<?php echo $session['session_number']; ?>"
                        <?php if ($selected == $session['session_number']) { echo 'selected'; } ?>>
                        Session <?php echo $session['session_number']; ?>: <?php echo $session['title']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div id="notes-section" <?php if ($selected === '') { echo 'style="display:none;"'; } ?>>
            <div class="form-row">
                <label for="notes">Private DM Notes:</label>
                <textarea id="notes" name="notes" class="dm-textarea"><?php echo $current_notes; ?></textarea>
            </div>

            <div class="form-buttons">
                <button type="submit" name="save_notes" value="1" class="btn-navy">Save Notes</button>
            </div>
        </div>

    </form>

    <!-- All session notes passed from PHP into JavaScript so we can load them instantly -->
    <script>
        var allNotes = <?php echo json_encode($all_notes); ?>;

        function loadNotes(sessionNumber) {
            if (sessionNumber === '') {
                document.getElementById('notes-section').style.display = 'none';
                return;
            }

            // Show the notes section
            document.getElementById('notes-section').style.display = 'block';

            // Load the notes for this session, or empty string if none exist yet
            if (allNotes[sessionNumber] !== undefined) {
                document.getElementById('notes').value = allNotes[sessionNumber];
            } else {
                document.getElementById('notes').value = '';
            }
        }
    </script>

<?php } ?>

<?php include 'includes/footer.php'; ?>