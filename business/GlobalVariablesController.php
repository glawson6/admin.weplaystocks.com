<?php

class GlobalVariablesController extends BaseController {

    public function GetGlobalVariables() {
        try {
            $globalVarsMapper = new GlobalVariablesMapper();
            return $globalVarsMapper->GetGlobalVariables();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function UpdateGlobalVariables() {
        try {
            $globalVarsMapper = new GlobalVariablesMapper();
            $globalVars = new GlobalVariables();

            $this->PopulateGlobalVarsFromRequest($globalVars);

            if (!$this->ValidateGlobalVars($globalVars)) return FALSE;
            $globalVarsMapper->UpdateGlobalVariables($globalVars);
            return TRUE;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function ValidateGlobalVars($globalVars) {
        global $errors;
        $validation = new Validation();

        if ($validation->IsEmpty($globalVars->getSystemName())) {
            $errors['systemName'] = "System name can't be blank";
        }
        if ($validation->IsEmpty($globalVars->getSystemDomain())) {
            $errors['systemDomain'] = "System domain can't be blank";
        }
        if ($validation->IsEmpty($globalVars->getSystemEmail())) {
            $errors['systemEmail'] = "System email can't be blank";
        }
        if ($validation->IsNotNumber($globalVars->getInterestRate())) {
            $errors['interestRate'] = "Interest rate can't be blank (no commas)";
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function PopulateGlobalVarsFromRequest($globalVars) {
        $globalVars->setSystemName(trim($_REQUEST['systemName']));
        $globalVars->setSystemDomain(trim($_REQUEST['systemDomain']));
        $globalVars->setSystemEmail(trim($_REQUEST['systemEmail']));
        $globalVars->setInterestRate(trim($_REQUEST['interestRate']));
    }

}

?>
