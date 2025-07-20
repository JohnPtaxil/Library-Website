<?php
// Open Connection
require_once "dbh.inc.php";

// If user searched something
if (isset($_SESSION['bookmem']) && isset($_SESSION['authmem']) && isset($_SESSION['catmem']))
{
    // Set local variables
    $u = $_SESSION['bookmem'];
    $p = $_SESSION['authmem'];
	$f = $_SESSION['catmem'];

    // Starting row
    $start = 0;
    
    // Number of rows per page
    $rows_per_page = 3;

    // Get full search for calculations
    $full_search = "SELECT books.*, categories.CategoryDescription as CategoryName
                    FROM books JOIN Categories ON books.Category = categories.CategoryID 	
                    WHERE ('$u' = '' OR LOWER(books.BookTitle) LIKE LOWER('%$u%'))
                    AND ('$p' = '' OR LOWER(books.Author) LIKE LOWER('%$p%'))
                    AND ('$f' = '' OR LOWER(books.Category) LIKE LOWER('%$f%'))";

    // Get books on specific pages, based on user search
    $records = $conn->query($full_search);

    // Check if query succeeded
    if ($records->num_rows == 0)
    {
        // Set message 
        $_SESSION['message'] = "No book matches search";

        // Unset Session variables
        unset($_SESSION['bookmem']);
        unset($_SESSION['authmem']);
        unset($_SESSION['catmem']);
    }

    // Books found matching search
    else
    {
        // Count number of books that match search (rows)
        $nr_of_rows = $records->num_rows;

        // Calculate number of pages
        $pages = ceil($nr_of_rows / $rows_per_page);
    
        // User clicks pagination buttons (change starting point)
        if(isset($_GET['page-nr']))
        {
            // Page 1 corresponds to row 0. i.e. change element to index
            $page = $_GET['page-nr'] - 1;

            // Move row starting point
            $start = $page * $rows_per_page;
        }
    
    
        // Get smaller search using same inputs (give row alias "CategoryName)
        $small_search = "SELECT books.*, categories.CategoryDescription as CategoryName
                        FROM books JOIN Categories ON books.Category = categories.CategoryID 	
                        WHERE ('$u' = '' OR LOWER(books.BookTitle) LIKE LOWER('%$u%'))
                        AND ('$p' = '' OR LOWER(books.Author) LIKE LOWER('%$p%'))
                        AND ('$f' = '' OR LOWER(books.Category) LIKE LOWER('%$f%'))
                        LIMIT $start, $rows_per_page";

        
        // Get books on specific pages, based on user search
        $result = $conn->query($small_search);

    }
}

// Close connection
$conn->close();
