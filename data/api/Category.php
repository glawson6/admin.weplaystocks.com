<?php

class Category {
    private $id;
    private $catCode;
    private $catDesc;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCatCode() {
        return $this->catCode;
    }

    public function setCatCode($catCode) {
        $this->catCode = $catCode;
    }

    public function getCatDesc() {
        return $this->catDesc;
    }

    public function setCatDesc($catDesc) {
        $this->catDesc = $catDesc;
    }


}

?>
