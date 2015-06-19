<?php

class ContestController extends BaseController {

    public function AddContest() {
        try {
            global $errors;
            $contestMapper = new ContestMapper();

            $contest = new Contest();
            $this->PopulateContestFromRequest($contest);

            if (!$this->ValidateContestInfo($contest)) return FALSE;
            return $contestMapper->InsertContest($contest);
        } catch (Exception $ex) {
            throw $ex->getTraceAsString();
        }
    }

    public function GetContestById($contestId) {
        try {
            $contestMapper = new ContestMapper();
            return $contestMapper->GetContestById($contestId);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function GetContestsByOwner($uid) {
        try {
            $contestMapper = new ContestMapper();
            return $contestMapper->GetContestsByOwner($uid);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function GetContestsByOwnerOrganization($orgId) {
        try {
            $contestMapper = new ContestMapper();
            return $contestMapper->GetContestsByOwnerOrganization($orgId);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function UpdateContest() {
        try {
            $contestMapper = new ContestMapper();
            $portfolioMapper = new PortfolioMapper();
            $contest = new Contest();

            $contest->setId(trim($_REQUEST['id']));
            $this->PopulateContestFromRequest($contest);
            if (!$this->ValidateContestInfo($contest)) return FALSE;

            $contestMapper->UpdateContest($contest);
            $portfolios = $portfolioMapper->GetPortfoliosByContest($contest->getId());
            foreach ($portfolios as $portfolio) {
                $cashDiff = $contest->getCashBegin() - $portfolio->getCashBegin();
                $portfolio->setCashBegin($contest->getCashBegin());
                $portfolio->setCashAvailable($portfolio->getCashAvailable() + $cashDiff);
                $portfolio->setPortfolioValue($portfolio->getPortfolioValue() + $cashDiff);
                $portfolioMapper->UpdatePortfolio($portfolio);
            }

            return TRUE;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function DeleteContest() {
        try {
            global $errors;
            if ($_REQUEST['sec_token'] != session_id()) {
                $errors['submit'] = 'Authorization failed';
                return FALSE;
            }
            $contestMapper = new ContestMapper();
            $contestId = trim($_REQUEST['id']);
            $contestMapper->DeleteContestById($contestId);
            return TRUE;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function SelectionMarkup($value) {
        global $errors;
        $loginUser = $_SESSION['loginSession'];
        $contests = $this->GetContestsByOwner($loginUser->getId());
        $res = '<label>Contest</label><div><select id="contest" name="contest">' . 
            '<option value="">[Select an contest]</option>';
        foreach ($contests as $contest) {
            $res .= '<option value="' . $contest->getId() . '"';
            if ($value == $contest->getId()) {
                $res .= ' selected';
            }
            $res .= '>' . $contest->getName() . '</option>';
        }
        $res .= '</select><span class="error">';
        if (isset($errors) && isset($errors['contest'])) {
            $res .= $errors['contest'];
        }
        $res .= '</span></div>';
        return $res;
    }

    public function ValidateContestInfo($contestModel) {
        global $errors;
        $validation = new Validation();
        $contest = $contestModel;

        if ($validation->IsEmpty($contest->getName())) {
            $errors['name'] = "Name can't be blank";
        } elseif ($validation->IsNotValidLenght($contest->getName(), 50)) {
            $errors['name'] = "Name too long";
        }

        if ($validation->IsEmpty($contest->getStartDate())) {
            $errors['startDate'] = "Start date can't be blank";
        }
        if ($validation->IsEmpty($contest->getEndDate())) {
            $errors['endDate'] = "End date can't be blank";
        }
        if ($validation->IsNotDate($contest->getStartDate())) {
            $errors['startDate'] = "Start date not formatted correctly";
        }
        if ($validation->IsNotDate($contest->getEndDate())) {
            $errors['endDate'] = "End date not formatted correctly";
        }
        if (strtotime($contest->getStartDate()) >= strtotime($contest->getEndDate())) {
            $errors['endDate'] = "End date must be after start date";
        }

        if ($validation->IsEmpty($contest->getOwner())) {
            $errors['owner'] = "Must have an owner";
        }
        if ($validation->IsNotNumber($contest->getCommission())) {
            $errors['commission'] = "Must enter commission, no commas (enter 0.00 for no commission)";
        }
        if ($validation->IsNotNumber($contest->getCashBegin())) {
            $errors['cashBegin'] = "Must set starting cash amount, no commas";
        }
        if ($validation->IsNotNumber($contest->getMinimumStockPrice())) {
            $errors['minimumStockPrice'] = "Must enter minimum stock price, no commas (enter 0.00 for no minimum)";
        }

        if (count($errors) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function PopulateContestFromRequest($contest) {
        $loginUser = $_SESSION['loginSession'];
        $accessRight = $this->GetAccessRight($loginUser);

        $owner = $loginUser->getId();
        if (isset($_REQUEST['owner'])) {
            $ownerId = trim($_REQUEST['owner']);
            if ($accessRight->getCanUpdateAllOrganizations()) {
                $owner = $ownerId;
            } else {
                $userMapper = new UserMapper();
                $ownerObj = $userMapper->GetUserById($ownerId);
                if ($ownerObj->getOrganization() === $loginUser->getOrganization()) {
                    $owner = $ownerId;
                }
            }
        }

        $contest->setOwner($owner);
        $contest->setName(trim($_REQUEST['name']));
        $contest->setStartDate(trim($_REQUEST['startDate']));
        $contest->setEndDate(trim($_REQUEST['endDate']));
        $contest->setCommission(trim($_REQUEST['commission']));
        $contest->setCashBegin(trim($_REQUEST['cashBegin']));
        $contest->setMinimumStockPrice(trim($_REQUEST['minimumStockPrice']));
        $contest->setNotes(trim($_REQUEST['notes']));
    }

}

?>
