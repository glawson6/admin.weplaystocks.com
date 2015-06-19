<?php

class EarningInfo {
    private $id;
    private $sym;
    private $currYrE;
    private $nxtYrE;
    private $currPE;
    private $nxtYrPE;
    
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

    public function getCurrYrE() {
        return $this->currYrE;
    }

    public function setCurrYrE($currYrE) {
        $this->currYrE = $currYrE;
    }

    public function getNxtYrE() {
        return $this->nxtYrE;
    }

    public function setNxtYrE($nxtYrE) {
        $this->nxtYrE = $nxtYrE;
    }

    public function getCurrPE() {
        return $this->currPE;
    }

    public function setCurrPE($currPE) {
        $this->currPE = $currPE;
    }

    public function getNxtYrPE() {
        return $this->nxtYrPE;
    }

    public function setNxtYrPE($nxtYrPE) {
        $this->nxtYrPE = $nxtYrPE;
    }


}

?>
