<?php
//added by sachith
class ForgetPasswordMapper extends BaseMapper {

    private $dbTable = 'forget_password';

    public function InsertForgetPassword($forgetPasswordModel) {
        try {
            parent::OpenMySqlConnection();

            $forgetPassword = new ForgetPassword();
            $forgetPassword = $forgetPasswordModel;


            $querry = "INSERT INTO $this->dbTable (user_id, code) 
                    VALUES('" . $forgetPassword->getUserId() . "','" . $forgetPassword->getCode() . "')";

            $result = mysql_query($querry, $this->connection);


            if (!$result) {

                throw new customException('user forget password insertion fail');
            }



            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function DeleteForgetPasswordByUserId($forgetPasswordModel) {
        try {
            parent::OpenMySqlConnection();

            $forgetPassword = new ForgetPassword();
            $forgetPassword = $forgetPasswordModel;


            $querry = "DELETE FROM $this->dbTable WHERE user_id='" . $forgetPassword->getUserId() . "' ";

            $result = mysql_query($querry, $this->connection);


            if (!$result) {

                throw new customException('user forget password delete fail');
            }



            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

    public function IsCheckForgetPasswordByUserIdAndCode($forgetPasswordModel) {
        try {
            parent::OpenMySqlConnection();

            $forgetPassword = new ForgetPassword();
            $forgetPassword = $forgetPasswordModel;


            $forgetPassword->setUserId(mysql_real_escape_string(intval($forgetPassword->getUserId())));

            $querry = "SELECT *FROM $this->dbTable WHERE user_id='" . $forgetPassword->getUserId() . "' AND code='" . $forgetPassword->getCode() . "'";

            $result = mysql_query($querry, $this->connection);

            $nr = 0;

            if (isset($result)) {
                $nr = mysql_num_rows($result);
            }

            if ($nr == 0) {
                return FALSE;
            } else {

                return TRUE;
            }

            parent::CloseMySqlConnection();
        } catch (customException $ex) {
            echo $ex->errorMessage();
        }
    }

}

?>
