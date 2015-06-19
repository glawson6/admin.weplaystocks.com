<?php

class OrganizationController extends BaseController {

    public function AddOrganization() {
        try {
            global $errors;

            $org = new Organization();
            $org->setName(trim($_REQUEST['name']));

            if ($this->ValidateOrgInfo($org)) {
                $orgMapper = new OrganizationMapper();
                $insertId = $orgMapper->InsertOrganization($org);
                return $insertId != 0;
            } else {
                return FALSE;
            }
        } catch (Exception $ex) {
            throw $ex->getTraceAsString();
        }
    }

    public function GetAllOrganizations() {
        try {
            $organizationMapper = new OrganizationMapper();
            return $organizationMapper->GetOrganizations();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function GetOrganizationById($id) {
        try {
            $organizationMapper = new OrganizationMapper();
            return $organizationMapper->GetOrganizationById($id);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function UpdateOrganization() {
        try {
            $orgMapper = new OrganizationMapper();
            $org = new Organization();

            $id = trim($_REQUEST['id']);
            $name = trim($_REQUEST['name']);

            $org->setId($id);
            $org->setName($name);

            if (!$this->ValidateOrgInfo($org)) return FALSE;
            $orgMapper->UpdateOrganization($org);

            $loginUser = $_SESSION['loginSession'];
            if ($loginUser->getOrganization() == $id) {
                $org = $orgMapper->GetOrganizationById($id);
                $loginUser->setOrganizationName($org->getName());
                $_SESSION['loginSession'] = $loginUser;
            }
            return TRUE;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function SelectionMarkup($value, $errors) {
        $orgs = $this->GetAllOrganizations();
        $res = '<label>Organization</label><div><select id="organization" name="organization">' . 
            '<option value="">[Select an organization]</option>';
        foreach ($orgs as $org) {
            $res .= '<option value="' . $org->getId() . '"';
            if ($value == $org->getId()) {
                $res .= ' selected';
            }
            $res .= '>' . $org->getName() . '</option>';
        }
        $res .= '</select><span class="error">';
        if (isset($errors) && isset($errors['organization'])) {
            $res .= $errors['organization'];
        }
        $res .= '</span></div>';
        return $res;
    }

    private function ValidateOrgInfo($orgModel) {
        global $errors;
        $validation = new Validation();
        $org = new Organization();
        $org = $orgModel;

        if ($validation->IsEmpty($org->getName())) {
            $errors['firstName'] = "Name can't be blank";
        } elseif ($validation->IsNotValidLenght($org->getName(), 50)) {
            $errors['firstName'] = "Name too long";
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>
