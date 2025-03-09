<?php
session_start();
?>

<?php
// Include config file
require_once "../config.php";

// Initialize variables for form validation and item data
$item_id = $item_name = $item_type = $item_category = $item_price = $item_description = $item_imge = "" ;
$item_id_err = "";

// Check if item_id is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $item_id = $_GET['id'];

    // Retrieve item details based on item_id
    $sql = "SELECT * FROM Menu WHERE item_id = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_item_id);
        $param_item_id = $item_id;
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $item_name = $row['item_name'];
                $item_type = $row['item_type'];
                $item_category = $row['item_category'];
                $item_price = $row['item_price'];
                $item_description = $row['item_description'];
                $item_image = $row['item_image'];
            } else {
                echo "Item not found.";
                exit();
            }
        } else {
            echo "Error retrieving item details.";
            exit();
        }
     
    }
}

// Process form submission when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // echo "Received POST data: <pre>";
//print_r($_POST);
//echo "</pre>";
    // Validate and sanitize input
    $item_name = trim($_POST["item_name"]);
    $item_type = trim($_POST["item_type"]);
    $item_category = trim($_POST["item_category"]);
    $item_price = floatval($_POST["item_price"]); // Convert to float
    $item_description = $_POST["item_description"];
    $item_image = $_POST["item_image"];

    // Update the item in the database
    $update_sql = "UPDATE Menu SET item_name='$item_name', item_type='$item_type', item_category='$item_category', item_price='$item_price', item_description='$item_description', item_image='$item_image' WHERE item_id='$item_id'";
    $resultItems = mysqli_query($link, $update_sql);
    
        if ($resultItems) {
            // Item updated successfully
          
           header("Location: ../panel/menu-panel.php");
            exit();
        } else {
            echo "Error updating item: ";
        }

       
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central Ceylon</title>
    <link rel="shortcut icon" href="./favicon.png" type="image/x-icon">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/header-main.css">
    <style>
        /* header{
            opacity: 30%;
            pointer-events: none;
        } */
        .page-content{
            width: 100vw;
            height: 100vh;
            
        }
        .section-div{
            margin: 40px auto;
            padding: 20px;
            width: fit-content;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
        }
        .page-header{
            margin: auto;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="page-content">
        <div class="section-div">
            <div class="page-header">
                <h2>Update Item</h2>
                <h5>Admin Credentials needed to Edit Item</h5>
            </div>
            <div class="table-search-bar add-form">
                <form action="" method="post" >
                    <table class="add-form-table">
                        <tr class="serach-input">
                            <td class="label">
                                <label for="item_name"  class="form-label" >Item Name:</label>
                            </td>
                            <td>
                                <input type="text" name="item_name" id="item_name" class="form-control"  placeholder="Spaghetti" value="<?php echo htmlspecialchars($item_name); ?>" required>
                            </td>
                        </tr>
                        <tr class="serach-input">
                            <td class="label">
                                <label for="item_type"  class="form-label">Item Type:</label>
                            </td>
                            <td>
                                <input type="text" name="item_type" id="item_type" class="form-control"placeholder="Beer, Cocktail, etc .." value="<?php echo htmlspecialchars($item_type); ?>" required>
                            </td>
                        </tr>
                        <tr class="serach-input">
                            <td class="label">
                                <label for="item_category" >Item Category:</label>
                            </td>
                            <td>
                                <input type="text" name="item_category" id="item_category" class="form-control" placeholder="Main Dish/ Side Dish/ Drinks" value="<?php echo htmlspecialchars($item_category); ?>" required>
                            </td>
                        </tr>
                        <tr class="serach-input">
                            <td class="label">
                                <label for="item_price">Item Price:</label>
                            </td>
                            <td>
                                <input type="number" min=0.01 step="0.01" name="item_price" id="item_price" placeholder="Enter Item Price"class="form-control" value="<?php echo htmlspecialchars($item_price);?>" required>
                            </td>
                        </tr>
                        <tr class="serach-input">
                            <td class="label">
                                <label for="item_description" class="form-label" >Item Description:</label>
                            </td>
                            <td>
                                <textarea name="item_description" id="item_description" placeholder="The dish...." required class="form-control"> <?php echo htmlspecialchars($item_description); ?> </textarea>
                            </td>
                        </tr>
                        <tr class="serach-input">
                            <td class="label">
                                <label for="item_image" class="form-label" >Item image:</label>
                            </td>
                            <td>
                                <textarea name="item_image" id="item_image" placeholder="url" required class="form-control"> <?php echo htmlspecialchars($item_image); ?> </textarea>
                            </td>
                        </tr>
                        
                        <tr class="search-btn">
                            <td>
                                <input type="hidden" name="item_id" value=""   class="form-control">
                                <a class="btn delete" style="padding: 13px; border-radius:8px;" href="../panel/menu-panel.php" >Cancel</a>
                            </td>
                            <td>
                                <div>
                                    <button class="btn btn-light" type="submit" name="submit" value="submit">Update</button>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>