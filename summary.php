<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$contestController = new ContestController();
$portfolioController = new PortfolioController();
$portfolio = $portfolioController->GetOwnCurrentPortfolio();

if (isset($portfolio)) {

$contest = $contestController->GetContestById($portfolio->getContest());
$owner = $contest->getOwnerObject();
$portfolios = $portfolioController->GetPortfoliosByContest($contest->getId());

function portfolioCmp($a, $b) {
    return $b->getPortfolioValue() - $a->getPortfolioValue();
}
usort($portfolios, 'portfolioCmp');
$rank = 0;
while ($portfolios[$rank++]->getId() !== $portfolio->getId());

?>
<div id="summary">
    <?php

    $messageController = new MessageController();
    $recentMessage = $messageController->GetRecentMessage($contest->getId());
    if ($recentMessage) {
        ?>
        <h3 class="summary-header">Last Instructor Message:</h3>
        <div id="summary-message">
            <div class="instructor-message"><?php echo $recentMessage->getMessage(); ?></div>
            <a href="messages.php" class="instructor-message-link">[ view all messages ]</a>
        </div>
        <?php
    }

    $portfolioController->PopulateHoldingDetails($portfolio);
    $includeHoldingDetails = FALSE;
    include 'portfolio_summary.php';

    ?>
    <div id="ranking">
        <table class="altrowstable">
        <tr class="long-header">
            <th>Your Contest Standing</th>
        </tr>
        <tr>
            <td>
                <div class="my-ranking"><?php echo $rank; ?></div>
                <div class="ranking-label">out of <?php echo count($portfolios); ?>! Keep it up!</div>
            </td>
        </tr>
        </table>
    </div>
    <div id="leaderboard">
        <table class="altrowstable">
        <tr class="long-header">
            <th>Contest Leaderboard</th>
        </tr>
        <?php
            foreach (array_slice($portfolios, 0, 5) as $i => $leadPortfolio) {
                $trader = $leadPortfolio->getTraderObject();
                ?>
                <tr>
                    <td><?php echo $i + 1; ?>. <?php 
                        echo $trader->getFirstName() . ' ' . $trader->getLastName();
                    ?> - <?php echo $leadPortfolio->getPortfolioValue(); ?></td>
                </tr>
                <?php
            }
        ?>
        </table>
    </div>
    <div class="clear"></div>

    <div id="stock-performance">
        <table class="altrowstable">
        <tr class="long-header">
            <th>Your Stock Performance</th>
        </tr>
        </table>
        <?php if ($portfolio->getHoldings() && count($portfolio->getHoldings()) > 0) {
            ?>
            <canvas id="stock-performance-canvas" width="460" height="170"></canvas>
            <div id="stock-performance-legend"></div>
            <?php
        } else {
            ?>
            <p>You don't have any stock holdings. Click <a href="stocks.php">here</a> to buy some.</p>
            <?php
        }?>
    </div>
    <div id="contest-summary">
        <table class="altrowstable">
        <tr class="long-header">
            <th colspan="2">Your Contest Summary</th>
        </tr>
        <tr>
            <td class="table-label">Contest Name:</td>
            <td><?php echo $contest->getName(); ?></td>
        </tr>
        <tr>
            <td class="table-label">Instructor:</td>
            <td><?php echo $owner->getLastName(); ?></td>
        </tr>
        <tr>
            <td class="table-label">Contest Date:</td>
            <td><?php echo date('M j, Y', strtotime($contest->getStartDate())) . ' - ' . date('M j, Y', strtotime($contest->getEndDate())); ?></td>
        </tr>
        </table>
    </div>
</div>

<?php if ($portfolio->getHoldings() && count($portfolio->getHoldings()) > 0) { ?>
<script src="contents/js/Chart.min.js"></script>
<script>
Chart.defaults.global.animation = false;
Chart.defaults.global.showTooltips = false;

var context = document.getElementById('stock-performance-canvas').getContext('2d');
var chart = new Chart(context);

var end = <?php echo time(); ?>;
var start = end - (2 * 7 * 24 * 60 * 60);

$.get('json_stock_performance.php?start=' + start, function(data) {
    for (var i = 0; i < data.datasets.length; ++i) {
        var color = '#';
        for (var j = 0; j < 3; ++j) color += Math.floor(16 + Math.random() * 200).toString(16);
        data.datasets[i].strokeColor = color;
    }
    var curChart = chart.Line(data,{
        bezierCurve: false,
        datasetFill: false,
        pointDot: false,
        legendTemplate : "<ul><% for (var i=0; i<datasets.length; i++){%><li style=\"border-color:<%=datasets[i].strokeColor%>\"><%=datasets[i].label%></li><%}%></ul>"
    });
    $('#stock-performance-legend').html(curChart.generateLegend());
}, 'json');
</script>
<?php } ?>

<?php
} else {
?>

You are not a part of any current contest.

<?php
}

$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Summary";
include 'master.php';
?>
