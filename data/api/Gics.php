<?php

class Gics {
    private $id;
    private $gicsCode;
    private $gicsDesc;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getGicsCode() {
        return $this->gicsCode;
    }

    public function setGicsCode($gicsCode) {
        $this->gicsCode = $gicsCode;
    }

    public function getGicsDesc() {
        return $this->gicsDesc;
    }

    public function setGicsDesc($gicsDesc) {
        $this->gicsDesc = $gicsDesc;
    }


}

?>
