<?php
// Instantiate database and get database connection
require_once 'crud.php';
require_once 'db_connection.php';
$db = new Database("localhost", "root", "", "db_manginasal");

// Create an instance of the Product class and inject the database connection
$soldItem = new Item($db);

// Check if item_sold and quantity variables are set for insert
if (isset($_POST['sold_item'], $_POST['quantity'])) {
    // Retrieve form data
    $item_sold = $_POST['sold_item']; // Ensure this matches your form's input name
    $quantity = $_POST['quantity']; // Ensure this matches your form's input name
    
    // Insert the sold item
    if ($soldItem->insert($item_sold, $quantity, "tbl_sold")) {

        echo "Item sold successfully.";
    } else {
        echo "Failed to record the sold item.";
    }
}
if (isset($_POST['add_stock'], $_POST['add_quantity'])) {
    // Retrieve form data
    $addStock = $_POST['add_stock']; // Ensure this matches your form's input name
    $quantity = $_POST['add_quantity']; // Ensure this matches your form's input name
    
    // Insert the sold item
    if ($soldItem->insertStock($quantity, $addStock, "tbl_items", "item_name", "item_quantity")) {

        echo "Item sold successfully.";
    } else {
        echo "Failed to record the sold item.";
    }
}
if (isset($_POST['update_sold'], $_POST['update_quantity'])) {
    // Retrieve form data
    $update = $_POST['update_sold']; // Ensure this matches your form's input name
    $quantity = $_POST['update_quantity']; // Ensure this matches your form's input name
    
    // Insert the sold item
    if ($soldItem->updateSold($quantity, $update, "tbl_sold", "sold_id", "sold_item_quantity")) {

        echo "Item sold successfully.";
    } else {
        echo "Failed to record the sold item.";
    }
}

// Check if delete_sold variable is set for delete
if (isset($_POST['delete_sold'])) {
    // Retrieve form data
    $delete_sold = $_POST['delete_sold'];
    var_dump($delete_sold);
    // Delete the sold item
    if ($soldItem->delete($delete_sold, "tbl_sold")) {
        echo "Item deleted successfully.";
    } else {
        echo "Failed to delete the sold item.";
    }
}

if(isset($_POST['apply-btn'])){
    $soldItem->apply();
    $soldItem->storeToHistory();
    $soldItem->truncateTblSold();

    echo '<script>';
    echo 'alert("Operation applied successfully!");'; 
    echo '</script>';
}
    
// Redirect to index.php
header("Location: index.php");
exit();
?>
