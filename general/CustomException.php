<?php

class CustomException extends Exception {

    //put your code here
    public function errorMessage() {
        //error message
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
                . ': <b>' . $this->getMessage() . '</b>';
        return $errorMsg;
    }

}

?>
