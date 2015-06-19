<?php
class Holding {
    private $sym;
    private $count;
    private $worth;

    public function getSym() {
        return $this->sym;
    }

    public function setSym($sym) {
        $this->sym = $sym;
    }

    public function getCount() {
        return $this->count;
    }

    public function setCount($count) {
        $this->count = $count;
    }

    public function getWorth() {
        return $this->worth;
    }

    public function setWorth($worth) {
        $this->worth = $worth;
    }
}
?>
