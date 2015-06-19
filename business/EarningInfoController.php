<?php

class EarningInfoController extends BaseController {

    // insert earning infor file detail 
    public function InsertEarningInfo($earningInfoArray) {
        try {
            $x = 1;
            while ($x <= count($earningInfoArray)) {

                $earningInfo = new EarningInfo();
                $earningInfoMapper = new EarningInfoMapper();

                $sym =  strtoupper(trim($earningInfoArray[$x][1]));
                $earn_fy1 = $earningInfoArray[$x][2];
                $earn_fy2 = $earningInfoArray[$x][3];
                $pe_fy1 = $earningInfoArray[$x][4];
                $pe_fy2 = $earningInfoArray[$x][5];




                $earningInfo->setCurrYrE($earn_fy1);
                $earningInfo->setNxtYrE($earn_fy2);
                $earningInfo->setCurrPE($pe_fy1);
                $earningInfo->setNxtYrPE($pe_fy2);
                $earningInfo->setSym($sym);

                //  echo 'sym='.$sym." earn_fy1=".$earn_fy1." earn_fy2=".$earn_fy2." pe_fy1=".$pe_fy1." pe_fy2".$pe_fy2."<br/>";
                $earningInfoMapper->InsertEarningInfo($earningInfo);

                $x++;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //Get All EarningInfo  sachith
    public function GetAllEarningInfo() {
        try {
            $earningInfoMapper = new EarningInfoMapper();
            return $earningInfoMapper->GetAllEarningInfo();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    //Update Earning Information by ID Madura
    public function UpdateEarningInfoById($earningInfoModel) {
        try {
            $earningInfoMapper = new EarningInfoMapper();
            return $earningInfoMapper->UpdateEarningInfoById($earningInfoModel);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    // get  EarningInfo by id added by madura
    public function GetEarningInfoById($earningInfoModel) {
        try {
            $earningInfoMapper = new EarningInfoMapper();
            return $earningInfoMapper->GetEarningInfoById($earningInfoModel);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //get EarningInfo by sym sachith
    public function GetEarningInfoBySym() {
        try {
            $earningInfoMapper = new EarningInfoMapper();
            $sym = strtoupper(trim($_REQUEST['sym']));
            $earningInfo = new EarningInfo();
            $earningInfo->setSym($sym);

            return $earningInfoMapper->GetEarningInfoBySym($earningInfo);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
 //get EarningInfo by sym sachith
    public function SearchSym() {
        try {
            $earningInfoMapper = new EarningInfoMapper();
            $sym = strtoupper(trim($_REQUEST['sym']));
            $earningInfo = new EarningInfo();
            $earningInfo->setSym($sym);

            return $earningInfoMapper->SearchSym($earningInfo);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
}

?>
