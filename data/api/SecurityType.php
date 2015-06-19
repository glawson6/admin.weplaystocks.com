<?php

class SecurityType {
    private $id;
    private $secType;
    private $typeDesc;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getSecType() {
        return $this->secType;
    }

    public function setSecType($secType) {
        $this->secType = $secType;
    }

    public function getTypeDesc() {
        return $this->typeDesc;
    }

    public function setTypeDesc($typeDesc) {
        $this->typeDesc = $typeDesc;
    }


}

?>
