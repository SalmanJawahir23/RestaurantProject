<?php
    session_start(); 
?>
<?php  include '../inc/Header-dash.php'?>
<?php
    // Include config file
    require_once "../config.php";
    
    $input_item_id = $item_id_err = $item_id = "";
    
    // Processing form data when form is submitted
    if(isset($_POST['submit'])){
        if (empty($_POST['item_id'])) {
        $item_idErr = 'ID is required';
    } else {
        // $item_id = filter_var($_POST['item_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $item_id = filter_input(
        INPUT_POST,
        'item_id',
        FILTER_SANITIZE_FULL_SPECIAL_CHARS
        );
    }
        
    }
?>

<div class="container" >
    <div class="page-content">
        <div class="page-header">
            <h2>Create New Item</h2>
            <p>Please fill Items Information Properly</p>
        <div>
        <div class="table-search-bar add-form">
            <form method="POST" action="success_create.php">
                <table class="add-form-table">
                    <tr class="serach-input">
                        <td class="label">
                            <label for="item_id" class="form-label">Item ID :</label>
                        </td>
                        <td>
                            <input type="text" name="item_id" class="form-control <?php echo !$item_id_err ? : 'is-invalid'; ?>" id="item_id" required item_id="item_id" placeholder="D016" value="<?php echo $item_id; ?>">
                        </td>
                    </tr>
                    <tr class="serach-input">
                        <td class="label">
                            <label for="item_name">Item Name :</label>
                        </td>
                        <td>
                            <input type="text" name="item_name" id="item_name" placeholder="String Hoppers" required class="form-control <?php echo (!empty($itemname_err)) ? 'is-invalid' : ''; ?>" >
                        </td>
                    </tr>
                    <tr class="serach-input">
                        <td class="label">
                            <label for="item_type">Item Type:</label>
                        </td>
                        <td>
                            <select name="item_type" id="item_type" class="form-control <?php echo (!empty($itemtype_err)) ? 'is-invalid' : ''; ?>" required >
                                <option value="">Select Item Type</option>
                                <option value="Non-Vegetarian">Non-Vegetarian</option>
                                <option value="Vegetarian">Vegetarian</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="serach-input">
                        <td class="label">
                            <label for="item_category">Item Category:</label>
                        </td>
                        <td>
                            <select name="item_category" id="item_category" class="form-control <?php echo (!empty($itemcategory_err)) ? 'is-invalid' : ''; ?>" required>
                                <option value="">Select Item Category</option>
                                <option value="Main Dishes">Main Dishes</option>
                                <option value="Side Snacks">Side Snacks</option>
                                <option value="Drinks">Drinks</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="serach-input">
                        <td class="label">
                            <label for="item_price">Item Price :</label>
                        </td>
                        <td>
                            <input min='0.01' type="number" name="item_price" id="item_price" placeholder="25.00 LKR" step="0.01" required class="form-control <?php echo (!empty($itemprice_err)) ? 'is-invalid' : ''; ?>" >
                        </td>
                    </tr>
                    <tr class="serach-input">
                        <td class="label">
                            <label for="item_description">Item Description :</label>
                        </td>
                        <td>
                            <textarea name="item_description" id="item_description" rows="4" placeholder="Add food description" required class="form-control <?php echo (!empty($itemdescription_err)) ? 'is-invalid' : ''; ?>" ></textarea>
                        </td>
                    </tr>
                    <tr class="serach-input">
                        <td class="label">
                            <label for="item_description">Item Image URL :</label>
                        </td>
                        <td>
                            <textarea name="item_image" id="item_image" rows="4" placeholder="add image url" required class="form-control <?php echo (!empty($itemimage_err)) ? 'is-invalid' : ''; ?>" ></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="search-btn">
                                <button type="submit">Create Item</button>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
 
<?php include '../inc/dashFooter.php'; ?>