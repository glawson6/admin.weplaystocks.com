<?php

class SecurityTypeController extends BaseController {

    // insert auto by using execl
    public function InsertSecurityTypeAutomatically($securityTypeCode, $securityTypeDesc) {
        try {

            global $errors;

            if ($this->ValidationSecurityType(trim($securityTypeCode), trim($securityTypeDesc), 0)) {
                $securityTypeMapper = new SecurityTypeMapper();
                $securityType = new SecurityType();

                $securityType->setSecType($securityTypeCode);
                $securityType->setTypeDesc($securityTypeDesc);

                $securityTypeMapper->InsertSecurityType($securityType);
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // insert manual 
    public function InsertSecurityTypeManual() {
        try {

            global $errors;
            $securityTypeCode = trim($_REQUEST['type']);
            $securityTypeDesc = trim($_REQUEST['description']);
            if ($this->ValidationSecurityType($securityTypeCode, $securityTypeDesc, 0)) {
                $securityTypeMapper = new SecurityTypeMapper();
                $securityType = new SecurityType();

                $securityType->setSecType($securityTypeCode);
                $securityType->setTypeDesc($securityTypeDesc);

                $securityTypeMapper->InsertSecurityType($securityType);
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //Get All SecurityType  sachith
    public function GetAllSecurityType() {
        try {
            $securityTypeMapper = new SecurityTypeMapper();
            return $securityTypeMapper->GetAllSecurityType();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get security type by id
    public function GetSecurityTypeById() {
        try {
            $securityTypeMapper = new SecurityTypeMapper();
            $securityType = new SecurityType();



            $id = $_REQUEST['id'];
            $securityType->setId($id);

            return $securityTypeMapper->GetSecurityTypeById($securityType);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // update
    public function UpdateSecurityType() {
        try {

            global $errors;
            $id = $_REQUEST['id'];
            $securityTypeCode = trim($_REQUEST['type']);
            $securityTypeDesc = trim($_REQUEST['description']);

            if ($this->ValidationSecurityType($securityTypeCode, $securityTypeDesc, $id)) {
                $securityTypeMapper = new SecurityTypeMapper();
                $securityType = new SecurityType();
                $securityType->setId($id);
                $securityType->setSecType($securityTypeCode);
                $securityType->setTypeDesc($securityTypeDesc);

                $securityTypeMapper->UpdateSecurityType($securityType);

                
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

     // get security type by type
    public function GetSecurityTypeByCode() {
        try {
            $securityTypeMapper = new SecurityTypeMapper();
            $securityType = new SecurityType();

            $type = $_REQUEST['id'];// id present as type in this time
            $securityType->setSecType($type);

            return $securityTypeMapper->GetSecurityTypeByCode($securityType);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    
    // validation 


    private function ValidationSecurityType($securityTypeCode, $securityTypeDesc, $id) {
        global $errors;
        $validation = new Validation();
        if ($validation->IsEmpty($securityTypeCode)) {
            $errors['type'] = "Type can't be blank";
        } else
        if ($validation->IsNotValidLenght($securityTypeCode, 255)) {
            $errors['type'] = "Type too long";
        } else {
            $securityTypeMapper = new SecurityTypeMapper();
            $securityType = new SecurityType();
            $securityType->setSecType($securityTypeCode);
            if ($securityTypeMapper->IsTypeDuplicate($securityType)) {

                if ($id == 0) {
                    $errors['type'] = "Type cannot be duplicate";
                } else {

                    $securityType = $securityTypeMapper->GetSecurityTypeByCode($securityType);
                    if ($securityType) {
                        if ($securityType->getId() != $id) {
                            $errors['type'] = "Type cannot be duplicate";
                        }
                    }
                }
            }
        }

        if ($validation->IsEmpty($securityTypeDesc)) {
            $errors['description'] = "Description can't be blank";
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>
