<?php
class Transaction {
    private $id;
    private $portfolio;
    private $shares;
    private $price;
    private $priceObject;
    private $type;
    private $commission;
    private $netCash;
    private $shortPrice;
    private $tradeDate;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getPortfolio() {
        return $this->portfolio;
    }

    public function setPortfolio($portfolio) {
        $this->portfolio = $portfolio;
    }

    public function getShares() {
        return $this->shares;
    }

    public function setShares($shares) {
        $this->shares = $shares;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getPriceObject() {
        return $this->priceObject;
    }

    public function setPriceObject($priceObject) {
        $this->priceObject = $priceObject;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getCommission() {
        return $this->commission;
    }

    public function setCommission($commission) {
        $this->commission = $commission;
    }

    public function getNetCash() {
        return $this->netCash;
    }

    public function setNetCash($netCash) {
        $this->netCash = $netCash;
    }

    public function getShortPrice() {
        return $this->shortPrice;
    }

    public function setShortPrice($shortPrice) {
        $this->shortPrice = $shortPrice;
    }

    public function getTradeDate() {
        return $this->tradeDate;
    }

    public function setTradeDate($tradeDate) {
        $this->tradeDate = $tradeDate;
    }
}
?>
