<?php

class CurrentPrice {

  
    private $sym;
    private $prevClos;
    private $open;
    private $WkHi;
    private $WkLo;
    private $mktCap;
    private $avgDaiVol;
    private $date;
    private $time;
    private $isExistInSymbleTable;
    private $symId;
   


    public function getSym() {
        return $this->sym;
    }

    public function setSym($sym) {
        $this->sym = $sym;
    }

    public function getPrevClos() {
        return $this->prevClos;
    }

    public function setPrevClos($prevClos) {
        $this->prevClos = $prevClos;
    }

    public function getOpen() {
        return $this->open;
    }

    public function setOpen($open) {
        $this->open = $open;
    }

    public function getWkHi() {
        return $this->WkHi;
    }

    public function setWkHi($WkHi) {
        $this->WkHi = $WkHi;
    }

    public function getWkLo() {
        return $this->WkLo;
    }

    public function setWkLo($WkLo) {
        $this->WkLo = $WkLo;
    }

    public function getMktCap() {
        return $this->mktCap;
    }

    public function setMktCap($mktCap) {
        $this->mktCap = $mktCap;
    }

    public function getAvgDaiVol() {
        return $this->avgDaiVol;
    }

    public function setAvgDaiVol($avgDaiVol) {
        $this->avgDaiVol = $avgDaiVol;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getTime() {
        return $this->time;
    }

    public function setTime($time) {
        $this->time = $time;
    }

    public function getIsExistInSymbleTable() {
        return $this->isExistInSymbleTable;
    }

    public function setIsExistInSymbleTable($isExistInSymbleTable) {
        $this->isExistInSymbleTable = $isExistInSymbleTable;
    }

    public function getSymId() {
        return $this->symId;
    }

    public function setSymId($symId) {
        $this->symId = $symId;
    }
 


}

?>
