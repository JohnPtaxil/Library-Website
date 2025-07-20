<?php
// Start secure session
require_once "includes/config_session.inc.php"; 

// Show pagination if data for search is set
if (isset($_SESSION['bookmem']) && isset($_SESSION['authmem']) && isset($_SESSION['catmem']))
{
	include 'includes/reservepag.inc.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-wdith, initial-scale=1.0">
    <title>KnowersArc Book Search</title>

    <link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,100,0,0&icon_names=person" />
</head>
<body>
	<!-- Header  -->
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
		<br>
		<!-- Input form -->
		<form method="POST" action="includes/reserve.inc.php" class="reservation-form">
			<!-- Title -->
			<div class="search-title-box">
				<h2 class="search-title">Search for a Book</h2>
			</div>
			


			<!-- Book title -->
			<div class="form-group">
				<label class="form-label">Book Title:</label>
				<input type="text" id="booktitle" name="BookTitle" placeholder="My Ranch in Texas" class="form-input" <?php if (isset($_SESSION['bookmem'])) echo "value=" . htmlspecialchars(stripslashes($_SESSION['bookmem'])); ?>>
			</div>
			
			<br>

			<!-- Author name -->
			<div class="form-group">
				<label class="form-label">Author:</label>
				<input type="text" id="author" name="Author" placeholder="George Bush" class="form-input" <?php if (isset($_SESSION['authmem'])) echo "value=" . htmlspecialchars(stripslashes($_SESSION['authmem'])); ?>>
			</div>
			
			<br>

			<!-- Genre Selector -->
			<?php
			// Open Connection
			include "includes/dbh.inc.php";

			// Gather DB categories
			$sql = "SELECT * FROM categories";
			$categoriesresult = $conn->query($sql);
		
			// If category search failed
			if ($categoriesresult === false) 
			{
				echo "Error: " . $conn->error;
			}
			?>

			<div class="form-group">
				<label class="form-label">Choose a Category:</label>    
				<select id="categories" name="Category" class="form-select">
					<option value="">-- Please Select --</option>
					<?php

					//category dropdown
					if ($categoriesresult->num_rows > 0) 
					{
						// Get each category and make an option
						while ($row = $categoriesresult->fetch_assoc()) 
						{
							$selected = ($category == $row['CategoryID']) ? 'selected' : '';
							echo "<option value='" . htmlentities($row['CategoryID']) . "' $selected>" . htmlentities($row['CategoryDescription']) . "</option>";
						}
					}
					?>
				</select> 
			</div>
			
			<br>

			<!-- Submit Buttons -->
			<div class="form-actions">
				<input type="submit" class="search-buttons" name="submit" value="Submit" class="form-button">
				<input type="submit" class="search-buttons" name="reset" value="Reset" class="form-button">
			</div>
		</form>
		<?php
        // Display success or error message if it exists in session
        if (isset($_SESSION['message']))
        {
            // Display and clear message
            echo '<div class="msg">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']); 
        }
        ?>


		<!-- Search Results -->
		<div class="content">
			<?php
				// Check if user submitted data
				if (isset($_SESSION['bookmem']) && isset($_SESSION['authmem']) && isset($_SESSION['catmem'])) 
				{
					// Fetch and display the book data
					if ($result->num_rows > 0) 
					{
						// Start the container for the entire book list box
						echo '<div class="book-list-box">'; // Background

						// Book Search Title
						echo '<div class="book-search-box">'; // Box for title
						echo '<h2 class="books-heading">All Available Books</h2>';
						echo '</div>';

						// Start the container for the book list
						echo "<div class='book-container'>"; // Show 3 book boxes in a row

						// Iterate over each row of results
						while ($row = $result->fetch_assoc()) 
						{
							// Start individual book card
							echo '<div class="book-card">'; // Each book card
							echo '<div class="book-info">'; // Card and button container
							echo '<p><strong>ISBN:</strong> ' . $row["ISBN"] . '</p>';
							echo '<p><strong>Title:</strong> ' . $row["BookTitle"] . '</p>';
							echo '<p><strong>Author:</strong> ' . $row["Author"] . '</p>';
							echo '<p><strong>Edition:</strong> ' . $row["Edition"] . '</p>';
							echo '<p><strong>Year Published:</strong> ' . $row["YearPublished"] . '</p>';
							echo '<p><strong>Category:</strong> ' . $row["CategoryName"] . '</p>';
							echo '</div>'; // Close book-info

							// Start action button area
							echo '<form action="includes/reserve.inc.php" method="POST">';

							// Add a hidden field for the current page (if it exists)
							if (isset($page)) {
								echo '<input type="hidden" name="page-nr" value="' . $page + 1 . '">';
							}

							// Check if the book is available or reserved
							if ($row["Reserved"] == 'N') {
								// Book Available, show reserve button
								echo '<button type="submit" name="reserve_book" value="' . $row["ISBN"] . '" class="reserve-button">Reserve Book</button>';
							} 
							
							else 
							{
								// Book unavailable
								echo '<p class="unavailable">Unavailable</p>';
							}

							echo '</form>'; // End of reserve form
							echo '</div>'; // Close book-card
						}
						echo '</div>'; // Close books-container
						echo '</div>'; // Close book-list-box
					} 
					else 
					{
						// No matching books found
						echo "<p>No books match your search criteria.</p>";
					}
				}			
			
			?>

			<br><br>

			<?php
			// Check if user searched something
			if (isset($_SESSION['bookmem']) && isset($_SESSION['authmem']) && isset($_SESSION['catmem']))
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

					<div class="page-info">
						<?php
						// Results and page number
						echo "<span>" . $nr_of_rows . " Results Found</span><br>";
						echo "<span>Showing Page " . $page . " of " . $pages . "</span>";
						?>
					</div>
				</div>

				<!-- Pagination of results (3 results per page) -->
				<div class="pagination">
					<!-- First button -->
					<a href="?page-nr=1">First</a>

					<!-- Previous button -->
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

					<!-- Page buttons -->
					<div class="page-numbers">
						<?php
							// Loop to create buttons for number of pages
							for($counter = 1; $counter <= $pages; $counter++)
							{
								echo '<a href="?page-nr=' . $counter . '">' . $counter . '</a>';
							}
						?>
					</div>

					<!-- Next button -->
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

					<!-- Last button -->
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

<?php 
	// Close Connection 
	$conn->close();
?>

