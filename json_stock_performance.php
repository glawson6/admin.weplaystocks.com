<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$portfolioController = new PortfolioController();
$priceController = new PriceController();

$start = $_GET['start'];

$portfolio = $portfolioController->GetOwnCurrentPortfolio();
$portfolioController->PopulateHoldingDetails($portfolio);
if (!$portfolio->getHoldings() || count($portfolio->getHoldings()) <= 0) {
    exit();
}

$prices = array();
$labels = array();
$datasets = array();
foreach ($portfolio->getHoldings() as $holding) {
    $sym = $holding->getSym();
    $datasets[] = array(
        'label' => $sym,
        'data' => array()
    );
    $prices[$sym] = array();
    $curPrices = $priceController->GetPriceBySymName($sym);
    $i = 0;
    $price = $curPrices[$i++];
    while ($price && strtotime($price->getDate()) > $start) {
        if (count($labels) < $i) $labels[] = date('n/j', strtotime($price->getDate()));
        $prices[$sym][] = floatval($price->getOpen());
        $price = $curPrices[$i++];
    }
    if ($price) {
        $prices[$sym][] = floatval($price->getOpen());
    } else {
        array_pop($labels);
    }
    if (count($prices[$sym]) <= 0) {
        array_pop($datasets);
    }
}
$labels = array_reverse($labels);

$i = 0;
while ($i < count($datasets)) {
    $dataset = $datasets[$i];
    $priceList = array_reverse($prices[$dataset['label']]);
    $index = 100;
    $j = 0;
    $prevPrice = $priceList[$j++];
    while ($j < count($priceList)) {
        $change = ($priceList[$j] / $prevPrice) - 1;
        $prevPrice = $priceList[$j++];
        $index *= (1 + $change);
        $dataset['data'][] = $index;
    }
    $datasets[$i++] = $dataset;
}

echo json_encode(array(
    'labels' => $labels,
    'datasets' => $datasets
));
?>
