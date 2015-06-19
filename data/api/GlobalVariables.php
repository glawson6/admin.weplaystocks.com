<?php
class GlobalVariables {
    private $id;
    private $systemName;
    private $systemDomain;
    private $systemEmail;
    private $interestRate;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getSystemName() {
        return $this->systemName;
    }

    public function setSystemName($systemName) {
        $this->systemName = $systemName;
    }

    public function getSystemDomain() {
        return $this->systemDomain;
    }

    public function setSystemDomain($systemDomain) {
        $this->systemDomain = $systemDomain;
    }

    public function getSystemEmail() {
        return $this->systemEmail;
    }

    public function setSystemEmail($systemEmail) {
        $this->systemEmail = $systemEmail;
    }

    public function getInterestRate() {
        return $this->interestRate;
    }

    public function setInterestRate($interestRate) {
        $this->interestRate = $interestRate;
    }
}
?>
