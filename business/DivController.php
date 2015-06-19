<?php

class DivController extends BaseController {

    // insert Div file detail 
    public function InsertDivInfo($divInfoArray) {
        try {
            $x = 1;
            while ($x <= count($divInfoArray)) {

                $divInfo = new DivInfo();
                $divInfoMapper = new DivInfoMapper();



                $sym = strtoupper(trim($divInfoArray[$x][1]));
                $divShare = $divInfoArray[$x][2];
                $divYld = $divInfoArray[$x][3];
                $divXDate = $divInfoArray[$x][4];
                $divPayDate = $divInfoArray[$x][5];

                if ($divXDate != "") {
                    $divXDate = $this->ConvertDateToMysqlType($divXDate);
                } else {
                    $divXDate = "0000-00-00";
                }
                if ($divPayDate != "") {
                    $divPayDate = $this->ConvertDateToMysqlType($divPayDate);
                } else {
                    $divPayDate = "0000-00-00";
                }
                $divInfo->setDivPayDate($divPayDate);
                $divInfo->setDivShare($divShare);
                $divInfo->setDivXDate($divXDate);
                $divInfo->setDivYld($divYld);
                $divInfo->setSym($sym);


                $divInfoMapper->InsertDivInfo($divInfo);

                $x++;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    
    //Get UpdateDivInfoById Madura
    public function UpdateDivInfoById($divInfoModel) {
        try {
            $divInfoMapper = new DivInfoMapper();           
            return $divInfoMapper->UpdateDivInfoById($divInfoModel);      
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
      //Get All DivInfo  sachith
    public function GetAllDivInfo() {
        try {
            $divInfoMapper = new DivInfoMapper();
           
            $divInfor=$divInfoMapper->GetAllDivInfo();
          
            
            return $divInfor;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    //get DivInfo by sym sachith
    public function SearchSym() {
        try {
            $divInfoMapper = new DivInfoMapper();
            $sym = strtoupper(trim($_REQUEST['sym']));
            $divInfo = new DivInfo();
            $divInfo->setSym($sym);

            return $divInfoMapper->SearchSym($divInfo);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
     public function GetDivInfoBySym() {
        try {
            $divInfoMapper = new DivInfoMapper();
            $sym = strtoupper(trim($_REQUEST['sym']));
            $divInfo = new DivInfo();
            $divInfo->setSym($sym);

            return $divInfoMapper->GetDivInfoBySym($divInfo);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //By Madura
    public function GetDivInfoBySymName($divInfo) {
        try {
            $divInfoMapper = new DivInfoMapper();            
            return $divInfoMapper->GetDivInfoBySym($divInfo);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
// convert date
    private function ConvertDateToMysqlType($Currentdate) {

        $month = substr($Currentdate, 0, 2);
        $_date = substr($Currentdate, 2, 2);
        $year = substr($Currentdate, 4, 4);

        $date = $year . "-" . $month . "-" . $_date;
        return $date;
      
    }

}

?>
