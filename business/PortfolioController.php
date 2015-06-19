<?php

class PortfolioController extends BaseController {

    public function AddPortfolio($traderId, $contestId) {
        try {
            global $warnings;

            $userMapper = new UserMapper();
            $contestMapper = new ContestMapper();
            $trader = $userMapper->GetUserById($traderId);
            $contest = $contestMapper->GetContestById($contestId);
            if ($trader->getOrganization() !== $contest->getOrganization()) {
                $warnings['student_import'] .= '<div class="error">' . 
                    "Existing trader " . $trader->getEmail() . ": organization doesn't match contest organization." . 
                '</div>';
                return FALSE;
            }

            $portfolioMapper = new PortfolioMapper();
            $portfolio = new Portfolio();
            $portfolio->setTrader($traderId);
            $portfolio->setContest($contestId);
            $portfolio->setCashBegin($contest->getCashBegin());
            $portfolio->setCashAvailable($portfolio->getCashBegin());
            $portfolio->setInterest(0);
            $portfolio->setPortfolioValue($portfolio->getCashBegin());

            return $portfolioMapper->InsertPortfolio($portfolio);
        } catch (Exception $ex) {
            throw $ex->getTraceAsString();
        }
    }

    public function AddPortfoliosFromChecklist($allUsers, $contestId) {
        global $errors;
        if ($_REQUEST['sec_token'] != session_id()) {
            $errors['submit'] = 'Authorization failed';
            return FALSE;
        }

        $portfolioMapper = new PortfolioMapper();
        $newTraders = array();
        $deletedTraders = array();

        foreach ($allUsers as $user) {
            $userId = $user->getId();
            $wasTrader = in_array($contestId, $user->getContests());
            $isTrader = trim($_REQUEST['is_trader_' . $userId]);
            if (!$wasTrader && $isTrader) {
                $newTraders[] = $userId;
            } else if ($wasTrader && !$isTrader) {
                $deletedTraders[] = $userId;
            }
        }

        if (!$this->AddPortfolios($newTraders, $contestId)) return FALSE;

        foreach ($deletedTraders as $traderId) {
            $portfolioMapper->DeletePortfolioByTraderAndContest($traderId, $contestId);
        }

        return TRUE;
    }

    public function AddPortfolios($traderIds, $contestId) {
        foreach ($traderIds as $traderId) {
            if (!$this->AddPortfolio($traderId, $contestId)) return FALSE;
        }
        return TRUE;
    }

    public function GetPortfoliosByContest($contestId) {
        try {
            $portfolioMapper = new PortfolioMapper();
            return $portfolioMapper->GetPortfoliosByContest($contestId);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function GetOwnCurrentPortfolio() {
        try {
            $loginUser = $_SESSION['loginSession'];
            $portfolioMapper = new PortfolioMapper();
            $portfolios = $portfolioMapper->GetCurrentPortfoliosByTrader($loginUser->getId());
            if (count($portfolios) > 0) return $portfolios[0];
            return NULL;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function PopulateHoldingDetails($portfolio) {
        try {
            $transactionMapper = new TransactionMapper();
            $priceMapper = new PriceMapper();

            $holdings = array();
            $totalHoldings = 0;
            $commission = 0;

            $stocksHeld = array();
            $holdingCounts = array();
            $transactions = $transactionMapper->GetTransactionsByPortfolio($portfolio->getId());
            foreach ($transactions as $transaction) {
                $price = $transaction->getPriceObject();
                $sym = $price->getSym();
                $count = 0;
                if ($holdingCounts[$sym]) $count = $holdingCounts[$sym];
                switch ($transaction->getType()) {
                    case ApplicationKeyValues::$TRANSACTION_TYPE_BUY_STOCK:
                        $count += $transaction->getShares();
                        break;
                    case ApplicationKeyValues::$TRANSACTION_TYPE_SELL_STOCK:
                        $count -= $transaction->getShares();
                        break;
                    default:
                        break;
                }
                if (!in_array($sym, $stocksHeld)) $stocksHeld[] = $sym;
                $holdingCounts[$sym] = $count;
                $commission += $transaction->getCommission();
            }

            sort($stocksHeld);
            foreach ($stocksHeld as $sym) {
                $count = $holdingCounts[$sym];
                if ($count <= 0) continue;

                $holding = new Holding();
                $holding->setSym($sym);
                $holding->setCount($count);

                $latestPrice = new Price();
                $latestPrice->setSym($sym);
                $prices = $priceMapper->GetPriceBySym($latestPrice);
                $latestPrice = $prices[0];
                $holding->setWorth(floatval($latestPrice->getOpen()) * $count);

                $totalHoldings += $holding->getWorth();
                $holdings[] = $holding;
            }

            $portfolio->setHoldings($holdings);
            $portfolio->setTotalHoldings($totalHoldings);
            $portfolio->setTotalCommission($commission);

        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

?>
