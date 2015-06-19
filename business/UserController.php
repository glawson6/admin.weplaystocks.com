<?php

class UserController extends BaseController {

    public function RegisterAdmin() {
        $this->RegisterUser(ApplicationKeyValues::$USER_TYPE_ADMIN);
    }

    public function RegisterStudent() {
        $this->RegisterUser(ApplicationKeyValues::$USER_TYPE_STUDENT);
    }

    public function RegisterTeacher() {
        $this->RegisterUser(ApplicationKeyValues::$USER_TYPE_TEACHER);
    }

    // register new user by sachith
    private function RegisterUser($userType) {
        try {
            global $errors;

            $user = new User();
            $user->setPassword($this->GetEncryptedPassword(trim($_REQUEST['password'])));
            $user->setUserType($userType);
            $user->setUserStatus(ApplicationKeyValues::$USER_STATUS_ACTIVE);
            $this->PopulateUserFromRequest($user);

            $password = trim($_REQUEST['password']);
            $confirmPassword = trim($_REQUEST['confirmPassword']);

            if ($this->ValidateNewUserInfo($user, $password, $confirmPassword)) {

                $userMapper = new UserMapper();
                $insertId = $userMapper->InsertUser($user);

                if ($insertId != 0) {
                    //TODO send email to new user
                    //mail($user->getEmail(), '[subject]', '[message]', 'From: [system address]');
                    return $insertId;
//                    $user = $userMapper->GetUserById($insertId);
//
//                    if (isset($user)) {
//                        $_SESSION['loginSession'] = serialize($user);
//                        return TRUE;
//                    } else {
//                        return FALSE;
//                    }
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } catch (Exception $ex) {
            throw $ex->getTraceAsString();
        }
    }

    public function InsertStudentsFromFile($studentArray, $defaultPassword, $contestId) {
        try {
            global $errors;
            global $warnings;

            $loginUser = $_SESSION['loginSession'];
            $accessRight = $this->GetAccessRight($loginUser);
            $org = $loginUser->getOrganization();
            if ($accessRight->getCanUpdateAllOrganizations()) {
                if ($contestId) {
                    $contestMapper = new ContestMapper();
                    $contest = $contestMapper->GetContestById($contestId);
                    $org = $contest->getOrganization();
                } else if (isset($_REQUEST['organization'])) {
                    $org = trim($_REQUEST['organization']);
                }
            }

            $dummy = new User();
            $dummy->setUserType(ApplicationKeyValues::$USER_TYPE_STUDENT);
            $dummy->setUserStatus(ApplicationKeyValues::$USER_STATUS_ACTIVE);
            $dummy->setFirstName('dummy');
            $dummy->setLastName('dummy');
            $dummy->setEmail('dummy@dummy.com');

            $dummy->setOrganization($org);
            $dummy->setPassword($this->GetEncryptedPassword($defaultPassword));
            if (!$this->ValidateNewUserInfo($dummy, $defaultPassword, $defaultPassword)) {
                return FALSE;
            }

            $studentIds = array();

            for ($x = 1; $x <= count($studentArray); $x++) {

                $user = new User();
                $userMapper = new UserMapper();

                $user->setOrganization($org);
                $user->setUserType(ApplicationKeyValues::$USER_TYPE_STUDENT);
                $user->setUserStatus(ApplicationKeyValues::$USER_STATUS_ACTIVE);

                $user->setFirstName(trim($studentArray[$x][1]));
                $user->setLastName(trim($studentArray[$x][2]));
                $user->setEmail(trim($studentArray[$x][3]));
                $user->setPassword($this->GetEncryptedPassword($defaultPassword));

                if ($contestId) {
                    $oldUser = $userMapper->GetUserByEmail($user);
                    if ($oldUser) {
                        $studentIds[] = $oldUser->getId();
                        continue;
                    }
                }

                if ($this->ValidateNewUserInfo($user, $defaultPassword, $defaultPassword)) {
                    $userMapper = new UserMapper();
                    $insertId = $userMapper->InsertUser($user);
                    if ($insertId == 0) {
                        throw new CustomException('error registering student:' . $user->getEmail());
                    }
                    if ($contestId) $studentIds[] = $insertId;

                } else if ($errors['email']) {
                    if (strtolower($user->getEmail()) === 'email') {
                        $errors = array();
                    } else {
                        if (!$warnings['student_import']) {
                            $warnings['student_import'] = '<div class="error">Students not added (email already registered?):<ul>';
                        }
                        $warnings['student_import'] .= '<li>' . $user->getFirstName() . ' ' . $user->getLastName() . ' (' . $user->getEmail() . ')</li>';
                    }

                } else {
                    $errMsg = 'invalid student:' . $user->getEmail() . ' (';
                    foreach ($errors as $error) {
                        $errMsg .= $error . ', ';
                    }
                    throw new CustomException(substr($errMsg, 0, -2) . ')');
                }
            }

            if ($warnings['student_import']) $warnings['student_import'] .= '</ul></div>';
            if ($contestId && count($studentIds) > 0) {
                $portfolioController = new PortfolioController();
                return $portfolioController->AddPortfolios($studentIds, $contestId);
            }
            return TRUE;

        } catch (Exception $exc) {
            if ($exc->errorMessage()) {
                echo $exc->errorMessage();
            } else {
                echo $exc->getTraceAsString();
            }
        }
    }

    public function GetUsersByTypeAndOrganization($userType, $orgId) {
        try {
            $userMapper = new UserMapper();
            return $userMapper->GetUsersByTypeAndOrganization($userType, $orgId);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function GetStudentsWithContests($orgId) {
        try {
            $userMapper = new UserMapper();
            return $userMapper->GetStudentsWithContests($orgId);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function Login() {
        try {
            global $errors;

            $user = new User();
            $user->setEmail(trim($_REQUEST['email']));

            $user->setUserStatus(ApplicationKeyValues::$USER_STATUS_ACTIVE); // set active status for user added by sachith
            $requestPassword = trim($_REQUEST['password']);

            if ($this->ValidateLogin($user, $requestPassword)) {

                $userMapper = new UserMapper();
                $user = $userMapper->GetUserByEmailAndUserStatus($user); // get active users added by sachith

                $requestPassword = $this->GetEncryptedPassword($requestPassword);

                if (isset($user)) {
                    if (strcasecmp($user->getPassword(), $requestPassword) == 0) {
//                        $_SESSION['loginSession'] = serialize($user);
                        $_SESSION['loginSession'] = $user;
                        return TRUE;
                    } else {
                        $errors['loginError'] = 'Invalid username or password !';
                        return FALSE;
                    }
                } else {
                    $errors['loginError'] = 'Invalid username or password !';
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } catch (Exception $ex) {
            throw $ex->getTraceAsString();
        }
    }

    /**
     * Get html markup for a selection input element with an organization's 
     * admins and teachers, for add / edit contest forms.
     * @param $orgId The organization from which users can be selected
     * @param $value The default selected value (optional)
     * @param $errors The global list of errors, in case there was an error with this selection in a previously submitted form
     * @return The markup for this selection input, as a string
     */
    public function SelectionMarkup($orgId, $value, $errors) {
        $users = $this->GetUsersByTypeAndOrganization(ApplicationKeyValues::$USER_TYPE_ADMIN, $orgId);
        $teachers = $this->GetUsersByTypeAndOrganization(ApplicationKeyValues::$USER_TYPE_TEACHER, $orgId);
        if (count($users) > 0) {
            if (count($teachers) > 0) {
                foreach ($teachers as $teacher) $users[] = $teacher;
            }
        } else {
            $users = $teachers;
        }
        if (count($users) === 0) {
            return '<div>No admins or teachers found for this organization!</div>';
        }
        $res = '<label>Owner</label><div><select id="owner" name="owner">' . 
            '<option value="">[Select a user]</option>';
        foreach ($users as $user) {
            $res .= '<option value="' . $user->getId() . '"';
            if ($value == $user->getId()) {
                $res .= ' selected';
            }
            $res .= '>' . $user->getFirstName() . ' ' . $user->getLastName() . '</option>';
        }
        $res .= '</select><span class="error">';
        if (isset($errors) && isset($errors['owner'])) {
            $res .= $errors['owner'];
        }
        $res .= '</span></div>';
        return $res;
    }

    public function SaveChangePassword() {
        try {
            global $errors;
            $loginUser = $_SESSION['loginSession'];

            $curPassword = $this->GetEncryptedPassword(trim($_REQUEST['curPassword']));
            $newPassword = trim($_REQUEST['newPassword']);
            $confirmPassword = trim($_REQUEST['confirmPassword']);

            if ($this->ValidateChangePassword($loginUser, $curPassword, $newPassword, $confirmPassword)) {
                $userMapper = new UserMapper();
                $loginUser->setPassword($this->GetEncryptedPassword($newPassword));
                $userMapper->SaveChangePassword($loginUser);
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function SaveResetPassword() {
        try {
            global $errors;

            $user = new User();
            $user->setId($_REQUEST['id']);

            $newPassword = trim($_REQUEST['newPassword']);
            $confirmPassword = trim($_REQUEST['confirmPassword']);

            if ($this->ValidatePassword($newPassword, $confirmPassword)) {
                $userMapper = new UserMapper();
                $user->setPassword($this->GetEncryptedPassword($newPassword));
                $userMapper->SaveChangePassword($user);

                $forgetPasswordMapper = new ForgetPasswordMapper();
                $forgetPassword = new ForgetPassword();

                $forgetPassword->setUserId($user->getId()); //set user id as forgetpassword user id
                $forgetPasswordMapper->DeleteForgetPasswordByUserId($forgetPassword); // delete user forgetpassword exist data

                return TRUE;
            } else {
                return FALSE;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    //change user status by sachith
    public function ChangeUserStatus() {
        try {
            $userMapper = new UserMapper();
            $user = new User();

            $id = $_REQUEST['id'];
            $status = $_REQUEST['status'];

            $user->setId($id);
            $user->setUserStatus($status);

            $userMapper->UpdateUserStatus($user);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function UpdateUserInfo() {
        try {
            $userMapper = new UserMapper();
            $user = new User();

            $user->setId(trim($_REQUEST['id']));
            $this->PopulateUserFromRequest($user);

            if (!$this->ValidateUserInfo($user)) return FALSE;
            $userMapper->UpdateUserInfo($user);
            $loginUser = $_SESSION['loginSession'];
            if ($loginUser->getId() == $user->getId()) {
                $_SESSION['loginSession'] = $userMapper->GetUserById($user->getId());
            }
            return TRUE;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    // get user by id not check is active status

    public function GetUserById($id) {
        try {
            $userMapper = new UserMapper();
            return $userMapper->GetUserById($id);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function ValidateChangePassword($user, $curPassword, $newPassword, $confirmPassword) {
        global $errors;

        $validation = new Validation();
        $userMapper = new UserMapper();

        $userCheck = $userMapper->GetUserById($user->getId());
        if (!$userCheck || $userCheck->getPassword() != $curPassword) {
            $errors['curPassword'] = "Incorrect password";
        }

        if (count($errors) == 0) {
            return $this->ValidatePassword($newPassword, $confirmPassword);
        } else {
            return FALSE;
        }
    }

    private function ValidatePassword($newPassword, $confirmPassword) {
        global $errors;

        $validation = new Validation();

        if ($validation->IsEmpty($newPassword)) {
            $errors['newPassword'] = "Password can't be blank";
        } else
        if ($validation->IsNotValidLenght($newPassword, 20)) {
            $errors['newPassword'] = "Password too long";
        } else
        if ($validation->IsNotMinimumValidLenght($newPassword, 5)) {
            $errors['newPassword'] = "Short passwords are easy to guess. Try one with at least 5 characters";
        } else
        if ($validation->IsEmpty($confirmPassword)) {
            $errors['confirmPassword'] = "Confirm Password can't be blank";
        } else
        if ($validation->IsStringNotEqual($newPassword, $confirmPassword)) {
            $errors['confirmPassword'] = "Password and confirm password don't match";
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function ValidateUserInfo($userModel) {
        global $errors;
        $validation = new Validation();
        $user = new User();
        $user = $userModel;
        $userMapper = new UserMapper();

        if ($validation->IsEmpty($user->getFirstName())) {
            $errors['firstName'] = "First Name can't be blank";
        } elseif ($validation->IsNotValidLenght($user->getFirstName(), 50)) {
            $errors['firstName'] = "First Name too long";
        }

        if (!$validation->IsEmpty($user->getLastName())) {
            if ($validation->IsNotValidLenght($user->getLastName(), 50)) {
                $errors['lastName'] = "Last Name too long";
            }
        }

        if ($validation->IsEmpty($user->getOrganization())) {
            $errors['organization'] = "Must be part of an organization";
        }

        if ($validation->IsEmpty($user->getEmail())) {
            $errors['email'] = "Email can't be blank";
        } else
        if ($validation->IsNotValidEmail($user->getEmail())) {
            $errors['email'] = "Invalid email address";
        } else
        if ($userMapper->GetUserByEmail($user) && $userMapper->GetUserByEmail($user)->getId() != $user->getId()) {// check email duplicate
            $errors['email'] = "Duplicate email address";
        } else
        if ($validation->IsNotValidLenght($user->getEmail(), 50)) {// check length
            $errors['email'] = "Email address too long";
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function ValidateNewUserInfo($userModel, $password, $confirmPassword) {
        global $errors;
        $validation = new Validation();

        if (!$this->ValidateUserInfo($userModel)) return FALSE;

        if ($validation->IsEmpty($password)) {
            $errors['password'] = "Password can't be blank";
        } else
        if ($validation->IsNotValidLenght($password, 20)) {
            $errors['password'] = "Password too long";
        } else
        if ($validation->IsNotMinimumValidLenght($password, 5)) {
            $errors['password'] = "Short passwords are easy to guess. Try one with at least 5 characters";
        } else
        if ($validation->IsEmpty($confirmPassword)) {
            $errors['confirmPassword'] = "Confirm Password can't be blank";
        } else
        if ($validation->IsStringNotEqual($password, $confirmPassword)) {
            $errors['confirmPassword'] = "Password and confirm password don't match";
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function ValidateLogin($user, $requestPassword) {
        global $errors;
        $validation = new Validation();

        if ($validation->IsEmpty($user->getEmail())) {
            $errors['email'] = "Email can't be blank";
        } else
        if ($validation->IsNotValidEmail($user->getEmail())) {
            $errors['email'] = "Invalid email address";
        }

        if ($validation->IsEmpty($requestPassword)) {
            $errors['password'] = "Password can't be blank";
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function PopulateUserFromRequest($user) {
        $loginUser = $_SESSION['loginSession'];
        $accessRight = $this->GetAccessRight($loginUser);

        $org = $loginUser->getOrganization();
        if (isset($_REQUEST['organization']) && $accessRight->getCanUpdateAllOrganizations()) {
            $org = trim($_REQUEST['organization']);
        }

        $user->setOrganization($org);
        $user->setFirstName(trim($_REQUEST['firstName']));
        $user->setLastName(trim($_REQUEST['lastName']));
        $user->setEmail(trim($_REQUEST['email']));
    }

}

?>
