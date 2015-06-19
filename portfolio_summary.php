<?php
global $portfolio;
global $includeHoldingDetails;
?>

<div id="portfolio-summary">
    <table class="altrowstable">
    <tr class="long-header">
        <th colspan="2">Your Portfolio Summary</th>
    </tr>
    <tr>
        <td class="table-label">Starting Value/Cash:</td>
        <td class="table-price"><?php echo $portfolio->getCashBegin(); ?></td>
    </tr>
    <?php if ($includeHoldingDetails) {
        foreach ($portfolio->getHoldings() as $holding) {
            $sym = $holding->getSym();
            ?>
            <tr>
                <td class="table-label"><a href="#stock-history-<?php echo $sym; ?>"><?php echo $sym; ?></a></td>
                <td class="table-price"><?php echo sprintf('%.2f', $holding->getWorth()); ?></td>
            </tr>
            <?php
        }
    } ?>
    <tr>
        <td class="table-label">Total Holdings:</td>
        <td class="table-price"><?php echo sprintf('%.2f', $portfolio->getTotalHoldings()); ?></td>
    </tr>
    <tr>
        <td class="table-label">Commission:</td>
        <td class="table-price"><?php echo sprintf('%.2f', $portfolio->getTotalCommission()); ?></td>
    </tr>
    <tr>
        <td class="table-label">Cash Available:</td>
        <td id="portfolio-cash-available" class="table-price"><?php echo $portfolio->getCashAvailable(); ?></td>
    </tr>
    <tr>
        <td class="table-label strong">TOTAL:</td>
        <td class="table-price"><?php
            //TODO when should this be updated?
            //echo $portfolio->getPortfolioValue();
            echo sprintf('%.2f', $portfolio->getTotalHoldings() + $portfolio->getCashAvailable() - $portfolio->getTotalCommission());
        ?></td>
    </tr>
    </table>
</div>
