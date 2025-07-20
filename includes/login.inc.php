<?php
// Start secure session
require_once "config_session.inc.php";

// Connect to Database
require_once "dbh.inc.php";

// Check if POST method used to access page
if ($_SERVER['REQUEST_METHOD'] == "POST") 
{
	// Get POST data and place into variables (remove whitespaces and sanitise)
	$u = $conn->real_escape_string(trim($_POST['Username']));
	$p = $conn->real_escape_string(trim($_POST['Password']));

	// Initialise error string
	$errors = "";
	
	// Check if user filled in all the data
	if (empty($u) || empty($p)) $errors = $errors . "Form not Complete" . "<br><br>";
	
	// Check entered password length
	if (strlen($p) != 6) $errors = $errors . "Password must be 6 characters long." . "<br><br>";

	// Check for errors
    if (!empty($errors)) 
	{
		// Set error message
        $_SESSION['logmsg'] = $errors;

		// Redirect
        header("Location: ../index.php");
        die();
    }

	// Check if user exists
	$check_user_sql = "SELECT passkey FROM users WHERE Username = '$u'";
	$result = $conn->query($check_user_sql);

	// User exists, verify info
	if ($result->num_rows > 0) 
	{
		// Fetch the stored password
		$row = $result->fetch_assoc();
		$stored_password = $row["passkey"];
		
		// Check if the entered password matches the stored password
		if ($stored_password === $p) 
		{
			// Set user_id session variable for later validation
			$_SESSION['user_id'] = $u;

			// Redirect to homepage
			header("Location: ../homepage.php");
			die();
		} 
		else 
		{
			// Show sanitized message
			$_SESSION['logmsg'] = "Password is incorrect for User: '" . htmlentities(stripslashes($u)) . "'.";

			// Redirect
			header("Location: ../index.php");
			die();
		}
	} 


	else 
	{
		// Username does not exist
		$_SESSION['logmsg'] = "User does not exist.";

		// Redirect
		header("Location: ../index.php");
		die();
	}
}

// Failed Access attempt
else
{
	// Set message 
	$_SESSION['logmsg'] = "Page access Denied.";
		
	// Redirect to index
	header("Location: ../index.php");
	die(); // Stop further script execution
}

// Close connection
$conn->close();