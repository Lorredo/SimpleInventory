<div class="items shadow-sm">
            <?php

            $item->getProductInfo();
            ?>
</div>


<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="processSoldItem.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStockModalLabel">Add Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select form-select-lg mb-3" name="add_stock" aria-label=".form-select-lg example">
                        <option selected>Select Stock to be added</option>
                        <option value="Hita">Hita</option>
                        <option value="Pecho">Pecho</option>
                        <option value="Liempo">Liempo</option>
                        <option value="Barbecue Pork">Barbecue Pork</option>
                        <option value="Ground Pork">Ground Pork</option>
                        <option value="Mineral Water">Mineral Water</option>
                        <option value="Spices">Spices</option>
                        <option value="Mantika">Mantika</option>
                        <option value="Calamansi">Calamansi</option>
                        <option value="Toyo">Toyo</option>
                        <option value="SIli">SIli</option>
                    </select>
                    <label for="quantity">Stock Quantity</label>
                    <input type="number" name="add_quantity" id="quantity" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
