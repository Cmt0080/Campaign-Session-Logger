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
    $delete_index = $_GET['delete'];
    $npcs = json_decode(file_get_contents('data/npcs.json'), true);

    $updated = [];
    foreach ($npcs as $index => $npc) {
        if ($index != $delete_index) {
            $updated[] = $npc;
        }
    }

    file_put_contents('data/npcs.json', json_encode($updated, JSON_PRETTY_PRINT));
    header('Location: /campaign-logger/npcs.php');
    exit();
}

// Handle new NPC form submission (DM only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'dm') {

    $name          = trim($_POST['name']);
    $description   = trim($_POST['description']);
    $first_session = trim($_POST['first_session']);

    if (empty($name) || empty($description) || empty($first_session)) {
        $error = 'Please fill in all fields.';
    } else {
        $npcs = json_decode(file_get_contents('data/npcs.json'), true);

        $new_npc = [
            'name'          => $name,
            'description'   => $description,
            'first_session' => $first_session
        ];

        $npcs[] = $new_npc;
        file_put_contents('data/npcs.json', json_encode($npcs, JSON_PRETTY_PRINT));

        $success = 'NPC added successfully!';
    }
}

// Read all NPCs from JSON
$npcs = json_decode(file_get_contents('data/npcs.json'), true);
?>

<div class="title-row">
    <h1>NPC Tracker</h1>
</div>

<?php if ($error) { ?>
    <div class="alert-error"><?php echo $error; ?></div>
<?php } ?>

<?php if ($success) { ?>
    <div class="alert-success"><?php echo $success; ?></div>
<?php } ?>

<!-- NPC Table -->
<?php if (count($npcs) === 0) { ?>
    <p>No NPCs have been added yet.</p>
<?php } else { ?>
    <table>
        <thead>
            <tr>
                <th>NPC Name</th>
                <th>Description</th>
                <th>First Seen</th>
                <?php if ($_SESSION['role'] === 'dm') { ?>
                    <th>Actions</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($npcs as $index => $npc) { ?>
                <tr>
                    <td><?php echo $npc['name']; ?></td>
                    <td><?php echo $npc['description']; ?></td>
                    <td>Session <?php echo $npc['first_session']; ?></td>
                    <?php if ($_SESSION['role'] === 'dm') { ?>
                        <td>
                            <a href="/campaign-logger/npcs.php?delete=<?php echo $index; ?>"
                               class="btn-delete"
                               onclick="return confirm('Are you sure you want to delete this NPC?');">
                                Delete
                            </a>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>

<!-- Add NPC Form (DM Only) -->
<?php if ($_SESSION['role'] === 'dm') { ?>
    <hr>
    <h2 id="add-npc-form">Add New NPC</h2>

    <div class="form-card">
        <form method="POST" action="">

            <div class="form-row">
                <label for="name">NPC Name:</label>
                <input type="text" id="name" name="name">
            </div>

            <div class="form-row">
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>
            </div>

            <div class="form-row">
                <label for="first_session">First Seen (Session #):</label>
                <input type="number" id="first_session" name="first_session">
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-navy">Add NPC</button>
            </div>

        </form>
    </div>
<?php } ?>

<?php include 'includes/footer.php'; ?>
