
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Inventory Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <style>
        body{
            height: 100vh;overflow: hidden;
        }
        .card-img-top{
            max-height: 88.53px;
        }

        .items, .sold{
            display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 16px;
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px 10px;
    overflow-y: auto;
    height: 75vh;  
        }
        .history{
            overflow-y: auto;
    height: auto;  
        }
        .items::-webkit-scrollbar-track, .history::-webkit-scrollbar-track, .sold::-webkit-scrollbar-track {
    border: 5px solid white;
    border-color: transparent;
    background-color: none;
}

.items::-webkit-scrollbar, .history::-webkit-scrollbar, .sold::-webkit-scrollbar {
    width: 15px;
    background-color: #dfe6e9;
    border-radius: 10px;
}

.items::-webkit-scrollbar-thumb, .history::-webkit-scrollbar-thumb, .sold::-webkit-scrollbar-thumb {
    background-color: #343a40;
    border-radius: 10px;
}
    </style>
</head>
<body>

    <?php
    require_once 'crud.php';
    require_once 'db_connection.php';
    $db = new Database("localhost", "root", "", "db_manginasal");

// Create an instance of the Product class and inject the database connection
$item = new Item($db);?>

    <div
        class="container-fluid bg-warning text-success p-3"
    >
    <div style="width: 10px; height: 10px; margin-top: 0; margin-left: 40px; margin-bottom: 0; position: in-line"> 
        <img src="mang_inasal.png" alt="logo">
    </div>

        <h1 class="text-danger text-center fw-bold fs-2" style="font-family:Impact, fantasy; letter-spacing:.1rem;">MANG INASAL INVENTORY MANAGEMENT SYSTEM</h1>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 bg-success p-5 ">
              
                <ul  class="list-group text-center fs-3" id="myList">
                <li id="item" onclick="toggle()" class="list-group-item list-group-item-action list-group-item-success mb-5 rounded-1rem active pointer fs-5"  style="border-radius: 1rem; cursor:pointer;">Stocks</li>
       
                <li id="sold" onclick="toggle()" class="list-group-item list-group-item-action list-group-item-success mb-5 rounded-1rem pointer fs-5"  style="border-radius: 1rem; cursor:pointer;">Sold</li>
                <li id="history" onclick="toggle()" class="list-group-item list-group-item-action list-group-item-success mb-5 rounded-1rem pointer fs-5"  style="border-radius: 1rem; cursor:pointer;">Sold History</li>      
                </ul>
            </div>
            
            <div id="item-display" class="col-10 bg-danger px-5 p-2 position-relative" style="height: 85.4vh; ">
    <div class="row pb-5 position-relative"> 
        
        <?php
                include 'items.php';
            ?>
    </div>
    <div class=" d-flex mb-3 justify-content-center text-align-center">
        <button type="button" class="btn btn-warning btn-lg position-absolute" data-bs-toggle="modal" data-bs-target="#addStockModal" style="right:45%; bottom:5px;">Add Stocks</button>
    </div>
    </div>
            <div id="sold-display" class="col-10 bg-danger p-4 position-relative" style="display: none; height: 85.4vh;">
    <div class=" row pb-5" > 
    <div class="container"><p class="h3 text-body-secondary" style="text-shadow: 1px 1px 2px white; ">Daily Sold</p></div>
            <?php
                include 'sold.php';
                ?>
            </div>
            <div class="position-absolute d-flex mb-2 justify-content-center text-align-center" style="right:35%;  bottom: 0;">
<div class="mx-3">
    <button type="button" class="btn-plus bg-primary border-white" data-bs-toggle="modal" data-bs-target="#insertModal"><i class="fas fa-plus"></i>
</button>
</div>
<div class="mx-3">
    <button type="button" class="btn-plus bg-success border-white" data-bs-toggle="modal" data-bs-target="#updateModal"><i class="fas fa-edit"></i>
</button>
</div>
<div class="mx-3">
    <button type="button" class="btn-plus bg-danger border-white" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fas fa-trash"></i>
</button>
</div>
<form method="post" action="processSoldItem.php">
        <div class="mx-3" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="top" title="Apply Changes" data-bs-content="Clicking Apply will subtract the amount of quantity in sold to the quantity of items in Stocks">
            <button type="submit" name="apply-btn" class="btn-plus bg-warning border-white" data-bs-toggle="modal" data-bs-target="#popModal">>Apply</button>
        </div>
    </form>
</div>
    </div>
    <div id="history-display" class="col-10 bg-danger p-3 position-relative" style="display: none;">
    <div class="row"> 
        <div class="container"><p class="h3 text-body-secondary" style="text-shadow: 1px 1px 2px white; ">Daily Sold History</p></div>
        <?php include 'soldHistory.php'; 
       
        ?>
    </div>
</div>


<script>

    
    document.addEventListener('DOMContentLoaded', function() {
    var listItems = document.querySelectorAll('#myList li');
    var displayDivs = {
        'item': document.getElementById('item-display'),
        'sold': document.getElementById('sold-display'),
        'history': document.getElementById('history-display')
    };

    function toggleDisplay(tabId) {
        for (var tab in displayDivs) {
            if (tab === tabId) {
                listItems.forEach(function(item) {
                    if (item.id === tabId) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
                displayDivs[tab].style.display = "block";
            } else {
                displayDivs[tab].style.display = "none";
            }
        }
    }

    listItems.forEach(function(item) {
        item.addEventListener('click', function(event) {
            var clickedTab = this.id;
            toggleDisplay(clickedTab);
            localStorage.setItem('activeTab', clickedTab);
        });
    });

    // Set active tab based on localStorage or default to 'item'
    var activeTab = localStorage.getItem('activeTab') || 'item';
    toggleDisplay(activeTab);
});

</script>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>