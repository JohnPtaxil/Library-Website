<?php
// Start secure session
require_once "includes/config_session.inc.php"; 
?>

<!DOCTYPE html>
<html lang="en">
	
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-wdith, initial-scale=1.0">
    <title>KnowersArc HomePage</title>

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
		<?php
		// Show all books
		echo "<div class='books-title-container'>";
		echo "<h2> All Available Books </h2>";
		echo "</div>";

		// Open Connection
		require_once "includes/dbh.inc.php";

		$sql = "SELECT books.*, categories.CategoryDescription as CategoryName
				FROM books JOIN Categories ON books.Category = categories.CategoryID";

		$result = $conn->query($sql);

		// If search succeeds, books will be shown
		if ($result->num_rows > 0) 
		{
			// Start the container for the entire book list box
			echo '<div class="book-list-box">';
			echo "<div class='book-container'>";

			// Get each books info
			while ($row = $result->fetch_assoc()) 
			{
				echo "<form class='book-card' action='includes/reserve.inc.php' method='POST'>";
				echo "<div class='book-details'>";
				echo "<p><strong>ISBN:</strong> " . $row["ISBN"] . "</p>";
				echo "<p><strong>Title:</strong> " . $row["BookTitle"] . "</p>";
				echo "<p><strong>Author:</strong> " . $row["Author"] . "</p>";
				echo "<p><strong>Edition:</strong> " . $row["Edition"] . "</p>";
				echo "<p><strong>Year:</strong> " . $row["YearPublished"] . "</p>";
				echo "<p><strong>Category:</strong> " . $row["CategoryName"] . "</p>";
				echo "</div>";
				echo "</form>";
			}
			
			echo "</div>"; // Close book list box
			echo "</div>"; // Closs book container
		} 
		
		// Show no books
		else 
		{
			echo "<p class='no-books'>No books in system</p>";
		}
		?>
	</div>

	<br><br><br>

	<!-- Footer -->
	<?php include 'includes/footer.inc.php'; ?>
  
</body>
</html>


