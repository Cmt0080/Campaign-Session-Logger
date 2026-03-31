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
    $loot = json_decode(file_get_contents('data/loot.json'), true);

    $updated = [];
    foreach ($loot as $index => $item) {
        if ($index != $delete_index) {
            $updated[] = $item;
        }
    }

    file_put_contents('data/loot.json', json_encode($updated, JSON_PRETTY_PRINT));
    header('Location: /campaign-logger/loot.php');
    exit();
}

// Handle new loot form submission (DM only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'dm') {

    $item_name     = trim($_POST['item_name']);
    $description   = trim($_POST['description']);
    $session_found = trim($_POST['session_found']);
    $held_by       = trim($_POST['held_by']);

    if (empty($item_name) || empty($description) || empty($session_found) || empty($held_by)) {
        $error = 'Please fill in all fields.';
    } else {
        $loot = json_decode(file_get_contents('data/loot.json'), true);

        $new_item = [
            'item_name'     => $item_name,
            'description'   => $description,
            'session_found' => $session_found,
            'held_by'       => $held_by
        ];

        $loot[] = $new_item;
        file_put_contents('data/loot.json', json_encode($loot, JSON_PRETTY_PRINT));

        $success = 'Loot item added successfully!';
    }
}

// Read all loot from JSON
$loot = json_decode(file_get_contents('data/loot.json'), true);
?>

<div class="title-row">
    <h1>Loot Log</h1>
</div>

<?php if ($error) { ?>
    <div class="alert-error"><?php echo $error; ?></div>
<?php } ?>

<?php if ($success) { ?>
    <div class="alert-success"><?php echo $success; ?></div>
<?php } ?>

<!-- Loot Table -->
<?php if (count($loot) === 0) { ?>
    <p>No loot has been added yet.</p>
<?php } else { ?>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Found In</th>
                <th>Held By</th>
                <?php if ($_SESSION['role'] === 'dm') { ?>
                    <th>Actions</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($loot as $index => $item) { ?>
                <tr>
                    <td><?php echo $item['item_name']; ?></td>
                    <td><?php echo $item['description']; ?></td>
                    <td>Session <?php echo $item['session_found']; ?></td>
                    <td><?php echo $item['held_by']; ?></td>
                    <?php if ($_SESSION['role'] === 'dm') { ?>
                        <td>
                            <a href="/campaign-logger/loot.php?delete=<?php echo $index; ?>"
                               class="btn-delete"
                               onclick="return confirm('Are you sure you want to delete this item?');">
                                Delete
                            </a>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>

<!-- Add Loot Form (DM Only) -->
<?php if ($_SESSION['role'] === 'dm') { ?>
    <hr>
    <h2 id="add-loot-form">Add New Loot</h2>

    <div class="form-card">
        <form method="POST" action="">

            <div class="form-row">
                <label for="item_name">Item Name:</label>
                <input type="text" id="item_name" name="item_name">
            </div>

            <div class="form-row">
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>
            </div>

            <div class="form-row">
                <label for="session_found">Found In (Session #):</label>
                <input type="number" id="session_found" name="session_found">
            </div>

            <div class="form-row">
                <label for="held_by">Held By:</label>
                <input type="text" id="held_by" name="held_by">
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-navy">Add Loot</button>
            </div>

        </form>
    </div>
<?php } ?>

<?php include 'includes/footer.php'; ?>
