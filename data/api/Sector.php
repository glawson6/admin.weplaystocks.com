<?php


class Sector {
    private $id;
    private $sectCode;
    private $sectDesc;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getSectCode() {
        return $this->sectCode;
    }

    public function setSectCode($sectCode) {
        $this->sectCode = $sectCode;
    }

    public function getSectDesc() {
        return $this->sectDesc;
    }

    public function setSectDesc($sectDesc) {
        $this->sectDesc = $sectDesc;
    }


}

?>
