<?php

class ForgetPassordController extends BaseController {

    // added by sachith 
    //forget password request 
    public function ForgetPasswordRequest() {
        try {
            global $errors;
            global $messages;
            $email = trim($_REQUEST['email']); // trim email
            if ($this->ValidateUserEmail($email)) {

                $user = new User();
                $user->setEmail($email);
                $userMapper = new UserMapper();

                $user = $userMapper->GetUserByEmail($user); // check user in system
                if ($user) {
                    switch ($user->getUserStatus()) {
                        case ApplicationKeyValues::$USER_STATUS_ACTIVE; // is active status for user 

                            $globalVarsController = new GlobalVariablesController();
                            $globalVars = $globalVarsController->GetGlobalVariables();

                            $forgetPasswordMapper = new ForgetPasswordMapper();
                            $forgetPassword = new ForgetPassword();

                            $forgetPassword->setUserId($user->getId()); //set user id as forgetpassowrd user id
                            $forgetPasswordMapper->DeleteForgetPasswordByUserId($forgetPassword); // delete user forgetpassword exist data

                            $randomCode = parent::createRandomCode(); // create random code
                            $forgetPassword->setCode($randomCode); // set random code

                            $forgetPasswordMapper->InsertForgetPassword($forgetPassword); //add new user forget password detail

                            //$to = 'coollamda84@gmail.com'; //set resiver email
                            $to = $user->getEmail(); //set receiver email
                            $from = '"' . $globalVars->getSystemName() . 
                                '" <' . $globalVars->getSystemEmail() . '>'; // set sender email
                            $subject = '[ ' . $globalVars->getSystemName() . ' ] Reset Password'; // set subject

                            $body = 'To reset your password click the following URL: ' . $globalVars->getSystemDomain() . 'reset_password.php?id=' . $user->getId() . '&code=' . $randomCode;

                            $this->SendMail($to, $from, $subject, $body); // send email to user with id and code

                            $messages['message'] = 'Please check your email!';

                            return TRUE;

                            break;
                        case ApplicationKeyValues::$USER_STATUS_INACTIVE; // set inactive status for user 
                            $errors['loginError'] = 'Sorry your account is inactive!';
                            return FALSE;
                            break;
                        default :
                            break;
                    }
                } else {
                    $errors['loginError'] = 'Invalid user email!';
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // check forget password by id and code
    public function CheckForgetPasswordDetaiByIdAndCode() {
        try {
            global $messages;
            $id = $_REQUEST['id'];
            $randomCode = $_REQUEST['code'];

            $user = $userMapper->GetUserById($id); // check user in system
            if ($user) {
                switch ($user->getUserStatus()) {
                    case ApplicationKeyValues::$USER_STATUS_ACTIVE; // is active status for user 

                        $forgetPasswordMapper = new ForgetPasswordMapper();
                        $forgetPassword = new ForgetPassword();

                        $forgetPassword->setUserId($user->getId()); //set user id as forgetpassowrd user id
                        $forgetPassword->setCode($randomCode); // set random code

                        if ($forgetPasswordMapper->IsCheckForgetPasswordByUserIdAndCode($forgetPassword)) { //is user exist forget password detail
                            return TRUE;
                        } else {
                            $messages['message'] = 'Invalid user details!';
                            return FALSE;
                        }
                        break;
                    case ApplicationKeyValues::$USER_STATUS_INACTIVE; // set inactive status for user 
                        $messages['message'] = 'Sorry your account is inactive!';
                        return FALSE;
                        break;
                    default :
                        break;
                }
            } else {
                $messages['message'] = 'Invalid user!';
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    

    //validation user email

    public function ValidateUserEmail($email) {
        global $errors;
        $validation = new Validation();

        if ($validation->IsEmpty($email)) {
            $errors['email'] = "Email can't be blank";
        } else
        if ($validation->IsNotValidEmail($email)) {
            $errors['email'] = "Invalid email address";
        }



        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>
