<?php
class Contest {
    private $id;
    private $startDate;
    private $endDate;
    private $owner;
    private $ownerObject;
    private $org;
    private $name;
    private $commission;
    private $cashBegin;
    private $minStockPrice;
    private $notes;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
    }

    public function getOwnerObject() {
        return $this->ownerObject;
    }

    public function setOwnerObject($ownerObject) {
        $this->ownerObject = $ownerObject;
    }

    public function getOrganization() {
        return $this->org;
    }

    public function setOrganization($org) {
        $this->org = $org;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getCommission() {
        return $this->commission;
    }

    public function setCommission($commission) {
        $this->commission = $commission;
    }

    public function getCashBegin() {
        return $this->cashBegin;
    }

    public function setCashBegin($cashBegin) {
        $this->cashBegin = $cashBegin;
    }

    public function getMinimumStockPrice() {
        return $this->minStockPrice;
    }

    public function setMinimumStockPrice($minStockPrice) {
        $this->minStockPrice = $minStockPrice;
    }

    public function getNotes() {
        return $this->notes;
    }

    public function setNotes($notes) {
        $this->notes = $notes;
    }
}
?>
