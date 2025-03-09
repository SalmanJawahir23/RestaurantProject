<?php
    session_start();
?>

<?php  include '../inc/Header-dash.php'?>

<?php
    require_once "../config.php";

    $conn = $link;

    $input_table_id = $table_id_err = $table_id = "";

    // To get the next available table id
    function getNextAvailableTableID($conn) {
        $sql = "SELECT MAX(table_id) as max_table_id FROM Restaurant_Tables";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $next_table_id = $row['max_table_id'] + 1;
        return $next_table_id;
    }
    $next_table_id = getNextAvailableTableID($conn);
?>


<style>
    .table-search-bar{
        margin-top: 20px;
    }
    .table-search-bar form{
        flex-direction: column;
        align-items: flex-start;

    }
</style>

<div class="container" >
    <div class="page-content">
        <div class="page-header">
            <h2>Create New Table</h2>
        </div>
        
        <div class="table-search-bar add-form">
            <form method="POST" action="succ_create_table.php">
                <table class="add-form-table">
                    <tr class="serach-input">
                        <td class="label">
                            <label for="table_id" class="form-label">Table ID :</label>
                        </td>
                        <td>
                            <input min="1" type="number" name="table_id"placeholder="1"  class="form-control <?php echo $next_table_id ? 'is-invalid' : ''; ?>" id="next_tab_idle" required value="<?php echo $next_table_id; ?>" readonly>
                        </td>
                    </tr>
                    <tr class="serach-input">
                        <td class="label">
                            <label for="capacity">Capacity :</label>
                        </td>
                        <td>
                            <input placeholder="8" min="1" type="number" name="capacity" min=1 id="capacity" required class="form-control <?php echo (!empty($capacity)) ? 'is-invalid' : ''; ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="search-btn">
                                <button type="submit">Create table</button>
                            </div>
                        </td>
                    </tr>
                </table> 
            </form>
        </div>
    </div>
</div>
 
<?php include '../inc/dashFooter.php'; ?>