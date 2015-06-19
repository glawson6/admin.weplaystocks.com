<?php

class SectorController extends BaseController {

    // insert auto by using execl
    public function InsertSectorAutomatically($sectorCode, $sectorDesc) {
        try {

            global $errors;

            if ($this->ValidationSector(trim($sectorCode), trim($sectorDesc), 0)) {
                $sectorMapper = new SectorMapper();
                $sector = new Sector();

                $sector->setSectCode($sectorCode);
                $sector->setSectDesc($sectorDesc);

                $sectorMapper->InsertSector($sector);
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // insert manual 
    public function InsertSectorManual() {
        try {

            global $errors;

            $sectorCode = trim($_REQUEST['code']);
            $sectorDesc = trim($_REQUEST['description']);
            if ($this->ValidationSector($sectorCode, $sectorDesc, 0)) {
                $sectorMapper = new SectorMapper();
                $sector = new Sector();

                $sector->setSectCode($sectorCode);
                $sector->setSectDesc($sectorDesc);

                $sectorMapper->InsertSector($sector);
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //Get All Sector  sachith
    public function GetAllSector() {
        try {
            $sectorMapper = new SectorMapper();
            return $sectorMapper->GetAllSectors();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

// get sector by id
    public function GetSectorById() {
        try {
            $sectorMapper = new SectorMapper();
            $sector = new Sector();

            $id = $_REQUEST['id'];
            $sector->setId($id);

            return $sectorMapper->GetSectorById($sector);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // update
    public function UpdateSector() {
        try {

            global $errors;
            $id = $_REQUEST['id'];
            $sectorCode = trim($_REQUEST['code']);
            $sectorDesc = trim($_REQUEST['description']);
            if ($this->ValidationSector($sectorCode, $sectorDesc, $id)) {
                $sectorMapper = new SectorMapper();
                $sector = new Sector();
                $sector->setId($id);
                $sector->setSectCode($sectorCode);
                $sector->setSectDesc($sectorDesc);

                $sectorMapper->UpdateSector($sector);
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // validation sector

    private function ValidationSector($sectorCode, $sectorDesc, $id) {
        global $errors;
        $validation = new Validation();
        if ($validation->IsEmpty($sectorCode)) {
            $errors['code'] = "Code can't be blank";
        } else
        if ($validation->IsNotValidLenght($sectorCode, 255)) {
            $errors['code'] = "Code too long";
        } else {
            $sectorMapper = new SectorMapper();
            $sector = new Sector();
            $sector->setSectCode($sectorCode);
            if ($sectorMapper->IsCodeDuplicate($sector)) {
                if ($id == 0) {
                    $errors['code'] = "Code cannot be duplicate";
                } else {
                    $sector->setSectCode($sectorCode);
                    $sector = $sectorMapper->GetSectorByCode($sector);
                    if ($sector) {
                        if ($sector->getId() != $id) {
                            $errors['code'] = "Code cannot be duplicate";
                        }
                    }
                }
            }
        }

        if ($validation->IsEmpty($sectorDesc)) {
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
