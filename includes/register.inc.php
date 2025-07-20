<?php
// Start secure session
require_once "config_session.inc.php"; 

// Connect to database
require_once "dbh.inc.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") 
{
    // Get POST data and sanitize
    $u = $conn->real_escape_string(trim($_POST['Username']));
    $p = $conn->real_escape_string(trim($_POST['Password']));
    $v = $conn->real_escape_string(trim($_POST['ConfirmPassword']));
    $f = $conn->real_escape_string(trim($_POST['FirstName']));
    $l = $conn->real_escape_string(trim($_POST['Surname']));
    $h = $conn->real_escape_string(trim($_POST['AddressLine1']));
    $i = $conn->real_escape_string(trim($_POST['AddressLine2']));
    $c = $conn->real_escape_string(trim($_POST['City']));
    $t = $conn->real_escape_string(trim($_POST['Telephone']));
    $m = $conn->real_escape_string(trim($_POST['Mobile']));

    // Initialise error string
    $errors = "";

    // Check if form is empty
    if (empty($u) || empty($p) || empty($v) || empty($f) || empty($l) || empty($h) || empty($i) || empty($c) || empty($t) || empty($m)) 
	{
		// Set message
        $errors = $errors . "Form not complete" . "<br>";
        $_SESSION['regmsg'] = $errors;

		// Redirect
        header("Location: ../index.php");
        die();
    }

	// Username too long
    if (strlen($u) > 30) $errors = $errors . "Username cannot be longer than 30 characters." . "<br><br>";

	// Passwords do not match
    if ($p !== $v) $errors = $errors . "Passwords do not match." . "<br><br>";

	// Password wrong length
    if (strlen($p) != 6) $errors = $errors . "Password must be 6 characters long." . "<br><br>";

	// Numbers are not numeric
    if (!is_numeric($t) || !is_numeric($m)) $errors = $errors . "Phone numbers must be numeric." . "<br><br>";

	// Telephone is not 10 characters long
    if (strlen($t) != 10) $errors = $errors . "Telephone number must be 10 digits long." . "<br><br>";

    // Mobile is not 7 characters long
    if (strlen($m) != 7) $errors = $errors . "Mobile number must be 7 digits long." . "<br><br>";

    // Check for errors
    if (!empty($errors)) 
	{
		// Set error message
        $_SESSION['regmsg'] = "Registration Error: " . "<br><br>" . $errors;

		// Redirect
        header("Location: ../index.php");
        die();
    }

    // Check if username exists
    $check_sql = "SELECT * FROM users WHERE Username = '$u'";
    $result = $conn->query($check_sql);

	// Username already in use
    if ($result->num_rows > 0) 
	{
		// Set message
        $errors = $errors . "Username '" . htmlentities(stripslashes($u)) . "' already exists. Please choose a different one." . "<br>";
        $_SESSION['regmsg'] = $errors;

		// Redirect
        header("Location: ../index.php");
        die();
    }

    // If no errors, insert the new user
    if (empty($errors)) 
	{
        // Insert user to database
        $sql = "INSERT INTO users (Username, Passkey, FirstName, Surname, AddressLine1, AddressLine2, City, Telephone, Mobile) 
                VALUES ('$u', '$p', '$f', '$l', '$h', '$i', '$c', '$t', '$m')";

		// Check if query succeeded
        if ($conn->query($sql) === TRUE) 
		{
            // Show sanitized message
            $_SESSION['regmsg'] = "Successfully Registered User: " . htmlentities(stripslashes($u));

			// Redirect
            header("Location: ../index.php");
            die();
        } 
		
		// Query failed
		else 
		{
			// Set error message
            $_SESSION['regmsg'] = "Error: " . $conn->error;


			// Redirect
			header("Location: ../index.php");
			die();
		}
    }
}

// Failed Access attempt
else
{
	// Set message 
	$_SESSION['regmsg'] = "Page access Denied.";
		
	// Redirect to index
	header("Location: ../index.php");
	die(); // Stop further script execution
}

// Close the database connection
$conn->close();
?>