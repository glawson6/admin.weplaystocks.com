<?php

class TransactionController extends BaseController {

    public function BuyStock() {
        try {
            global $errors;
            $transactionMapper = new TransactionMapper();
            $portfolioController = new PortfolioController();
            $portfolioMapper = new PortfolioMapper();
            $contestMapper = new ContestMapper();
            $priceMapper = new PriceMapper();

            $transaction = new Transaction();
            $transaction->setPrice(trim($_REQUEST['price']));
            $transaction->setShares(intval(trim($_REQUEST['buy_shares'])));
            if (!$this->ValidateTransactionInfo($transaction)) return FALSE;

            $portfolio = $portfolioController->GetOwnCurrentPortfolio();
            $contest = $contestMapper->GetContestById($portfolio->getContest());
            $transaction->setCommission($contest->getCommission());

            $price = new Price();
            $price->setId($transaction->getPrice());
            $price = $priceMapper->GetPriceById($price);
            if ($price) {
                if (floatval($price->getOpen()) < $contest->getMinimumStockPrice()) {
                    $errors['submit'] = 'Stock price below contest minimum';
                    return FALSE;
                }
            } else {
                $errors['price'] = 'Stock not found in database';
                return FALSE;
            }
            $transaction->setNetCash(floatval($price->getOpen()) * intval($transaction->getShares()));

            if ($portfolio->getCashAvailable() < ($transaction->getNetCash() + $transaction->getCommission())) {
                $errors['submit'] = 'Not enough cash available';
                return FALSE;
            }
            $portfolio->setCashAvailable($portfolio->getCashAvailable() - $transaction->getNetCash());
            $portfolioMapper->UpdatePortfolio($portfolio);

            $transaction->setType(ApplicationKeyValues::$TRANSACTION_TYPE_BUY_STOCK);
            $transaction->setPortfolio($portfolio->getId());

            return $transactionMapper->InsertTransaction($transaction);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function SellStock() {
        try {
            global $errors;
            $transactionMapper = new TransactionMapper();
            $priceMapper = new PriceMapper();
            $portfolioController = new PortfolioController();
            $contestMapper = new ContestMapper();

            $transaction = new Transaction();
            $transaction->setPrice(trim($_REQUEST['price']));
            $transaction->setShares(intval(trim($_REQUEST['sell_shares'])));

            $price = new Price();
            $price->setId($transaction->getPrice());
            $price = $priceMapper->GetPriceById($price);

            if (!$price) {
                $errors['price'] = 'Stock not found in database';
                return FALSE;
            } else if (!$this->ValidateTransactionInfo($transaction)) {
                if ($errors['shares']) {
                    $errors['shares_' . $price->getSym()] = $errors['shares'];
                }
                return FALSE;
            }

            $portfolio = $portfolioController->GetOwnCurrentPortfolio();
            $portfolioController->PopulateHoldingDetails($portfolio);

            $hasShares = FALSE;
            foreach ($portfolio->getHoldings() as $holding) {
                if ($holding->getSym() === $price->getSym() && $holding->getCount() >= $transaction->getShares()) {
                    $hasShares = TRUE;
                }
            }
            if (!$hasShares) {
                $errors['submit_' . $price->getSym()] = "You don't own this many shares";
                return FALSE;
            }

            $contest = $contestMapper->GetContestById($portfolio->getContest());
            $transaction->setCommission($contest->getCommission());
            $transaction->setNetCash(floatval($price->getOpen()) * intval($transaction->getShares()));
            $totalReturn = $transaction->getNetCash() - $transaction->getCommission();

            if ($totalReturn < 0 && ($portfolio->getCashAvailable() + $totalReturn < 0)) {
                $errors['submit_' . $price->getSym()] = 'Not enough cash available';
                return FALSE;
            } else {
                $portfolioMapper = new PortfolioMapper();
                $portfolio->setCashAvailable($portfolio->getCashAvailable() + $transaction->getNetCash());
                $portfolioMapper->UpdatePortfolio($portfolio);
            }

            $transaction->setType(ApplicationKeyValues::$TRANSACTION_TYPE_SELL_STOCK);
            $transaction->setPortfolio($portfolio->getId());

            return $transactionMapper->InsertTransaction($transaction);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function GetSortedTransactionsByPortfolio($portfolioId, $sort, $dir, $offset, $limit) {
        try {
            $transactionMapper = new TransactionMapper();
            return $transactionMapper->GetSortedTransactionsByPortfolio($portfolioId, $sort, $dir, $offset, $limit);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function ValidateTransactionInfo($transactionModel) {
        global $errors;
        $validation = new Validation();
        $transaction = $transactionModel;

        if ($validation->IsEmpty($transaction->getPrice())) {
            $errors['price'] = "Must designate a stock to trade!";
        }
        if ($validation->IsEmpty($transaction->getShares()) || 
                $validation->IsNotInteger($transaction->getShares()) || 
                intval($transaction->getShares()) <= 0) {
            $errors['shares'] = "Must enter positive integer for shares.";
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

?>
