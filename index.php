<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-wdith, initial-scale=1.0">
    <title>KnowersArc Index</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,100,0,0&icon_names=person" />
</head>
<body>
    <?php
        // Start secure session
        require_once "includes/config_session.inc.php";
    ?>

    <!-- Header -->
    <?php include 'includes/header.inc.php'; ?>

    <!-- Login form section-->
    <section class="login-section">
        <form class="login-background" method="post" action="includes/login.inc.php">
            <!-- Login title -->
            <div class="form-title">
                Login
            </div>

            <!-- Login form items -->
            <div class="login-box">
                <div class="form-items">
                    <!-- Username section -->
                    <div>
                        <label class="form-item">Username:</label>
                        <input type="text" class="login-item" name="Username" placeholder="Spongebob01">
                    </div>
                </div>
                
                <div class="form-items">
                    <!-- Password section -->
                    <div>
                        <label class="form-item">Password:</label>
                        <input type="password" class="login-item" name="Password" placeholder="Password">
                    </div>
                </div>
            </div>

            <br>

            <!-- Submit and reset buttons -->
            <div class="login-buttons">
                <div>
                    <input type="submit" name="Add_New" value="Submit">
                    <input type="reset" name="Reset" value="Reset">
                </div>
            </div>
            
        </form>

        
        <?php
        // Display error message if it exists in session
        if (isset($_SESSION['logmsg'])) 
        {
            // Display and clear message
            echo '<div class="form-msg">' . $_SESSION['logmsg'] . '</div>';
            unset($_SESSION['logmsg']); 
        }
        ?>

    </section>


    <section class="register-section">
        <!-- Register form items -->
        <form class="register-background" method="post" action="includes/register.inc.php">
            <h3 class="form-title"> Register </h3>
            
            <!-- Username -->
            <div class="form-items">
                <div>
                    <label class="form-item">Username:</label>
                    <input type="text" class="login-item" name="Username" placeholder="Spongebob01" value="">
                </div>
            </div>
            
            <!-- Password -->
            <div class="form-items">
                <div>
                    <label class="form-item">Password:</label>
                    <input type="password" class="login-item" name="Password" placeholder="Password" value="">
                </div>
            </div>
            
            <!-- Confirm Password -->
            <div class="form-items">
                <div>
                    <label class="form-item">Confirm Password:</label>
                    <input type="password" class="login-item" name="ConfirmPassword" placeholder="Password">
                </div>
            </div>
            
            <!-- First Name -->
            <div class="form-items">
                <div>
                    <label class="form-item">First Name:</label>
                    <input type="text" class="login-item" name="FirstName" placeholder="Spongebob">
                </div>
            </div>
            
            <!-- Surname -->
            <div class="form-items">
                <div>
                    <label class="form-item">Surname:</label>
                    <input type="text" class="login-item" name="Surname" placeholder="Squarepants">
                </div>
            </div>
            
            <!-- Address Line 1 -->
            <div class="form-items">
                <div>
                    <label class="form-item">Address Line 1:</label>
                    <input type="text" class="login-item" name="AddressLine1">
                </div>
            </div>
            
            <!-- Address Line 2 -->
            <div class="form-items">
                <div>
                    <label class="form-item">Address Line 2:</label>
                    <input type="text" class="login-item" name="AddressLine2">
                </div>
            </div>
            
            <!-- City -->
            <div class="form-items">
                <div>
                    <label class="form-item">City:</label>
                    <input type="text" class="login-item" name="City" placeholder="Bikini Bottom">
                </div>
            </div>

            <!-- Telephone -->
            <div class="form-items">
                <div>
                    <label class="form-item">Telephone:</label>
                    <input type="tel" class="login-item" name="Telephone">
                </div>
            </div>
            
            <!-- Mobile -->
            <div class="form-items">
                <div>
                    <label class="form-item">Mobile:</label>
                    <input type="tel" class="login-item" name="Mobile">
                </div>
            </div>

            <br>
            
            <!-- Register form buttons -->
            <div class="reg-buttons">
                <div>
                    <input type="submit" name="Add_New" value="Register" class="login-item">
                    <input type="reset" name="Reset" value="Reset" class="login-item">
                </div>
            </div>
        </form>

    
        <?php
        // Display success or error message if it exists in session
        if (isset($_SESSION['regmsg'])) 
        {
            // Display and clear message
            echo '<div class="form-msg">' . $_SESSION['regmsg'] . '</div>';
            unset($_SESSION['regmsg']); 
        }
        ?>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.inc.php'; ?>
        
</body>
</html>