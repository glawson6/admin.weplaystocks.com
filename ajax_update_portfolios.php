<?php
include_once 'init.php';

$globalVarsController = new GlobalVariablesController();
$transactionMapper = new TransactionMapper();
$priceMapper = new PriceMapper();
$portfolioMapper = new PortfolioMapper();
$contestMapper = new ContestMapper();

$hasRight = FALSE;
$loginUser = $_SESSION['loginSession'];
if ($loginUser) {
    $accessRight = $globalVarsController->GetAccessRight($loginUser);
    $hasRight = $accessRight->getCanUpdateAllOrganizations();
}
if (!$hasRight) {
    exit();
}


// Need interest rate for adding interest to portfolios
$globalVars = $globalVarsController->GetGlobalVariables();

// Update recent transactions (new stock prices)
$recentTransactions = $transactionMapper->GetRecentTransactions();
foreach ($recentTransactions as $transaction) {
    $prices = $priceMapper->GetPriceBySym($transaction->getPriceObject());
    $price = $prices[0];
    $netCash = floatval($price->getOpen()) * $transaction->getShares();
    switch ($transaction->getType()) {
        case ApplicationKeyValues::$TRANSACTION_TYPE_BUY_STOCK:
            $netCash += $transaction->getCommission();
            break;
        case ApplicationKeyValues::$TRANSACTION_TYPE_SELL_STOCK:
            $netCash -= $transaction->getCommission();
            break;
        default:
            break;
    }
    $transaction->setPrice($price->getId());
    $transaction->setNetCash($netCash);
    print_r($transaction);
    $transactionMapper->UpdateTransaction($transaction);
}

// Update portfolios (new stock prices, today's interest)
$portfolios = $portfolioMapper->GetAllPortfolios();
foreach ($portfolios as $portfolio) {
    $contest = $contestMapper->GetContestById($portfolio->getContest());
    $commission = $contest->getCommission();

    $cashAvailable = $portfolio->getCashBegin();
    $holdings = array();
    $holdingValue = 0;
    $commissionSum = 0;
    $transactions = $transactionMapper->GetTransactionsByPortfolio($portfolio->getId());
    if (!$transactions) $transactions = array();
    foreach ($transactions as $transaction) {
        $price = $transaction->getPriceObject();
        switch ($transaction->getType()) {
            case ApplicationKeyValues::$TRANSACTION_TYPE_BUY_STOCK:
                $cashAvailable -= $transaction->getNetCash();
                if ($holdings[$price->getSym()]) {
                    $holdings[$price->getSym()] += $transaction->getShares();
                } else {
                    $holdings[$price->getSym()] = $transaction->getShares();
                }
                break;
            case ApplicationKeyValues::$TRANSACTION_TYPE_SELL_STOCK:
                $cashAvailable += $transaction->getNetCash();
                if ($holdings[$price->getSym()]) {
                    $holdings[$price->getSym()] -= $transaction->getShares();
                } else {
                    $holdings[$price->getSym()] = -$transaction->getShares();
                }
                break;
            default:
                break;
        }
        $commissionSum += $commission;
    }
    foreach ($holdings as $sym => $shares) {
        if ($shares < 0) {
            echo "ERROR: portfolio " . $portfolio->getId() . " has less than zero shares of stock $sym";
            continue;
        }
        $price = new Price();
        $price->setSym($sym);
        $prices = $priceMapper->GetPriceBySym($price);
        $price = $prices[0];
        $holdingValue += floatval($price->getOpen()) * $shares;
    }
    $interest = ($globalVars->getInterestRate() / 365) * $cashAvailable;
    $portfolio->setInterest($portfolio->getInterest() + $interest);
    $portfolio->setCashAvailable($cashAvailable + $portfolio->getInterest());
    $portfolio->setPortfolioValue($portfolio->getCashAvailable() + $holdingValue - $commissionSum);
    $portfolioMapper->UpdatePortfolio($portfolio);
}
?>
