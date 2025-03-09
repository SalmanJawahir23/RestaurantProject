<?php
    session_start();
?>
<?php include '../inc/Header-dash.php'; ?>
<?php
    // Include config file
    require_once "../config.php";


    // Define variables and initialize them
    $member_id = $member_name = $points = $account_id = "";
    $member_id_err = $member_name_err = $points_err = "";
    $input_account_id = $account_iderr = $account_id = "";
    $input_email = $email_err = $email = "";
    $input_register_date = $register_date_err = $register_date = "";
    $input_phone_number = $phone_number_err = $phone_number = "";
    $input_password = $password_err = $password = "";

    // Function to get the next available account ID
    function getNextAvailableAccountID($conn) {
        $sql = "SELECT MAX(account_id) as max_account_id FROM Accounts";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $next_account_id = $row['max_account_id'] + 1;
        return $next_account_id;
    }

    // Function to get the next available Member ID
    function getNextAvailableMemberID($conn) {
        $sql = "SELECT MAX(member_id) as max_member_id FROM Memberships";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $next_member_id = $row['max_member_id'] + 1;
        return $next_member_id;
    }

    // Get the next available Member ID
    $next_member_id = getNextAvailableMemberID($link);

    // Get the next available account ID
    $next_account_id = getNextAvailableAccountID($link);
?>

<style>
    /* input:required:invalid {
        color: #999;
        border-color: #f00;
    }
    input:required:valid {
        border-color: #28a745;
        color: #333;
    } */
</style>


<div class="container">
    <div class="page-content">
        <div class="page-header">
            <h2>Create New Membership</h2>
            <p>Please fill in Membership Information</p>
        </div>

        <div class="table-search-bar add-form">
            <form method="POST" action="success_createMembership.php">
                <table class="add-form-table">
                    <tr class="serach-input">
                        <td class="label">
                            <label for="member_id" class="form-label">Member ID:</label>
                        </td>
                        <td>
                            <input min="1" type="number" name="member_id" placeholder="1" class="form-control <?php echo $member_id_err ? 'is-invalid' : ''; ?>" id="member_id" required value="<?php echo $next_member_id; ?>" readonly>
                        </td>
                    </tr>
                    
                    <tr class="serach-input">
                        <td class="label">
                            <label for="member_name" class="form-label">Member Name :</label>
                        </td>
                        <td>
                            <input type="text" name="member_name" placeholder="Perera" class="form-control <?php echo $member_name_err ? 'is-invalid' : ''; ?>" id="member_name" required value="<?php echo $member_name; ?>">
                        </td>
                    </tr>

                    <tr class="serach-input">
                        <td class="label">
                            <label for="points">Points :</label>
                        </td>
                        <td>
                            <input type="number" name="points" id="points" placeholder="1234" required class="form-control <?php echo $points_err ? 'is-invalid' : ''; ?>" value="<?php echo $points; ?>">
                        </td>
                    </tr>

                    <tr class="serach-input">
                        <td class="label">
                            <label for="account_id" class="form-label">Account ID:</label>
                        </td>
                        <td>
                            <input min="1" type="number" name="account_id" placeholder="99" class="form-control <?php echo !$account_iderr ?: 'is-invalid'; ?>" id="account_id" required value="<?php echo $next_account_id; ?>" readonly>
                        </td>
                    </tr>
                    
                    <tr class="serach-input">
                        <td class="label">
                            <label for="email" class="form-label">Email :</label>
                        </td>
                        <td>
                            <input type="text" name="email" placeholder="Perera@acc.com" class="form-control <?php echo !$email_err ?: 'is-invalid'; ?>" id="email" required value="<?php echo $email; ?>">
                        </td>
                    </tr>

                    <tr class="serach-input">
                        <td class="label">
                            <label for="register_date">Register Date :</label>
                        </td>
                        <td>
                            <input type="date" name="register_date" id="register_date" required class="form-control <?php echo !$register_date_err ?: 'is-invalid';?>" value="<?php echo $register_date; ?>">
                        </td>
                    </tr>

                    <tr class="serach-input">
                        <td class="label">
                            <label for="phone_number" class="form-label">Phone Number:</label>
                        </td>
                        <td>
                            <input type="text" name="phone_number" placeholder="0111234567" class="form-control <?php echo !$phone_number_err ?: 'is-invalid'; ?>" id="phone_number" required value="<?php echo $phone_number; ?>">
                        </td>
                    </tr>

                    <tr class="serach-input">
                        <td class="label">
                            <label for="password">Password :</label>
                        </td>
                        <td>
                            <input type="password" name="password" placeholder="Perera@1234" id="password" required class="form-control <?php echo !$password_err ?: 'is-invalid' ; ?>" value="<?php echo $password; ?>">
                        </td>
                    </tr>
                    
                    <tr class="search-btn">
                        <td></td>
                        <td>
                            <div>
                                <button type="submit" name="submit">Create Membership</button>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>