<?php
class Price {
    private $id;
    private $sym;
    private $stockObject;
    private $prevClos;
    private $open;
    private $WkHi;
    private $WkLo;
    private $mktCap;
    private $avgDaiVol;
    private $date;
    private $time;
    private $isExistInSymbleTable;
    private $isExistInCompareFile;
    private $isDifferenceWithCompareData;
    private $symId;
    private $isMissingDate;
    private $isExcessiveData;
    private $isNewData;



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

    public function getStockObject() {
        return $this->stockObject;
    }

    public function setStockObject($stockObject) {
        $this->stockObject = $stockObject;
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

    public function getIsExistInCompareFile() {
        return $this->isExistInCompareFile;
    }

    public function setIsExistInCompareFile($isExistInCompareFile) {
        $this->isExistInCompareFile = $isExistInCompareFile;
    }

    public function getIsDifferenceWithCompareData() {
        return $this->isDifferenceWithCompareData;
    }

    public function setIsDifferenceWithCompareData($isDifferenceWithCompareData) {
        $this->isDifferenceWithCompareData = $isDifferenceWithCompareData;
    }
    public function getSymId() {
        return $this->symId;
    }

    public function setSymId($symId) {
        $this->symId = $symId;
    }

    public function getIsMissingDate() {
        return $this->isMissingDate;
    }

    public function setIsMissingDate($isMissingDate) {
        $this->isMissingDate = $isMissingDate;
    }

    public function getIsExcessiveData() {
        return $this->isExcessiveData;
    }

    public function setIsExcessiveData($isExcessiveData) {
        $this->isExcessiveData = $isExcessiveData;
    }

    public function getIsNewData() {
        return $this->isNewData;
    }

    public function setIsNewData($isNewData) {
        $this->isNewData = $isNewData;
    }



}

?>
