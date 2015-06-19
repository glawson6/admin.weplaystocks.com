<?php
class Portfolio {
    private $id;
    private $trader;
    private $traderObject;
    private $contest;
    private $cashBegin;
    private $cashAvailable;
    private $interest;
    private $portfolioValue;
    private $holdings;
    private $totalHoldings;
    private $totalCommission;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTrader() {
        return $this->trader;
    }

    public function setTrader($trader) {
        $this->trader = $trader;
    }

    public function getTraderObject() {
        return $this->traderObject;
    }

    public function setTraderObject($traderObject) {
        $this->traderObject = $traderObject;
    }

    public function getContest() {
        return $this->contest;
    }

    public function setContest($contest) {
        $this->contest = $contest;
    }

    public function getCashBegin() {
        return $this->cashBegin;
    }

    public function setCashBegin($cashBegin) {
        $this->cashBegin = $cashBegin;
    }

    public function getCashAvailable() {
        return $this->cashAvailable;
    }

    public function setCashAvailable($cashAvailable) {
        $this->cashAvailable = $cashAvailable;
    }

    public function getInterest() {
        return $this->interest;
    }

    public function setInterest($interest) {
        $this->interest = $interest;
    }

    public function getPortfolioValue() {
        return $this->portfolioValue;
    }

    public function setPortfolioValue($portfolioValue) {
        $this->portfolioValue = $portfolioValue;
    }

    public function getHoldings() {
        return $this->holdings;
    }

    public function setHoldings($holdings) {
        $this->holdings = $holdings;
    }

    public function getTotalHoldings() {
        return $this->totalHoldings;
    }

    public function setTotalHoldings($totalHoldings) {
        $this->totalHoldings = $totalHoldings;
    }

    public function getTotalCommission() {
        return $this->totalCommission;
    }

    public function setTotalCommission($totalCommission) {
        $this->totalCommission = $totalCommission;
    }
}
?>
