<?php
// Start secure session
require_once "config_session.inc.php";

// Open Connection
require_once "dbh.inc.php";

// Check if POST method used to access page
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
	// Set username
	$username = $_SESSION['user_id'];

	// If user pressed submit for search
	if (isset($_POST['submit']))
	{
		// Use form data (and sanitise input)
		$u = $conn->real_escape_string(trim($_POST['BookTitle']));
		$p = $conn->real_escape_string(trim($_POST['Author']));
		$f = $conn->real_escape_string(trim($_POST['Category']));

		// If form is empty
		if (empty($u) && empty($p) && empty($f))
		{
			// And data is not already taken
			if (!isset($_SESSION['bookmem']) || !isset($_SESSION['authmem']) || !isset($_SESSION['catmem']))
			{
				// Set message 
				$_SESSION['message'] = "Fill in a field";
			}
		} 
	
		// User inputted new search details, or tried to research same data
		else
		{
			// Set session variables
			$_SESSION['bookmem'] = $u;
			$_SESSION['authmem'] = $p;
			$_SESSION['catmem'] = $f;
		}

		// Redirect
		header("Location: ../booksearch.php");
		die(); // Stop further script execution
	}

	// If user pressed reset for search
	if(isset($_POST['reset']))
	{
		unset($_SESSION['bookmem']);
		unset($_SESSION['authmem']);
		unset($_SESSION['catmem']);

		// Redirect
		header("Location: ../booksearch.php");
		die(); // Stop further script execution
	}

	// User pressed reserve book
	if (isset($_POST['reserve_book']))
	{
		// Get ISBN from reserve form
		$isbn = $_POST['reserve_book'];

		// Get current page number from the POST data
		$currentPage = isset($_POST['page-nr']) ? (int)$_POST['page-nr'] : 1;

		// Prepare the SQL query to update the "Reserved" status in the Books table
		$ReservedStatus = "UPDATE books SET Reserved = 'Y' WHERE ISBN = '$isbn';";

		// Prepare the SQL query to insert to reservations table
		$addToReserve =  "INSERT INTO Reservations (Username, ISBN, ReservedDate) VALUES ('$username', '$isbn', CURDATE());";

		// If either fail, send back and error message
		if ($conn->query($ReservedStatus) === FALSE || $conn->query($addToReserve) === FALSE) 
		{
			// Set message 
			$_SESSION['message'] = "Error reserving the book: " . $conn->error;
				
			// Redirect
			header("Location: ../booksearch.php?page-nr=$currentPage");
			die(); // Stop further script execution
		}

		else
		{
			// Redirect
			header("Location: ../booksearch.php?page-nr=$currentPage");
			die(); // Stop further script execution
		}
	}
}

// User did not access page correctly
else
{
	// Set message 
	$_SESSION['message'] = "Page access Denied.";
		
	// Redirect
	header("Location: ../booksearch.php?page-nr=$currentPage");
	die(); // Stop further script execution
}

// Close connection
$conn->close();