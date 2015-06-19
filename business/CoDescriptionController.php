<?php

class CoDescriptionController extends BaseController {

    public function InsertCoDescriptionManual() {
        try {

            global $errors;
            $sym = strtoupper(trim($_REQUEST['sym']));
            $coDesc = $_REQUEST['description'];
            if ($this->ValidationCoDescription($sym, $coDesc, 0)) {
                $coDescriptionMapper = new CoDescriptionMapper();
                $coDescription = new CoDescription();
                $coDescription->setSym($sym);
                $coDescription->setCoDesc($coDesc);

                $coDescriptionMapper->InsertCoDescription($coDescription);
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // insert auto

    public function InsertCoDescriptionAuto($codeArray) {
        try {

            $x = 1;
            while ($x <= count($codeArray)) {

                $coDescriptionCode = strtoupper(trim($codeArray[$x][1]));
                // $coDescriptionDesc = $codeArray[$x][2]; 
                $coDescriptionDesc = 'symble description'; // set default value 
                if ($this->ValidationCoDescription($coDescriptionCode, $coDescriptionDesc, 0)) {
                    $coDescriptionMapper = new CoDescriptionMapper();
                    $coDescription = new CoDescription();

                    $coDescription->setSym($coDescriptionCode);
                    $coDescription->setCoDesc($coDescriptionDesc);

                    $coDescriptionMapper->InsertCoDescription($coDescription);
                }


                $x++;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //Get All CoDescription  sachith
    public function GetAllCoDescription() {
        try {
            $coDescriptionMapper = new CoDescriptionMapper();
            return $coDescriptionMapper->GetAllCoDescription();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get co_description by id
    public function GetCoDescriptionById() {
        try {
            $coDescriptionMapper = new CoDescriptionMapper();
            $coDescription = new CoDescription();

            $id = $_REQUEST['id'];
            $coDescription->setId($id);

            return $coDescriptionMapper->GetCoDescriptionById($coDescription);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    // get co_description by id madura
    public function GetCompanyDescriptionById($coDescription) {
        try {
            $coDescriptionMapper = new CoDescriptionMapper();

            return $coDescriptionMapper->GetCoDescriptionById($coDescription);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get co_description by stock sym in request
    public function GetCoDescriptionBySym() {
        $this->GetCoDescription($_REQUEST['sym']);
    }

    // get co_description by stock sym name
    public function GetCoDescriptionBySymName($symName) {
        try {
            $coDescriptionMapper = new CoDescriptionMapper();
            $coDescription = new CoDescription();

            $sym = $symName;
            $coDescription->setSym($sym);

            return $coDescriptionMapper->GetCoDescriptionBySym($coDescription);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    // update
    public function UpdateCoDescription() {
        try {

            global $errors;
            $id = $_REQUEST['id'];
            $sym = strtoupper(trim($_REQUEST['sym']));
            $coDesc = $_REQUEST['description'];
            if ($this->ValidationCoDescription($sym, $coDesc, $id)) {
                $coDescriptionMapper = new CoDescriptionMapper();
                $coDescription = new CoDescription();

                $coDescription->setId($id);
                $coDescription->setSym($sym);
                $coDescription->setCoDesc($coDesc);

                $coDescriptionMapper->UpdateCoDescription($coDescription);
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // validation 


    private function ValidationCoDescription($sym, $coDesc, $id) {
        global $errors;
        $validation = new Validation();
        if ($validation->IsEmpty($sym)) {
            $errors['sym'] = "Sym can't be blank";
        } else {
            $coDescriptionMapper = new CoDescriptionMapper();
            $coDescription = new CoDescription();
            $coDescription->setSym($sym);
            if ($coDescriptionMapper->IsCoDescriptionDuplicate($coDescription)) {

                if ($id == 0) {
                    $errors['sym'] = "Sym cannot be duplicate";
                } else {

                    $coDescription = $coDescriptionMapper->GetCoDescriptionByCodeSYM($coDescription);
                    if ($coDescription) {
                        if ($coDescription->getId() != $id) {
                            $errors['sym'] = "Sym cannot be duplicate";
                        }
                    }
                }
            }
        }

        if ($validation->IsEmpty($coDesc)) {
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
