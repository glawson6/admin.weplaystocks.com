<?php

class PriceErrorInforController extends BaseController {

    public function GetErrorsByCurrentDate($error) {
        try {
            $priceErrorMapper=new PriceErrorInforMapper();
            $priceError=new PriceErrorInfor();
            $priceError=$error;
            return  $priceErrorMapper->GetErrorsByDate($priceError);
            
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

?>
