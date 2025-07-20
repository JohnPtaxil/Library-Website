<?php
// Start secure session
require_once "includes/config_session.inc.php"; 

// Open Connection
require_once "includes/dbh.inc.php";

if (isset($_SESSION['user_id']))
{
    include 'includes/unreservepag.inc.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-wdith, initial-scale=1.0">
    <title>KnowersArc Reservations</title>

    <link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,100,0,0&icon_names=person" />
</head>

<body>

    <!-- Header -->
	<?php include 'includes/header.inc.php'; ?>

	<?php
		// If user not logged in
		if (!isset($_SESSION['user_id'])) 
		{
			// Tell user to login
			echo '
			<div class="access-denied-container">
				<div class="access-denied-message">
					<h1>Access Denied</h1>
					<p>You must be logged in to access this page.</p>
				</div>
			</div>';

			// Show footer
			include 'includes/footer.inc.php';
			die();
		}
	?>

	<div class="main-content">
		<!-- Page contents -->
		<div class="content">

			<?php
				
				// Show all books
				echo "<div class='books-title-container'>";
				echo "<h2>Your Reservations</h2>"; 
				echo "</div>";

				// Check if there are any reserved books
				if (isset($_SESSION['user_id']) && $_SESSION['reservations'] === TRUE) 
				{
				
					// If there is data to display
					if ($result->num_rows > 0)
					{
						// Start the container for the entire book list box
						echo '<div class="book-list-box">';
						echo "<div class='book-container'>";

						// Output data of each row (limited in unreservepag.inc.php => 3 rows per page)
						while ($row = $result->fetch_assoc()) 
						{

							// Get data from books table 
							$isbn = $row["ISBN"];
							$book_search = "SELECT BookTitle, Author, Edition, YearPublished, Category FROM books WHERE ISBN = '$isbn'";
							$result2 = $conn->query($book_search);
							$book_rows = $result2->fetch_assoc();

							// Get data from categories table
							$category = $book_rows["Category"];
							$cat_search = "SELECT CategoryDescription FROM Categories WHERE CategoryID = '$category'";
							$result3 = $conn->query($cat_search);
							$cat_rows = $result3->fetch_assoc();
							

							// Display book info from selects
							echo "<div class='book-box'>";
							echo '<h3 class="book-title">' . $book_rows["BookTitle"] . '</h3>';
							echo "<p><strong>ISBN:</strong> " . $row["ISBN"] . "</p>";
							echo "<p><strong>Author:</strong> " . $book_rows["Author"] . "</p>";
							echo "<p><strong>Edition:</strong> " . $book_rows["Edition"] . "</p>";
							echo "<p><strong>Year Published:</strong> " . $book_rows["YearPublished"] . "</p>";
							echo "<p><strong>Category:</strong> " . $cat_rows["CategoryDescription"] . "</p>";
							echo "<p><strong>Date Reserved:</strong> " . $row["ReservedDate"] . "</p>";
							
							// Button to unreserve
							echo "<form method='POST' style='display:inline;'>";
							echo "<button type='submit' name='unreserve_book' class='reservation-button' value='" . $row["ISBN"] . "'>Unreserve this book</button>";
							echo "</form><br><br>";
				
							// Button to confirm choice or cancel
							if (isset($_POST['unreserve_book']) && $_POST['unreserve_book'] == $row["ISBN"])
							{
								echo "<form action='includes/unreserve.inc.php' method='POST' style='display:inline;'>";
								echo "<button type='submit' name='unreserve_book' class='confirm-button' value='" . $row["ISBN"] . "'>Confirm</button>";
								echo "<button type='submit' name='cancel' class='cancel-button' value='" . $row["ISBN"] . "'>Cancel</button>";
								echo "</form><br><br>";
							}
				
				
							echo "</div>"; // Close book box
						}

						echo "</div>"; // Close book list box
						echo "</div>"; // Close book container
					}  
				} 

				// No reservation data
				else
				{
					echo '<div class="book-list-box">';
					echo "<h2 class='msg'>You have no reservations at the moment.</h2>";
					echo '</div>';
				}

			?>

			
			<?php
			// Display success or error message if it exists in session
			if (isset($_SESSION['message']))
			{
				// Display and clear message
				echo '<div class="msg">' . $_SESSION['message'] . '</div>';
				unset($_SESSION['message']); 
			}
			?>

			<br><br>

			<?php
			// Pagination displayal
			if ($_SESSION['reservations'] === TRUE)
			{
			?>

				</div>
				<div class="page-info">
					<?php
						// Check if the page exists in the URL
						if (!isset($_GET['page-nr'])) 
						{
							// If not default to page 1
							$page = 1;
						} 
						
						else 
						{
							// If so, use it
							$page = $_GET['page-nr'];
						}
					?>

					<?php echo $nr_of_rows ?> results found <br>
					Showing page <?php echo $page ?> of <?php echo $pages ?>
				</div>

				<div class="pagination">
					<a href="?page-nr=1">First</a>

					<?php
						// If page exists in URL and its not 1 show button with link
						if (isset($_GET['page-nr']) && $_GET['page-nr'] > 1)
						{
							?>
								<a href="?page-nr= <?php echo $_GET['page-nr'] - 1 ?>" >Previous</a>
							<?php
						}
						else
						{
							?>
								<a>Previous</a>
							<?php
						}
					?>


					<div class="page-numbers">
						<?php
							// Loop to create buttons for number of pages
							for($counter = 1; $counter <= $pages; $counter++)
							{
								echo '<a href="?page-nr=' . $counter . '">' . $counter . '</a>';
							}
						?>
					</div>

					<?php
						// Check if 'page-nr' exists in the URL
						if (isset($_GET['page-nr'])) 
						{
							// If it exists, convert it to an integer and use it as the current page number
							$page = (int)$_GET['page-nr'];
						} 
						else 
						{
							// If it doesn't exist, default the page number to 1
							$page = 1;
						}

						// Display "Next" button
						if ($page < $pages) 
						{
							// Show a link to the next page
							echo '<a href="?page-nr=' . ($page + 1) . '">Next</a>';
						} 
						
						else 
						{
							// Disable the "Next" button if on the last page
							echo '<a>Next</a>';
						}
					?>


					<a href="?page-nr=<?php echo $pages ?>">Last</a>
				</div>

			<?php
			}
			?>

		</div>
	</div>

	<br><br><br>
    
	<!-- Footer -->
	<?php include 'includes/footer.inc.php'; ?>

</body>
</html>