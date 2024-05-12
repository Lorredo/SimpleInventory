<?php
require_once 'db_connection.php';

class Item {
    private $db;

    // Object properties


    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function insert($itemName, $quantity, $table_name) {
        $conn = $this->db->getConnection();
        $sql = "INSERT INTO ".$table_name. " (sold_item, sold_item_quantity) VALUES (?, ?)";
    
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error;
            return false;
        }
    
        // Assuming $itemName is a string and $quantity is an integer
        $stmt->bind_param("si", $itemName, $quantity);
    
        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error executing statement: " . $stmt->error;
            return false;
        }
    }

    public function insertStock($quantity, $itemName, $table_name, $col_name, $quan_col) {
        $conn = $this->db->getConnection();
        $sql = "UPDATE ".$table_name." SET ".$quan_col." = $quan_col + ? WHERE ".$col_name." = ?";
    
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error;
            return false;
        }
    
        // Assuming $itemName is a string and $quantity is an integer
        $stmt->bind_param("is", $quantity, $itemName);
    
        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error executing statement: " . $stmt->error;
            return false;
        }
    }
    
    public function updateSold($quantity, $soldID, $table_name, $col_name, $quan_col) {
        $conn = $this->db->getConnection();
        $sql = "UPDATE ".$table_name." SET ".$quan_col." = ? WHERE ".$col_name." = ?";
    
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error;
            return false;
        }
    
        // Assuming $itemName is a string and $quantity is an integer
        $stmt->bind_param("ii", $quantity, $soldID);
    
        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error executing statement: " . $stmt->error;
            return false;
        }
    }

    public function getProductInfo() {
        $conn = $this->db->getConnection();
        $sql = "SELECT item_image,item_name, item_quantity AS total_quantity, item_quantity_type FROM tbl_items";
        $result = $conn->query($sql);

        // Check if there are any rows returned
        if ($result->num_rows > 0) {
            // Loop through the rows
            while ($row = $result->fetch_assoc()) {
        ?> 
                <div class="card p-3  mb-2 shadow bg-black" style="width: 13rem; height: 13rem; border-radius:1rem;">
                    <img src="<?php echo $row['item_image']; ?>" class="card-img-top" style="max-height: 110px">
                    <div class="card-body p-0">
                        <h5 class="card-title"><?php echo $row['item_name']; ?></h5>
                        <p class="card-text mb-1">Quantity <?php echo $row['item_quantity_type'] .': '.$row['total_quantity'] ; ?></p>

                    </div>
                </div>
        <?php
            }
        } else {
        
            echo "No items found.";
        }
    }
    public function getProductInfoimg() {
        $conn = $this->db->getConnection();
        $sql = "SELECT tbl_sold.sold_id, tbl_items.item_name, tbl_sold.sold_item_quantity, tbl_items.item_image, tbl_items.item_quantity_type
        FROM tbl_sold
        INNER JOIN tbl_items ON tbl_sold.sold_item=tbl_items.item_name";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
        <div class="card p-3 mb-2" style="width: 13rem; height: 14.5rem ; border-radius:1rem;">
            <img src="<?php echo $row['item_image']; ?>" class="card-img-top" style="max-height: 110px">
            <div class="card-body p-0">
            <p class="card-text mb-0">Sold ID: <?php echo $row['sold_id']; ?></p>
                <h5 class="card-title mb-1"><?php echo $row['item_name']; ?></h5>
                <p class="card-text mb-1">Quantity <?php echo $row['item_quantity_type'] .': '.$row['sold_item_quantity'] ; ?></p>
            </div>
        </div>
<?php
    }
} else {
    // If no rows are found
    echo "No items found.";
}

    }

    public function delete($id, $table_name) {
        $conn = $this->db->getConnection();
        $sql = "DELETE FROM $table_name WHERE sold_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

 public function apply(){
    $conn = $this->db->getConnection();
    // Assuming connection is successful and $conn is your database connection
    
    // Fetch total sold quantities for each item
    $sql = "SELECT sold_item, SUM(sold_item_quantity) AS total_sold_quantity FROM tbl_sold GROUP BY sold_item";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Loop through each sold item
        while($row = $result->fetch_assoc()) {
            $soldItem = $row["sold_item"];
            $totalSoldQuantity = $row["total_sold_quantity"];
    
            // Update tbl_items to subtract sold quantities
            $updateSql = "UPDATE tbl_items SET item_quantity = item_quantity - ? WHERE item_name = ?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("is", $totalSoldQuantity, $soldItem); // 'i' for integer, 's' for string
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
            } else {
                echo "No update needed or item not found for item: $soldItem\n";
            }
            $stmt->close();
        }
    } else {
        echo "No sold items found.";
    }

 }

 public function truncateTblSold(){
    $conn = $this->db->getConnection();
    $truncateSql = "TRUNCATE TABLE tbl_sold";
    $truncateResult = $conn->query($truncateSql);
    
    if ($truncateResult) {
        echo "tbl_sold table truncated successfully.";
    } else {
        echo "Error truncating tbl_sold table: " . $conn->error;
    }
 }

 public function storeToHistory(){
        $conn = $this->db->getConnection();
        
        // Assuming connection is successful and $conn is your database connection
        
        // Insert sold items into tbl_sold_history with timestamp
        $sql = "INSERT INTO tbl_sold_history (sold_item, sold_quantity, sold_date)
                SELECT sold_item, sold_item_quantity, NOW() FROM tbl_sold";
        $result = $conn->query($sql);
        
        if ($result) {
            echo "Sold items moved to history successfully.";
        } else {
            echo "Error moving sold items to history: " . $conn->error;
        }
        
       
 }
 public function displaySoldHistoryByTimestamp() {
    $conn = $this->db->getConnection();
    
    // Query to retrieve data from tbl_sold_history
    $sql = "SELECT sold_id as ID, sold_item as Item, sold_quantity as Quantity, DATE_FORMAT(sold_date, '%Y-%m-%d') AS Date_Sold, DATE_FORMAT(sold_date, '%H:%i:%s') AS Time_Sold
            FROM tbl_sold_history 
            ORDER BY sold_date DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Output table header
        echo '<div class="table-responsive history" style="height: 75vh; overflow-y: auto;">';
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="table-light" style="position: sticky; top: 0;">';
        echo '<tr><th>ID</th><th>Item</th><th>Quantity</th><th>Date Sold</th><th>Time Sold</th></tr>';
        echo '</thead>';
        echo '<tbody class="text-light">';
        
        // Output data rows
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['ID'] . "</td>";
            echo "<td>" . $row['Item'] . "</td>";
            echo "<td>" . $row['Quantity'] . "</td>";
            echo "<td>" . $row['Date_Sold'] . "</td>";
            echo "<td>" . $row['Time_Sold'] . "</td>";
            echo "</tr>";
        }
        
        // Close table body and table
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo "No sales history found.";
    }
    
    $conn->close();
}

}
