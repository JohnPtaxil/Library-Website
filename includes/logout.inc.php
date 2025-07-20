<?php
// Start secure session
include "config_session.inc.php";

// Unsets all session variables
$_SESSION = [];

// Destroys the session
session_destroy(); 

header("Location: ../index.php"); // Redirect to the login page
die();