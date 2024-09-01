<?php
// Logout user and destroy the session
session_start();
session_destroy();
header("Location: ../index.php");
exit();
?>
