<?php
    session_start();
?>
<?php include '../inc/Header-dash.php'; ?>
<?php
    require_once "../config.php";

    $input_staff_id = $staff_id_err = $staff_id = "";
    $input_account_id = $account_iderr = $account_id = "";
    $input_email = $email_err = $email = "";
    $input_register_date = $register_date_err = $register_date = "";
    $input_phone_number = $phone_number_err = $phone_number = "";
    $input_password = $password_err = $password = "";

    // Processing form data when form is submitted
    if (isset($_POST['submit'])) {
        if (empty($_POST['staff_id'])) {
            $staff_idErr = 'ID is required';
        } else {
            $staff_id = filter_input(
                INPUT_POST,
                'staff_id',
                FILTER_SANITIZE_FULL_SPECIAL_CHARS
            );
        }
    }

    // To get the next available account ID
    function getNextAvailableAccountID($conn) {
        $sql = "SELECT MAX(account_id) as max_account_id FROM Accounts";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $next_account_id = $row['max_account_id'] + 1;
        return $next_account_id;
    }

    // To get the next available Staff ID
    function getNextAvailableStaffID($conn) {
        $sql = "SELECT MAX(staff_id) as max_staff_id FROM Staffs";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $next_staff_id = $row['max_staff_id'] + 1;
        return $next_staff_id;
    }

    // Get the next available Staff ID
    $next_staff_id = getNextAvailableStaffID($link);

    // Get the next available account ID
    $next_account_id = getNextAvailableAccountID($link);
?>

<style>
    #account_id:required:invalid {
        color: #999;
        border-color: #f00;
    }
    #account_id:required:valid {
        border-color: #28a745;
        color: #333;
    }
</style>


<div class="container">
    <div class="page-content">
        <div class="page-header">
            <h2>Create New Staff</h2>
            <p>Please fill in the Staff Information</p>
        </div>

        <div class="table-search-bar add-form">
            <form method="POST" action="succ_create_staff.php">
                <table class="add-form-table">
                    <tr class="serach-input">
                        <td class="label">
                            <label for="staff_id" class="form-label">Staff ID:</label>
                        </td>
                        <td>
                            <input min="1" type="number" name="staff_id" placeholder="1" class="form-control <?php echo $staff_id_err ? 'is-invalid' : ''; ?>" id="staff_id" required value="<?php echo $next_account_id; ?>" readonly>
                        </td>
                    </tr>

                    <tr class="serach-input">
                        <td class="label">
                            <label for="staff_name">Staff Name:</label>
                        </td>
                        <td>
                            <input type="text" name="staff_name" placeholder="Perera" id="staff_name" required class="form-control <?php echo (!empty($staff_name_err)) ? 'is-invalid' : ''; ?>">
                        </td>
                    </tr>

                    <tr class="serach-input">
                        <td class="label">
                            <label for="role">Role:</label>
                        </td>
                        <td>
                            <input type="text" name="role" id="role" placeholder="Waiter" required class="form-control <?php echo (!empty($role_err)) ? 'is-invalid' : ''; ?>">
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
                    
                    <tr>
                        <td></td>
                        <td>
                            <div class="search-btn">
                                <button type="submit">Create Staff</button>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>
