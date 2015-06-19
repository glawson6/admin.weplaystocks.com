<?php
global $portfolio;
global $contest;

if ($portfolio->getHoldings() && count($portfolio->getHoldings()) > 0) {

$stockController = new StockController();
$coDescriptionController = new CoDescriptionController();
$priceController = new PriceController();

?>

<div id="stock-list">
    <h3>Your Stocks</h3>
    <?php
    foreach ($portfolio->getHoldings() as $holding) {
        $sym = $holding->getSym();
        $stock = $stockController->FindStockBySym($sym);
        $coDescription = $coDescriptionController->GetCoDescriptionBySymName($sym);
        $prices = $priceController->GetPriceBySymName($sym);
        $priceToday = $prices[0];
        ?>
        <div class="stock-history" id="stock-history-<?php echo $sym; ?>">
            <div class="stock-history-title">
                <div class="stock-name">
                    <div class="stock-symbol"><?php echo $sym; ?></div>
                    <div class="stock-co-name"><?php echo $stock->getCoName(); ?></div>
                </div>
                <div class="price-today">
                    <div class="price-value" id="price-<?php echo $sym; ?>"><?php echo $priceToday->getOpen(); ?></div>
                    <div class="price-label">Today's Price</div>
                </div>
            </div>
            <div class="stock-history-content">
                <!--<div class="stock-co-description">
                    <h4>Description:</h4>
                    <p><?php echo strip_tags($coDescription->getCoDesc()); ?></p>
                </div>-->
                <table class="altrowstable">
                    <tr>
                        <?php
                        for ($i = 1; $i < 6; $i++) {
                            $price = $prices[$i];
                            if (!$price) continue;
                            ?><th>Day <?php
                                $rawDate = strtotime($price->getDate());
                                echo ceil(($rawDate - strtotime($contest->getStartDate())) / (60 * 60 * 24));
                                ?>: <?php
                                echo date('m/d/y', $rawDate);
                            ?></th><?php
                        }
                        ?>
                    </tr>
                    <tr>
                        <?php
                        for ($i = 1; $i < 6; $i++) {
                            $price = $prices[$i];
                            if (!$price) continue;
                            ?><td class="table-price"><?php echo $price->getOpen(); ?></td><?php
                        }
                        ?>
                    </tr>
                </table>
                <div class="holding-info">You own <span id="holding-count-<?php echo $sym; ?>"><?php
                        echo $holding->getCount();
                    ?></span> shares worth $<?php
                        echo sprintf('%.2f', $holding->getWorth());
                ?>.</div>
            </div>
            <div class="sell-form">
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                    <input type="hidden" name="action" value="sell_stock">
                    <input type="hidden" name="price" value="<?php echo $priceToday->getId(); ?>">
                    <table class="altrowstable">
                        <tr class="long-header"><th>SELL <?php echo $sym; ?></th></tr>
                        <tr><td>
                            <div class="price-detail">
                                <input type="text" name="sell_shares" id="sell-shares-<?php echo $sym; ?>" class="sell-shares" value="<?php
                                    if (isset($_REQUEST['sell_shares']) && $_REQUEST['price'] == $priceToday->getId()) {
                                        echo $_REQUEST['sell_shares'];
                                    } else {
                                        ?>0<?php
                                    }
                                ?>" />
                                <div class="error"><?php if (isset($errors) && isset($errors["shares_$sym"])) {
                                    echo $errors["shares_$sym"];
                                } ?></div>
                            </div>
                            <div class="clear"></div>
                            <div class="price-detail locked">
                                <input type="text" class="commission" id="commission-<?php echo $sym; ?>" value="<?php
                                    echo $contest->getCommission();
                                ?>" disabled />
                            </div>
                            <div class="clear"></div>
                            <div class="price-detail locked">
                                <input type="text" id="total-<?php echo $sym; ?>" disabled />
                            </div>
                        </td></tr>
                    </table>
                    <div class="price-detail">
                        <span class="error" id="error-<?php echo $sym; ?>"></span>
                        <input type="submit" id="sell-stock-submit-<?php echo $sym; ?>" name="submit" value="SELL" disabled />
                    </div>
                </form>
            </div>
            <div class="clear"></div>
        </div>
        <?php
    }
    ?>
    <script>
        var commission = parseFloat($('.commission').first().val());
        var updateSellTotal = function() {
            var sym = $(this).attr('id').split('-')[2];
            var count = parseInt($('#sell-shares-' + sym).val()) || 0;
            var subtotal = parseFloat($('#price-' + sym).text()) * count;
            var curCommission = subtotal ? commission : 0;
            var total = subtotal - curCommission;
            $('#commission-' + sym).val(curCommission.toFixed(2));
            $('#total-' + sym).val(total.toFixed(2));
            var canSell = total > 0;
            var sellError = '';
            if (count > parseInt($('#holding-count-' + sym).text())) {
                sellError = 'You don\'t own this many shares.';
                canSell = false;
            }
            $('#error-' + sym).text(sellError);
            $('#sell-stock-submit-' + sym).prop('disabled', !canSell);
        };
        $('.sell-shares').keyup(updateSellTotal);
        $('.sell-shares').each(updateSellTotal);
    </script>
</div>

<?php
}
?>
