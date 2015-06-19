<?php

class PriceErrorInfor {
    private $id;
    private $sym;
    private $curDate;
    private $comDate;
    private $status;
    
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

    public function getCurDate() {
        return $this->curDate;
    }

    public function setCurDate($curDate) {
        $this->curDate = $curDate;
    }

    public function getComDate() {
        return $this->comDate;
    }

    public function setComDate($comDate) {
        $this->comDate = $comDate;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }


}

?>
