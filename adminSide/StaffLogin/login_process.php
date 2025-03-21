<?php
session_start();
?>
<?php
require_once "../config.php";


// Check whether form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $provided_account_id = $_POST['account_id'];
    $provided_password = $_POST['password'];

    // Query to fetch staff record
    $query = "SELECT * FROM Accounts WHERE account_id = '$provided_account_id'";
    $result = $link->query($query);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        // Password matches, login successful
        if ($provided_password === $stored_password) {
        
            // Check if the account_id exists in the Staffs table
            $staff_query = "SELECT * FROM Staffs WHERE account_id = '$provided_account_id'";
            $staff_result = $link->query($staff_query);

            if ($staff_result->num_rows === 1) {
                $staff_row = $staff_result->fetch_assoc();
                $logged_staff_name = $staff_row['staff_name']; // Get staff_name
                $logged_staff_id = $staff_row['staff_id']; 
                
                // After successful login, store staff details in session
                $_SESSION['logged_account_id'] = $provided_account_id;
                $_SESSION['logged_staff_name'] = $logged_staff_name;
                $_SESSION['logged_staff_id'] = $logged_staff_id;
                
                // Directly go to the pos panel
                header("Location: ../panel/pos-panel.php");
                exit;
                
            } else {
                // Staff ID not found in Staffs table
                $message = "Staff ID not found.<br>Please try again to choose a correct Staff ID.";
                $iconClass = "fa-times-circle";
                $cardClass = "alert-danger";
                $bgColor = "#FFA7A7";
                $direction = "login.php";
            }      
            
        } else {
            $message = "Incorrect password.<br>Please try again to type your password.";
            $iconClass = "fa-times-circle";
            $cardClass = "alert-danger";
            $bgColor = "#FFA7A7";
            $direction = "login.php";
        }
    } else {
        $message = "Staff ID not found.<br>Please try again to choose a correct Staff ID.";
        $iconClass = "fa-times-circle";
        $cardClass = "alert-danger";
        $bgColor = "#FFA7A7";
        $direction = "login.php";
    }
}

$link->close();
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <style>
        body {
            text-align: center;
            padding: 40px 0;
            background: #EBF0F5;
        }
        h1 {
            color: #88B04B;
            font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
            font-weight: 900;
            font-size: 40px;
            margin-bottom: 10px;
        }
        p {
            color: #404F5E;
            font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
            font-size: 20px;
            margin: 0;
        }
        i.checkmark {
            color: #9ABC66;
            font-size: 100px;
            line-height: 200px;
            margin-left: -15px;
        }
        .card {
            background: white;
            padding: 60px;
            border-radius: 4px;
            box-shadow: 0 2px 3px #C8D0D8;
            display: inline-block;
            margin: 0 auto;
        }
        .alert-success {
            background-color: <?php echo $bgColor; ?>;
        }
        .alert-success i {
            color: #5DBE6F;
        }
        .alert-danger {
            background-color: #FFA7A7;
        }
        .alert-danger i {
            color: #F25454;
        }
        .custom-x {
            color: #F25454;
            font-size: 100px;
            line-height: 200px;
        }
            .alert-box {
            max-width: 300px;
            margin: 0 auto;
        }

        .alert-icon {
            padding-bottom: 20px;
        }
    
    </style>
</head>
<body>
    <div class="card <?php echo $cardClass; ?>" style="display: none;">
        <div style="border-radius: 200px; height: 200px; width: 200px; background: #F8FAF5; margin: 0 auto;">
            <?php if ($iconClass === 'fa-check-circle'): ?>
                <i class="checkmark">✓</i>
            <?php else: ?>
                <i class="custom-x" style="font-size: 100px; line-height: 200px;">✘</i>
            <?php endif; ?>
        </div>
        <h1><?php echo ($cardClass === 'alert-success') ? 'Success' : 'Error'; ?></h1>
        <p><?php echo $message; ?></p>
    </div>

    <div style="text-align: center; margin-top: 20px;">Redirecting back in <span id="countdown">3</span></div>

    <script>
        //Declare the direction
        var direction = "<?php echo $direction; ?>";
        
        // Show the message card as pop-up
        function showPopup() {
            var messageCard = document.querySelector(".card");
            messageCard.style.display = "block";

            var i = 3;
            var countdownElement = document.getElementById("countdown");
            var countdownInterval = setInterval(function() {
                i--;
                countdownElement.textContent = i;
                if (i <= 0) {
                    clearInterval(countdownInterval);
                    window.location.href = direction;
                }
            }, 1000);
        }
        window.onload = showPopup;

        // Hide the message card
        function hidePopup() {
            var messageCard = document.querySelector(".card");
            messageCard.style.display = "none";
            // Redirect
            setTimeout(function () {
                window.location.href = direction;
            }, 3000);
        }
        setTimeout(hidePopup, 3000);
    </script>
</body>
</html>