<?php
// If user logged in
if (isset($_SESSION['user_id'])) 
{
    
    // Show user icon and username
    echo  "<p class='username'> <span class='material-symbols-outlined'>person</span>" . $_SESSION['user_id'] . "</p>";

    // Logout button
    echo    '<form class="form-style" action="includes/logout.inc.php" method="POST">' .
            '<button class="logout-button" type="submit">Logout</button>' .
            '</form>';
}

// Show button to go to login page
else
{
    echo "<a class='username' href=" . "index.php" . ">Login</a>";
}
