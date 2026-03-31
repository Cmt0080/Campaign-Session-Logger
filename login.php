<?php
session_start();

// If already logged in, send them to the home page
if (isset($_SESSION['username'])) {
    header('Location: /campaign-logger/index.php');
    exit();
}

$error = '';

// This runs when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = 'Please fill in both fields.';
    } else {

        $users = json_decode(file_get_contents('data/users.json'), true);

        $found = false;

        foreach ($users as $user) {
            if ($user['username'] === $username) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['character_name'] = $user['character_name'];
                    $found = true;
                    header('Location: /campaign-logger/index.php');
                    exit();
                } else {
                    $error = 'Incorrect password.';
                    $found = true;
                }
            }
        }

        if (!$found) {
            $error = 'Username not found.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Campaign Session Tool</title>
    <link rel="stylesheet" href="/campaign-logger/css/style.css">
</head>
<body>

<main>

    <!-- Banner image -->
    <div class="login-banner">
        <img src="/campaign-logger/images/banner.webp" alt="Campaign Banner">
    </div>

    <!-- Error message -->
    <?php if ($error) { ?>
        <div class="alert-error"><?php echo $error; ?></div>
    <?php } ?>

    <!-- Login form -->
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

            <div class="form-buttons">
                <button type="submit" class="btn-navy">Log In</button>
                <a href="/campaign-logger/signup.php" class="btn-red">Create New Account</a>
            </div>

        </form>
    </div>

</main>

<footer>
    <p>Campaign Session Tool &copy; 2026</p>
</footer>

</body>
</html>