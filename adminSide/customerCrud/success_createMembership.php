<?php
require_once "../config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $member_id = $_POST["member_id"];
    $member_name = $_POST["member_name"];
    $points = $_POST["points"];
    $account_id = $_POST["account_id"];
    $email = $_POST["email"];
    $register_date = $_POST["register_date"];
    $phone_number = $_POST["phone_number"];
    $password = $_POST["password"];
    $conn = $link;

    // Start a transaction to ensure consistency across multiple table inserts
    $conn->begin_transaction();

    try {
        // Insert Data into Accounts Table
        $insert_account_query = "INSERT INTO Accounts (account_id, email, register_date, phone_number, password) VALUES (?, ?, ?, ?, ?)";
        $stmt_account = $conn->prepare($insert_account_query);
        $stmt_account->bind_param("issss", $account_id, $email, $register_date, $phone_number, $password);

        // Execute the query to insert data into Accounts table
        if (!$stmt_account->execute()) {
            throw new Exception("Error creating account: " . $stmt_account->error);
        }

        // Insert Data into Memberships Table
        $insert_membership_query = "INSERT INTO Memberships (member_id, member_name, points, account_id) VALUES (?, ?, ?, ?)";
        $stmt_membership = $conn->prepare($insert_membership_query);
        $stmt_membership->bind_param("issi", $member_id, $member_name, $points, $account_id);

        // Execute the query to insert data into Memberships table
        if (!$stmt_membership->execute()) {
            throw new Exception("Error creating membership: " . $stmt_membership->error);
        }

        // Commit the transaction if everything is successful
        $conn->commit();

        $message = "Membership created successfully.";
        $iconClass = "fa-check-circle";
        $cardClass = "alert-success";
        $bgColor = "#D4F4DD";
    } catch (Exception $e) {
        // Rollback
        $conn->rollback();

        $message = "Error: " . $e->getMessage();
        $iconClass = "fa-times-circle";
        $cardClass = "alert-danger";
        $bgColor = "#FFA7A7";
    } finally {
        // Close the prepared statements
        $stmt_account->close();
        $stmt_membership->close();

        // Close the connection
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <style>
        body {
            text-align: center;
            padding: 40px 0;
            background: <?php echo $bgColor; ?>;
            font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        }
        .card {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .card.alert-success {
            border-top: 6px solid green;
        }
        .card.alert-danger {
            border-top: 6px solid red;
        }
        .card h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .card p {
            margin: 0;
            color: #333;
        }
        .card i {
            font-size: 50px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="card <?php echo $cardClass; ?>">
        <div style="border-radius: 200px; height: 200px; width: 200px; background: #F8FAF5; margin: auto;">
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
        function showPopup() {
            var messageCard = document.querySelector(".card");
            messageCard.style.transform = "scale(1)";

            var i = 3;
            var countdownElement = document.getElementById("countdown");
            var countdownInterval = setInterval(function() {
                i--;
                countdownElement.textContent = i;
                if (i <= 0) {
                    clearInterval(countdownInterval);
                    window.location.href = "../panel/customer-panel.php";
                }
            }, 1000);
        }

        window.onload = showPopup;
    </script>
</body>
</html>