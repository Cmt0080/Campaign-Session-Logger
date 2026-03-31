<?php
session_start();

// Delete all session data
session_destroy();

// Send the user back to the login page
header('Location: /campaign-logger/login.php');
exit();
?>
