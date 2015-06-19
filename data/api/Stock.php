<?php

class Stock {
    private $id;
    private $sym;
    private $date;
    private $exchg;
    private $cusip;
    private $secType;
    private $gicsCode;
    private $sectCode;
    private $catCode;
    private $index;
    private $coName;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getSym() {
        return $this->sym;
    }

    public function setSym($sym) {
        $this->sym = $sym;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getExchg() {
        return $this->exchg;
    }

    public function setExchg($exchg) {
        $this->exchg = $exchg;
    }

    public function getCusip() {
        return $this->cusip;
    }

    public function setCusip($cusip) {
        $this->cusip = $cusip;
    }

    public function getSecType() {
        return $this->secType;
    }

    public function setSecType($secType) {
        $this->secType = $secType;
    }

    public function getGicsCode() {
        return $this->gicsCode;
    }

    public function setGicsCode($gicsCode) {
        $this->gicsCode = $gicsCode;
    }

    public function getSectCode() {
        return $this->sectCode;
    }

    public function setSectCode($sectCode) {
        $this->sectCode = $sectCode;
    }

    public function getCatCode() {
        return $this->catCode;
    }

    public function setCatCode($catCode) {
        $this->catCode = $catCode;
    }

    public function getIndex() {
        return $this->index;
    }

    public function setIndex($index) {
        $this->index = $index;
    }

    public function getCoName() {
        return $this->coName;
    }

    public function setCoName($coName) {
        $this->coName = $coName;
    }


}

?>
