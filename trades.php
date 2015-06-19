<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$portfolioController = new PortfolioController();
$portfolio = $portfolioController->GetOwnCurrentPortfolio();

if (isset($portfolio)) {
    $sort = $_REQUEST['sort'];
    $dir = $_REQUEST['dir'];
    $offset = $_REQUEST['offset'];
    $limit = $_REQUEST['limit'];
    if (!$sort) $sort = 'date';
    if (!$dir) $dir = 'desc';
    if (!$offset) $offset = 0;
    if (!$limit) $limit = 101;

    $headers = array(
        'date' => 'Trade Date',
        'sym' => 'Ticker',
        'shares' => '# of Shares',
        'price' => 'Stock Price',
        'type' => 'Buy or Sell',
        'amt' => 'Trade Amt'
    );

    $transactionController = new TransactionController();
    $transactions = $transactionController->GetSortedTransactionsByPortfolio($portfolio->getId(), $sort, $dir, $offset * $limit, $limit);
    ?><h3 class="trade-history-header">Your Trade History</h3><?php
    if (count($transactions)) {
        $transactionTypes = array();
        $transactionTypes[ApplicationKeyValues::$TRANSACTION_TYPE_BUY_STOCK] = 'Buy';
        $transactionTypes[ApplicationKeyValues::$TRANSACTION_TYPE_SELL_STOCK] = 'Sell';
        $transactionTypes[ApplicationKeyValues::$TRANSACTION_TYPE_SHORT_STOCK] = 'Short Stock';
        $transactionTypes[ApplicationKeyValues::$TRANSACTION_TYPE_SHORT_COVER] = 'Short Cover';
        if ($offset > 0) {
            ?><a href="trades.php?sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?>&offset=<?php echo $offset - 1; ?>">Previous Page</a><?php
        }
        if (count($transactions) > 100) {
            ?><a class="next-link" href="trades.php?sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?>&offset=<?php echo $offset + 1; ?>">Next Page</a><?php
        }
        ?>
        <table class="altrowstable trade-history" id="alternatecolor">
            <tr><?php
            foreach ($headers as $key => $label) {
                $selected = $sort == $key;
                ?>
                <th class="<?php if ($selected) { ?>selected<?php } else { ?>selectable<?php } ?>">
                    <a href="trades.php?sort=<?php echo $key; ?>&dir=<?php if ($selected) { echo $dir == 'asc' ? 'desc' : 'asc'; } else { ?>asc<?php } ?>">
                        <div class="arrow-wrapper">
                            <?php
                            echo $label;
                            if ($selected) {
                                ?><div class="arrow <?php echo $dir ?>"></div><?php
                            } else {
                                ?><div class="arrow asc"></div><div class="arrow desc"></div><?
                            }
                            ?>
                        </div>
                    </a>
                </th>
                <?php
            }
            ?></tr>
            <?php
            foreach (array_slice($transactions, 0, 100) as $transaction) {
                $price = $transaction->getPriceObject();
                ?>
                <tr>
                    <td><?php echo $transaction->getTradeDate(); ?></td>
                    <td><?php echo $price->getSym(); ?></td>
                    <td><?php echo $transaction->getShares(); ?></td>
                    <td><?php echo sprintf('%.2f', $price->getOpen()); ?></td>
                    <td><?php echo $transactionTypes[$transaction->getType()]; ?></td>
                    <td class="table-price"><?php echo sprintf('$%.2f', $transaction->getShares() * $price->getOpen()); ?></td>
                </tr>
                <?php
                if ($transaction->getCommission() > 0) {
                    ?>
                    <tr>
                        <td><?php echo $transaction->getTradeDate(); ?></td>
                        <td>Commission</td>
                        <td>-</td>
                        <td>-</td>
                        <td><?php echo $transactionTypes[$transaction->getType()]; ?></td>
                        <td class="table-price"><?php echo sprintf('$%.2f', $transaction->getCommission()); ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
        <?php
        if ($offset > 0) {
            ?><a href="trades.php?sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?>&offset=<?php echo $offset - 1; ?>">Previous Page</a><?php
        }
        if (count($transactions) > 100) {
            ?><a class="next-link" href="trades.php?sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?>&offset=<?php echo $offset + 1; ?>">Next Page</a><?php
        }
    } else {
        echo 'No records found.';
    }
} else {
?>

You are not a part of any current contest.

<?php
}

$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Trades";
include 'master.php';
?>
