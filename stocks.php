<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$msg = '';
$errors = array();

function getPriceNow($sym) {
    $ch = curl_init();
    $source = "http://download.finance.yahoo.com/d/quotes1.csv?s=$sym&f=l1";
    curl_setopt($ch, CURLOPT_URL, $source);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $data = curl_exec ($ch);
    curl_close ($ch);
    return $data;
}

$contestController = new ContestController();
$portfolioController = new PortfolioController();
$portfolio = $portfolioController->GetOwnCurrentPortfolio();

if (isset($portfolio)) {

$contest = $contestController->GetContestById($portfolio->getContest());

if (isset($_POST['submit'])) {
    $priceController = new PriceController();
    switch ($_POST['action']) {
        case 'stock_search':
            $prices = $priceController->GetPriceBySym();
            if (count($errors) == 0) {
                if ($prices) {
                    $price = $prices[0];
                    $price->setOpen(getPriceNow($price->getSym()));
                } else {
                    $prices = $priceController->GetPriceByStockCoName();
                    if (count($errors) == 0) {
                        if ($prices) {
                            $price = $prices[0];
                            $price->setOpen(getPriceNow($price->getSym()));
                        } else {
                            $errors['sym'] = ApplicationKeyValues::$MSG_SEARCH_SYM;
                        }
                    }
                }
                if (count($errors) == 0 && floatval($price->getOpen()) < $contest->getMinimumStockPrice()) {
                    $errors['submit'] = sprintf('Stock price is below contest minimum ($%.2f).', $contest->getMinimumStockPrice());
                }
            }
            break;
        case 'buy_stock':
            $transactionController = new TransactionController();
            if ($transactionController->BuyStock()) {
                $msg = 'Stock bought!';
                $portfolio = $portfolioController->GetOwnCurrentPortfolio();
            } else {
                $price = $priceController->GetPriceById(trim($_POST['price']));
                $price->setOpen(getPriceNow($price->getSym()));
            }
            break;
        case 'sell_stock':
            $transactionController = new TransactionController();
            if ($transactionController->SellStock()) {
                $msg = 'Stock sold!';
                $portfolio = $portfolioController->GetOwnCurrentPortfolio();
            }
            break;
        default:
            break;
    }
}

?>

<h3 class="stocks-header">Today is <?php echo date('l, M j, Y'); ?> (Project Day <?php
    echo ceil((time() - strtotime($contest->getStartDate())) / (60 * 60 * 24));
?>)</h3>

<div class="message"><?php if ($msg) { echo $msg; } ?></div>

<div id="search-stocks">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="hidden" name="action" value="stock_search">
        <div class="stock-search">
            <label for="sym" class="big-label">Search Stocks: </label>
            <input type="text" name="sym" id="sym" <?php if (isset($_REQUEST["sym"])) { ?>value="<?php echo $_REQUEST["sym"]; ?>"<?php } ?> />
            <input type="submit" name="submit" value="Go">
            <span class="error"><?php if (isset($errors) && isset($errors['sym'])) {
                echo $errors['sym'];
            } ?></span>
        </div>
    </form>

    <?php
    if (isset($price)) { 
        $stock = $price->getStockObject();
        ?>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
            <input type="hidden" name="action" value="buy_stock">
            <input type="hidden" name="price" value="<?php echo $price->getId(); ?>">
            <div class="price-co locked">
                <label class="big-label">Company / Ticker</label>
                <div><input type="text" value="<?php echo $stock->getCoName() . ' (' . $price->getSym() . ')'; ?>" disabled /></div>
            </div>
            <div class="price-detail locked">
                <label class="big-label">Price</label>
                <div><input type="text" id="buy-price" value="<?php echo $price->getOpen(); ?>" disabled /></div>
            </div>
            <div class="error"><?php if (isset($errors) && isset($errors['price'])) {
                echo $errors['price'];
            } ?></div>
            <div class="clear"></div>
            <div class="price-detail">
                <label class="big-label">How many shares do you want to buy?</label>
                <input type="text" name="buy_shares" id="buy-shares" value="<?php
                    if (isset($_REQUEST['buy_shares']) && $_REQUEST['action'] == 'buy_stock') {
                        echo $_REQUEST['buy_shares'];
                    } else {
                        ?>0<?php
                    }
                ?>" />
            </div>
            <div class="error"><?php if (isset($errors) && isset($errors['shares'])) {
                echo $errors['shares'];
            } ?></div>
            <div class="clear"></div>
            <div class="price-detail locked">
                <label>Subtotal</label>
                <input type="text" id="buy-subtotal" disabled />
            </div>
            <div class="clear"></div>
            <div class="price-detail locked">
                <label>Commission</label>
                <input type="text" id="commission" value="<?php echo $contest->getCommission(); ?>" disabled />
            </div>
            <div class="clear"></div>
            <div class="price-detail locked">
                <label>Total</label>
                <input type="text" id="buy-total" disabled />
            </div>
            <div class="clear"></div>
            <div class="price-detail">
                <span class="error" id="buy-error"></span>
                <input type="submit" id="buy-stock-submit" name="submit" value="BUY!" disabled />
            </div>
        </form>
        <script>
            var commission = parseFloat($('#commission').val());
            $('#buy-price').val(parseFloat($('#buy-price').val() || '0').toFixed(2));
            var updateTotal = function() {
                var subtotal = parseFloat($('#buy-price').val()) * (parseInt($('#buy-shares').val()) || 0);
                var curCommission = subtotal ? commission : 0;
                var total = subtotal + curCommission;
                $('#buy-subtotal').val(subtotal.toFixed(2));
                $('#commission').val(curCommission.toFixed(2));
                $('#buy-total').val(total.toFixed(2));
                var canBuy = total > 0;
                var buyError = '';
                if (total > parseFloat($('#portfolio-cash-available').text())) {
                    buyError = 'Not enough cash available.';
                    canBuy = false;
                }
                $('#buy-error').text(buyError);
                $('#buy-stock-submit').prop('disabled', !canBuy);
            };
            $('#buy-shares').keyup(updateTotal);
            $(document).ready(updateTotal);
        </script>
        <?php
    }
    ?>
</div>

<?php 
$portfolioController->PopulateHoldingDetails($portfolio);
$includeHoldingDetails = TRUE;
include 'portfolio_summary.php';
?>

<div class="clear"></div>
<?php
include 'portfolio_stock_histories.php';
?>

<?php
} else {
?>
You are not a part of any current contest.
<?php
}

$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Stocks";
include 'master.php';
?>
