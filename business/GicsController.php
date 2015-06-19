<?php

class GicsController extends BaseController {

    // insert auto by using execl
    public function InsertGicsAuto($gicsCode, $gicsDesc) {
        try {

            global $errors;

            if ($this->ValidationGics(trim($gicsCode), trim($gicsDesc), 0)) {
                $gicsMapper = new GicsMapper();
                $gics = new Gics();

                $gics->setGicsCode($gicsCode);
                $gics->setGicsDesc($gicsDesc);

                $gicsMapper->InsertGics($gics);
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // insert auto by using execl
    public function InsertGicsManual() {
        try {

            global $errors;
            $gicsCode = trim($_REQUEST['code']);
            $gicsDesc = trim($_REQUEST['description']);
            if ($this->ValidationGics($gicsCode, $gicsDesc, 0)) {
                $gicsMapper = new GicsMapper();
                $gics = new Gics();

                $gics->setGicsCode($gicsCode);
                $gics->setGicsDesc($gicsDesc);

                $gicsMapper->InsertGics($gics);
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //Get All Gics  sachith
    public function GetAllGics() {
        try {
            $gicsMapper = new GicsMapper();
            return $gicsMapper->GetAllGics();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get gics by id
    public function GetGicsById() {
        try {
            $gicsMapper = new GicsMapper();
            $gics = new Gics();

            $id = $_REQUEST['id'];
            $gics->setId($id);

            return $gicsMapper->GetGicsById($gics);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // update
    public function UpdateGics() {
        try {

            global $errors;
            $id = $_REQUEST['id'];
            $gicsCode = trim($_REQUEST['code']);
            $gicsDesc = trim($_REQUEST['description']);
            if ($this->ValidationGics($gicsCode, $gicsDesc, $id)) {
                $gicsMapper = new GicsMapper();
                $gics = new Gics();
                $gics->setId($id);
                $gics->setGicsCode($gicsCode);
                $gics->setGicsDesc($gicsDesc);

                $gicsMapper->UpdateGics($gics);
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

      // get gics by code
    public function GetGicsByCode() {
        try {
            $gicsMapper = new GicsMapper();
            $gics = new Gics();

            $code = $_REQUEST['id'];
            $gics->setGicsCode($code);

            return $gicsMapper->GetGicsByCode($gics);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    // validation

    private function ValidationGics($gicsCode, $gicsDesc, $id) {
        global $errors;
        $validation = new Validation();
        if ($validation->IsEmpty($gicsCode)) {
            $errors['code'] = "Code can't be blank";
        } else
        if ($validation->IsNotValidLenght($gicsCode, 255)) {
            $errors['code'] = "Code too long";
        } else {
            $gicsMapper = new GicsMapper();
            $gics = new Gics();
            $gics->setGicsCode($gicsCode);
            if ($gicsMapper->IsCodeDuplicate($gics)) {
                if ($id == 0) {
                    $errors['code'] = "Code cannot be duplicate";
                } else {
                    $gics->setGicsCode($gicsCode);
                    $gics = $gicsMapper->GetGicsByCode($gics);
                    if ($gics) {
                        if ($gics->getId() != $id) {
                            $errors['code'] = "Code cannot be duplicate";
                        }
                    }
                }
            }
        }

        if ($validation->IsEmpty($gicsDesc)) {
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
