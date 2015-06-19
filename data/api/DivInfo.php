<?php
class DivInfo {
    private $id;
    private $sym;
    private $divShare;
    private $divYld;
    private $divPayDate;
    private $divXDate;
    
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

    public function getDivShare() {
        return $this->divShare;
    }

    public function setDivShare($divShare) {
        $this->divShare = $divShare;
    }

    public function getDivYld() {
        return $this->divYld;
    }

    public function setDivYld($divYld) {
        $this->divYld = $divYld;
    }

    public function getDivPayDate() {
        return $this->divPayDate;
    }

    public function setDivPayDate($divPayDate) {
        $this->divPayDate = $divPayDate;
    }

    public function getDivXDate() {
        return $this->divXDate;
    }

    public function setDivXDate($divXDate) {
        $this->divXDate = $divXDate;
    }


}

?>
