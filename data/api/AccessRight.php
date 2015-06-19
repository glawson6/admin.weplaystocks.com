<?php

class AccessRight {

    private $canUpdateAllOrgs;
    private $canUpdateOwnOrg;
    private $canUpdateOwnContests;
    private $canUpdateAdmins;
    private $canUpdateTeachers;
    private $canUpdateStudents;
    private $canUpdateOwnProfile;

    public function getCanUpdateAllOrganizations() {
        return $this->canUpdateAllOrgs;
    }

    public function setCanUpdateAllOrganizations($canUpdateAllOrgs) {
        $this->canUpdateAllOrgs = $canUpdateAllOrgs;
    }
    
    public function getCanUpdateOwnOrganization() {
        return $this->canUpdateOwnOrg;
    }

    public function setCanUpdateOwnOrganization($canUpdateOwnOrg) {
        $this->canUpdateOwnOrg = $canUpdateOwnOrg;
    }
    
    public function getCanUpdateOwnContests() {
        return $this->canUpdateOwnContests;
    }

    public function setCanUpdateOwnContests($canUpdateOwnContests) {
        $this->canUpdateOwnContests = $canUpdateOwnContests;
    }

    public function getCanUpdateAdmins() {
        return $this->canUpdateAdmins;
    }

    public function setCanUpdateAdmins($canUpdateAdmins) {
        $this->canUpdateAdmins = $canUpdateAdmins;
    }

    public function getCanUpdateTeachers() {
        return $this->canUpdateTeachers;
    }

    public function setCanUpdateTeachers($canUpdateTeachers) {
        $this->canUpdateTeachers = $canUpdateTeachers;
    }

    public function getCanUpdateStudents() {
        return $this->canUpdateStudents;
    }

    public function setCanUpdateStudents($canUpdateStudents) {
        $this->canUpdateStudents = $canUpdateStudents;
    }

    public function getCanUpdateOwnProfile() {
        return $this->canUpdateOwnProfile;
    }

    public function setCanUpdateOwnProfile($canUpdateOwnProfile) {
        $this->canUpdateOwnProfile = $canUpdateOwnProfile;
    }
    
}

?>
