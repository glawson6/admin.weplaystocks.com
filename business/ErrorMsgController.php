<?php
class ErrorMsgController extends BaseController {
      public function InsertErrorMsg($sym, $error_msg,$date) {
        try {
            
            global $errors;

            if ($this->ValidationErrorMsg($sym, $error_msg,$date)) {
                $errorMsgMapper = new ErrorMsgMapper();
                $errorMsg = new ErrorMsg();


                //$errorMsgCode=$_REQUEST['errorMsgCode'];
                //$errorMsgDesc=$_REQUEST['errorMsgDesc'];

                $errorMsg->setSym($sym);
                $errorMsg->setErrorMsg($error_msg);
                $errorMsg->setDate($date);

                $errorMsgMapper->InsertErrorMsg($errorMsg);
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

     //Get All ErrorMsg  sachith
    public function GetAllErrorMsg() {
        try {
            $errorMsgMapper = new ErrorMsgMapper();
            return $errorMsgMapper->GetAllErrorMsg();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    // validation error message

    private function ValidationErrorMsg($sym, $error_msg,$date) {
        global $errors;
        $validation = new Validation();
        if ($validation->IsEmpty($sym)) {
            $errors['sym'] = "sym can't be blank";
        } 

        if ($validation->IsEmpty($error_msg)) {
            $errors['coDesc'] = "Description can't be blank";
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

?>
