<?php
    require_once '../config.php';
    session_start();

    // Define variables
    $email = $password = "";
    $email_err = $password_err = "";

    // Check form submiton
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Validate email
        if (empty(trim($_POST["email"]))) {
            $email_err = "Please enter your email.";
        } else {
            $email = trim($_POST["email"]);
        }

        // Validate password
        if (empty(trim($_POST["password"]))) {
            $password_err = "Please enter your password.";
        } else {
            $password = trim($_POST["password"]);
        }

        // Check input errors
        if (empty($email_err) && empty($password_err)) {

            $sql = "SELECT * FROM Accounts WHERE email = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_email);

                // Set parameters
                $param_email = $email;

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
              
                    $result = mysqli_stmt_get_result($stmt);

                    // Check if a matching record was found.
                    if (mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_assoc($result);
                        
                        // Verify the password
                        if ($password === $row["password"]) {
                            // Password is correct, start a new session and redirect the user to a dashboard or home page.
                            $_SESSION["loggedin"] = true;
                            $_SESSION["email"] = $email;

                            // Query to get membership details
                            $sql_member = "SELECT * FROM Memberships WHERE account_id = " . $row['account_id'];
                            $result_member = mysqli_query($link, $sql_member);

                            if ($result_member) {
                                $membership_row = mysqli_fetch_assoc($result_member);

                                if ($membership_row) {
                                    $_SESSION["account_id"] = $membership_row["account_id"];
                                    header("location: ../home/home.php"); // Redirect to the home page
                                    exit;
                                } else {
                                    // No membership details found
                                    $password_err = "No membership details found for this account.";
                                }
                            } else {
                                // Error in membership query
                                $password_err = "Error fetching membership details: " . mysqli_error($link);
                            }
                        } else {
                            // Password is incorrect
                            $password_err = "Invalid password. Please try again.";
                        }

                    } else {
                        // No matching records found
                        $email_err = "No account found with this email.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="authent.css">
    <title>Login</title>
</head>
<body>
    <section>

        <!--container-->
        <div class="form_div">

            <!-- container > heder -->
            <div class="form_header">
                <h2>Sign In</h2>
                <div class="switch_login">
                    
                    <div class="form_header_button">
                        <h5><a href="">Member</a></h5>
                    </div>
                    <div class="form_header_button_off">
                        <h5><a href="../../adminSide\StaffLogin\login.php">Staff</a></h5>
                    </div>

                </div>
                
            </div>
            
            <!-- form -->
            <div class="wrapper">

                <form id="authentication_form" action="login.php" method="post">
                    <label for="Email">Email:</label>
                    <input type="email" id="email"  name="email" placeholder="Enter your email" required>
                    <span class="text-danger"><?php echo $email_err; ?></span>

                    <label for="password">Password:</label>
                    <input type="password" id="password"  name="password" placeholder="Enter your password" required>
                    <span class="text-danger"><?php echo $password_err; ?></span>

                    <div id="form_middle_div">

                        <div id="saveLogin">
                            <input type="checkbox" name="save_login" id="saveLogin_checkbox" >
                            <label for="saveLogin_checkbox">Save my login.</label>
                        </div>

                        <p><a href="reset-password.php">Forgot password?</a></p>
                        
                    </div>

                    <input type="submit" value="Login" name="submit">
                </form>
                
                <p>Don't have an account? <a href="register.php">Register</a>.</p>

            </div>
        </div>
    </section>
</body>
</html>