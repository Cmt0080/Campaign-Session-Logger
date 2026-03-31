<?php
session_start();

// If already logged in, send them to the home page
if (isset($_SESSION['username'])) {
    header('Location: /campaign-logger/index.php');
    exit();
}

$error = '';
$success = '';

// This runs when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username       = trim($_POST['username']);
    $password       = trim($_POST['password']);
    $character_name = trim($_POST['character_name']);
    $role           = trim($_POST['role']);

    // Validation - check nothing is empty
    if (empty($username) || empty($password) || empty($character_name) || empty($role)) {
        $error = 'Please fill in all fields.';
    } else {

        // Read the existing users from users.json
        $users = json_decode(file_get_contents('data/users.json'), true);

        // Check if username is already taken
        $taken = false;
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                $taken = true;
            }
        }

        if ($taken) {
            $error = 'That username is already taken. Please choose another.';
        } else {

            // Hash the password before saving
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Build the new user as an array
            $new_user = [
                'username'       => $username,
                'password'       => $hashed_password,
                'role'           => $role,
                'character_name' => $character_name
            ];

            // Add new user to the list
            $users[] = $new_user;

            // Save the updated list back to users.json
            file_put_contents('data/users.json', json_encode($users, JSON_PRETTY_PRINT));

            $success = 'Account created! You can now log in.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Campaign Session Tool</title>
    <link rel="stylesheet" href="/campaign-logger/css/style.css">
</head>
<body>

<main>

    <h1>Create Account</h1>

    <!-- Show error or success message -->
    <?php if ($error): ?>
        <div class="alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert-success">
            <?php echo $success; ?>
            <a href="/campaign-logger/login.php">Go to Login</a>
        </div>
    <?php endif; ?>

    <!-- Signup form -->
    <div class="form-card">
        <form method="POST" action="">

            <div class="form-row">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
            </div>

            <div class="form-row">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-row">
                <label for="character_name">Character Name:</label>
                <input type="text" id="character_name" name="character_name">
            </div>

            <div class="form-row">
                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="">-- Select Role --</option>
                    <option value="dm">DM</option>
                    <option value="player">Player</option>
                </select>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-navy">Create Account</button>
                <a href="/campaign-logger/login.php" class="btn-red">Back to Login</a>
            </div>

        </form>
    </div>

</main>

<footer>
    <p>Campaign Session Tool &copy; 2026</p>
</footer>

</body>
</html>
