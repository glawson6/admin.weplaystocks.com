<?php
class User {
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $password;
    private $userType;
    private $userStatus;
    private $org;
    private $orgName;
    private $contests;
    private $createDate;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getUserType() {
        return $this->userType;
    }

    public function setUserType($userType) {
        $this->userType = $userType;
    }

    public function getUserStatus() {
        return $this->userStatus;
    }

    public function setUserStatus($userStatus) {
        $this->userStatus = $userStatus;
    }

    public function getOrganization() {
        return $this->org;
    }

    public function setOrganization($org) {
        $this->org = $org;
    }

    public function getOrganizationName() {
        return $this->orgName;
    }

    public function setOrganizationName($orgName) {
        $this->orgName = $orgName;
    }

    public function getContests() {
        return $this->contests;
    }

    public function setContests($contests) {
        $this->contests = $contests;
    }

    public function getCreateDate() {
        return $this->createDate;
    }

    public function setCreateDate($createDate) {
        $this->createDate = $createDate;
    }
}

?>
