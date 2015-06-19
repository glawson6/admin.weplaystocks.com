<?php

class BaseController {

    //password encription
    public function GetEncryptedPassword($password) {
        try {
            $encryptedPassword = md5($password);

            return $encryptedPassword;
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
    }

    public function GetLoginSession() {
        try {
            if (isset($_SESSION['loginSession'])) {
                $loginSession = new User();
//                $loginSession = unserialize($_SESSION['loginSession']);
                $loginSession = $_SESSION['loginSession'];
            } else {
                $loginSession = NULL;
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
    }

    public function GetAccessRight($user) {// add by sachith
        $accessRight = new AccessRight();
        switch ($user->getUserType()) {
            case ApplicationKeyValues::$USER_TYPE_SUPER_ADMIN:
                $accessRight->setCanUpdateAllOrganizations(TRUE);
                $accessRight->setCanUpdateAdmins(TRUE);

            case ApplicationKeyValues::$USER_TYPE_ADMIN:
                $accessRight->setCanUpdateOwnOrganization(TRUE);
                $accessRight->setCanUpdateTeachers(TRUE);

            case ApplicationKeyValues::$USER_TYPE_TEACHER:
                $accessRight->setCanUpdateOwnContests(TRUE);
                $accessRight->setCanUpdateStudents(TRUE);

            case ApplicationKeyValues::$USER_TYPE_STUDENT:
                $accessRight->setCanUpdateOwnProfile(TRUE);
                break;
            default:
                break;
        }
        return $accessRight;
    }

    //create random cord by sachith
    public function createRandomCode() {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $code = '';
        while ($i <= 9) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $code = $code . $tmp;
            $i++;
        }
        return $code;
    }

    public function SendMail($to, $from, $subject, $body) {

        mail($to, $subject, $body, "From: $from");

        //mail function by sachith
        /*$headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= "Content-Transfer-Encoding: 8bit" . "\r\n";

        $SMTPMail = new SMTPClient('mail.rayzay.org.uk', '26', 'notification+rayzay.org.uk', 'abc123', $from, $to, $subject, $body, $headers);

        $SMTPMail->SendMail();*/
    }

}

?>
