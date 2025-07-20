<?php

// Start secure session
require_once "config_session.inc.php"; 

// Open Connection
require_once "dbh.inc.php";

// If user requested to unreserve a book
if ($_SERVER['REQUEST_METHOD'] == "POST") 
{
    // Check user pressed confirm (and match isbn to element)
    if (isset($_POST['unreserve_book']))
    {
        // Get the ISBN from the POST request
        $isbn = $_POST['unreserve_book'];

        // Prepare SQL query to update the "Reserved" status in the Books table
        $ReservedStatus = "UPDATE books SET Reserved = 'N' WHERE ISBN = '$isbn';";

        // Prepare SQL query to remove the reservation from table
        $RemoveFromReserve =  "DELETE FROM Reservations WHERE ISBN = '$isbn';";

        // Execute the queries
        if ($conn->query($ReservedStatus) === TRUE && $conn->query($RemoveFromReserve) === TRUE  ) 
        {
            // Set message 
            $_SESSION['message'] = "Book reservation successfully removed";
                
            // Redirect
            header("Location: ../reserveview.php");
            die(); // Stop further script execution
        } 

        // Execution error
        else 
        {
            // Set message 
            $_SESSION['message'] = "Error unreserving the book: " . $conn->error;
                
            // Redirect
            header("Location: ../reserveview.php");
            die(); // Stop further script execution
        }
    }

    // User selected cancel
    else
    {
        // Set message 
        $_SESSION['message'] = "Book unreserve Cancelled";
                
        // Redirect
        header("Location: ../reserveview.php");
        die(); // Stop further script execution
    }

}

// Redirect with error
else
{
    // Set message 
	$_SESSION['message'] = "Page access Denied.";
		
	// Redirect
	header("Location: ../reserveview.php");
	die(); // Stop further script execution
}

// Close connection
$conn->close();
