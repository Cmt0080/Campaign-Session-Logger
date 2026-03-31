<?php
include 'includes/header.php';

// If not logged in, send them to the login page
if (!isset($_SESSION['username'])) {
    header('Location: /campaign-logger/login.php');
    exit();
}
?>

<!-- Campaign Banner -->
<div class="home-banner">
    Welcome to the Campaign Session Tool!
</div>

<!-- Welcome Text -->
<div class="home-welcome">
    <h1>Welcome, <?php echo $_SESSION['character_name']; ?>!</h1>
    <p>
        Your adventure continues here! Browse session recaps to catch up on the story,
        check the NPC Tracker to remember who you've met, and review the Loot Log to
        see what your party is carrying. Use the navigation above to get started.
    </p>
</div>

<?php include 'includes/footer.php'; ?>
