<?php

// If user logged in
if (isset($_SESSION['user_id'])) 
{
    // Starting row
    $start = 0;
    
    // Number of rows per page
    $rows_per_page = 3;

    // From login page
    $username = $_SESSION['user_id'];

    // Query to fetch the user's reserved books
    $sql = "SELECT * FROM reservations WHERE Username = '$username'";
    $records = $conn->query($sql);

    // Check if query succeeded
    if ($records->num_rows == 0) 
    {
        // Set reserves to false
        $_SESSION['reservations'] = FALSE;
    }
    else
    {
        // Set session variable indicating data is in reservations
        $_SESSION['reservations'] = TRUE;

        // Count number of rows returned
        $nr_of_rows = $records->num_rows;

        // Calculate number of pages
        $pages = ceil($nr_of_rows / $rows_per_page);
    
        // User clicks pagination buttons (change starting point)
        if(isset($_GET['page-nr']))
        {
            $page = $_GET['page-nr'] - 1;
            $start = $page * $rows_per_page;
        }
    
    
        // Get smaller search
        $small_search = "SELECT * FROM reservations WHERE Username = '$username' LIMIT $start, $rows_per_page";
        
        // Get books on specific pages, based on user search
        $result = $conn->query($small_search);
    }


}