<?php

class MessageController extends BaseController {

    public function AddMessage() {
        try {
            global $errors;
            $messageMapper = new MessageMapper();

            $message = new Message();
            $this->PopulateMessageFromRequest($message);

            if (!$this->ValidateMessageInfo($message)) return FALSE;
            return $messageMapper->InsertMessage($message);
        } catch (Exception $ex) {
            throw $ex->getTraceAsString();
        }
    }

    public function GetMessagesByContest($contestId) {
        try {
            $messageMapper = new MessageMapper();
            return $messageMapper->GetMessagesByContest($contestId);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function GetRecentMessage($contestId) {
        try {
            $messageMapper = new MessageMapper();
            return $messageMapper->GetRecentMessage($contestId);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function ValidateMessageInfo($message) {
        global $errors;
        $validation = new Validation();

        if ($validation->IsEmpty($message->getMessage())) {
            $errors['message'] = "Message can't be blank";
        }
        if ($validation->IsEmpty($message->getContest())) {
            $errors['contest'] = "Must have an contest";
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function PopulateMessageFromRequest($message) {
        $message->setContest(trim($_REQUEST['contest']));
        $message->setMessage(trim($_REQUEST['message']));
    }

}

?>
