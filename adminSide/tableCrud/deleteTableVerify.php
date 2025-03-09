<?php
require_once "../config.php";

// if 'id' is set and not empty
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $table_id = intval($_GET['id']);
} else {
    header("Location: ../panel/table-panel.php");
    exit(); // Exit after redirect
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $provided_account_id = $_POST['admin_id'];
    $provided_password = $_POST['password'];
    $uniqueString = $provided_account_id . $provided_password;


    if ($uniqueString == "9999912345") {
        echo ' Correct';
        header("Location: ../tableCrud/deleteTable.php?id=".$table_id ."");
    } else {
        echo '<script>alert("Incorrect ID or Password!")</script>';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/adminpass.css">
</head>
<body>
    <div class="login-container">
        <div class="login_wrapper">
                
            <h2>Admin Pass</h2>
            <p>Admin permission needed to Delete Table</p>

            <form action="" method="post">
                <div class="form-group">
                    <label>Admin Id:</label>
                    <input type="number" name="admin_id" class="form-control" placeholder="Enter Admin ID" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter Admin Password" required>
                </div>
                <div class="form-group">
                    <button class="btn btn-light" type="submit" name="submit" value="submit">Delete Table</button>
                    <a class="btn btn-danger" href="../panel/table-panel.php" >Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
