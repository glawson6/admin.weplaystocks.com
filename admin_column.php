<div id="content-left">
<?php
$loginSession = $_SESSION['loginSession'];
if (isset($loginSession)) {
    $userController = new UserController();
    $accessRight = new AccessRight();
    $accessRight = $userController->GetAccessRight($loginSession); // get access right

    if ($accessRight->getCanUpdateAllOrganizations()) {
        ?>

        <?php
        if (isset($_SESSION['submenu'])) { // check submenu session
//                            switch (unserialize($_SESSION['submenu'])) {
            switch ($_SESSION['submenu']) {
                case ApplicationKeyValues::$SUBMENU_STOCK://if session is stock submenu
                    ?>
                    <ul>
                        <li><a href="stock_review.php">Stock Review</a></li>
                    </ul>
                    <ul>
                        <li><a href="stock.php">Find Stock</a></li>
                    </ul>
                    <ul>
                        <li><a href="#">Edit Description</a></li>
                    </ul>
                    <?php
                    break;
            }
            ?>
             <ul>
                        <li><a href="index.php">Back</a></li>
                    </ul>
            <?php
            $_SESSION['submenu'] =NULL;//set session submenu null
        } else {
            ?>
            <ul>
                <li><a href="global_vars.php">Global Variables</a></li>
            </ul>
            <br />
            <ul>
                <li><a href="stock_review.php">Stock Review</a></li>
            </ul>
            <ul>
                <li><a href="search_sym.php">Ticker Search</a></li>
            </ul>
            <ul>
                <li><a href="earning.php">Review Earnings</a></li>
            </ul>
            <ul>
                <li><a href="div_infor.php">Review Div Infor</a></li>
            </ul>
            <br />
            <ul>
                <li><a href="data_tables.php">View Tables</a></li>
            </ul>
            <br />
            <ul>
                <li><a href="price_compare.php">Price Compare</a></li>
            </ul>
            <ul>
                <li><a href="test_price_compare.php">Compare Price Dates</a></li>
            </ul>

            <ul>
                <li><a href="price_detail.php">Review Daily Price</a></li>
            </ul>
            <br />
            <ul>
                <li><b>Manual File Upload</b></li>
            </ul>
            <ul>
                <li><a href="add_price.php">Add Daily Prices</a></li>
            </ul>
            <ul>
                <li><a href="add_stock_file.php">Add Stock Data</a></li>
            </ul>
            <ul>
                <li><a href="#">Add Dividend Data</a></li>
            </ul>
            <ul>
                <li><a href="#">Add Earning Data</a></li>
            </ul>
            <br />
            <ul>
                <li><b>----Price(feed)----</b></li>
            </ul>

            <ul>
                <li><a href="update_price.php">Update Daily Prices</a></li>
            </ul>
            <ul>
                <li><a href="price_url_detail.php">Review Daily Prices</a></li>
            </ul>
             <ul>
                <li><a href="auto_price_compare.php">Auto Compare</a></li>
            </ul>
            <ul>
                <li><a href="price_compare_by_url.php">Manual Compare</a></li>
            </ul>
        <?php
        }
    }
} ?>
</div>

