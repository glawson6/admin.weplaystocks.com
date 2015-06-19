<?php
include_once 'init.php'; /* load requried pages and initialized */

if (isset($_REQUEST['id']) && isset($_REQUEST['status'])) {
    $userController = new UserController();
    $userController->ChangeUserStatus();
}
// check flag
if (isset($_REQUEST['flag'])) {
    $flag = $_REQUEST['flag'];
    switch ($flag) {
        case 'sym_detail':// if information request
            $priceController = new PriceController();
            $id = $_REQUEST['id'];
            $price = $priceController->GetPriceDetailById($id);
            $page = $_REQUEST['page'];
            ?>
            <div style="border: 1px;" >
                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                <table>
                    <?php
                    switch ($page) { // check which page is request
                        case 'search':
                            ?>
                            <!--                    set sym as hidden-->
                            <input type="hidden" name="sym" id="sym" value="<?php echo $price->getSym(); ?>" /> 
                            <tr>
                                <td>Date</td><td><input type="text" readonly name="date" id="date" value="<?php echo $price->getDate(); ?>"</td>
                                <td></td>

                            </tr>
                            <?php
                            break;
                        case 'dailyprice':
                            ?>
                            <!--                    set date as hidden-->
                            <input type="hidden" name="date" id="date" value="<?php echo $price->getDate(); ?>" />
                            <tr>
                                <td>symbol</td><td><input type="text"  name="sym" id="sym" value="<?php echo $price->getSym(); ?>"</td>
                                <td></td>

                            </tr>
                            <?php
                            break;

                        default:
                            break;
                    }
                    ?>

                    <tr>
                        <td>Aveg</td><td><input type="text" name="avg_vol" id="avg_vol" value="<?php echo $price->getAvgDaiVol(); ?>"</td>
                        <td></td>

                    </tr>
                    <tr>
                        <td>Wkhi</td><td><input type="text" name="wk_hi" id="wk_hi" value="<?php echo $price->getWkHi(); ?>"</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>WkLo</td><td><input type="text" name="wk_lo" id="wk_lo" value="<?php echo $price->getWkLo(); ?>"</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Prev clo</td><td><input type="text" name="pre_clos" id="pre_clos" value="<?php echo $price->getPrevClos(); ?>"/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Mkt cap</td><td><input type="text" name="mkt_cap" id="mkt_cap" value="<?php echo $price->getMktCap(); ?>"/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>open</td><td><input type="text" name="open" id="open" value="<?php echo $price->getOpen(); ?>"/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td ><input type="button" value="Close" name="close" onclick="_close()" /></td>
                        <td><input type="button" value="Update" name="update" onclick="_update()" />
                            <?php
                            switch ($page) {
                                case 'search':
                                    ?>
                                    <input type="button" value="Remove" name="remove" onclick="_remove()" />
                                    <?php
                                    break;


                                default:
                                    break;
                            }
                            ?>

                        </td>
                    </tr>
                </table>


            </div> 
            <?php
            break;
        case 'sym_update':// if request edit
            $errors = array();
            $price = new Price();

            $open = $_REQUEST['open'];
            $avg_vol = $_REQUEST['avg_vol'];
            $wk_hi = $_REQUEST['wk_hi'];
            $wk_lo = $_REQUEST['wk_lo'];
            $pre_clos = $_REQUEST['pre_clos'];
            $mkt_cap = $_REQUEST['mkt_cap'];
            $id = $_REQUEST['id'];
            $date = $_REQUEST['date'];
            $sym = $_REQUEST['sym'];

            $page = $_REQUEST['page'];


            $price->setSym($sym);
            $price->setDate($date);
            $price->setOpen($open);
            $price->setAvgDaiVol($avg_vol);
            $price->setWkHi($wk_hi);
            $price->setWkLo($wk_lo);
            $price->setPrevClos($pre_clos);
            $price->setMktCap($mkt_cap);
            $price->setId($id);

            $priceController = new PriceController();


            if ($priceController->UpdatePriceById($price)) {
                ?>
                <div style="border: 1px;"  >
                    <table>
                        <tr>
                            <td>Update was Successful</td>
                        </tr>
                        <tr>
                            <td>
                            <td ><input type="button" value="Close" name="close" onclick="_close()" /></td>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php
            } else {
                ?>

                <div style="border: 1px;" >
                    <input type="hidden" name="id" id="id" value="<?php echo $id ?>" />
                    <table>
                        <?php
                        switch ($page) { // check which page is request
                            case 'search':
                                ?>
                                <!--                    set sym as hidden-->
                                <input type="hidden" name="sym" id="sym" value="<?php echo $price->getSym(); ?>" /> 
                                <tr>
                                    <td>Date</td><td><input type="text" readonly name="date" id="date" value="<?php echo $price->getDate(); ?>"</td>
                                    <td><span style="color: red; font-size: 12px;"><?php
                        if (isset($errors) && isset($errors['date'])) {
                            echo $errors['date'];
                        }
                                ?></span></td>

                                </tr>
                                <?php
                                break;
                            case 'dailyprice':
                                ?>
                                <!--                    set date as hidden-->
                                <input type="hidden" name="date" id="date" value="<?php echo $price->getDate(); ?>" />
                                <tr>
                                    <td>symbol</td><td><input type="text"  name="sym" id="sym" value="<?php echo $price->getSym(); ?>"</td>
                                    <td><span style="color: red; font-size: 12px;"><?php
                        if (isset($errors) && isset($errors['sym'])) {
                            echo $errors['sym'];
                        }
                                ?></span></td>

                                </tr>
                                <?php
                                break;

                            default:
                                break;
                        }
                        ?>


                        <tr>
                            <td>Aveg</td><td><input type="text" name="avg_vol" id="avg_vol" value="<?php echo $price->getAvgDaiVol(); ?>"</td>
                            <td><span style="color: red; font-size: 12px;"><?php
                if (isset($errors) && isset($errors['avg_vol'])) {
                    echo $errors['avg_vol'];
                }
                        ?></span></td>
                        </tr>
                        <tr>
                            <td>Wkhi</td><td><input type="text" name="wk_hi" id="wk_hi" value="<?php echo $price->getWkHi(); ?>"</td>
                            <td><span style="color: red; font-size: 12px;"><?php
                    if (isset($errors) && isset($errors['wk_hi'])) {
                        echo $errors['wk_hi'];
                    }
                        ?></span></td>
                        </tr>
                        <tr>
                            <td>WkLo</td><td><input type="text" name="wk_lo" id="wk_lo" value="<?php echo $price->getWkLo(); ?>"</td>
                            <td><span style="color: red; font-size: 12px;"><?php
                    if (isset($errors) && isset($errors['wk_lo'])) {
                        echo $errors['wk_lo'];
                    }
                        ?></span></td>
                        </tr>
                        <tr>
                            <td>Prev clo</td><td><input type="text" name="pre_clos" id="pre_clos" value="<?php echo $price->getPrevClos(); ?>"/></td>
                            <td><span style="color: red; font-size: 12px;"><?php
                    if (isset($errors) && isset($errors['pre_clos'])) {
                        echo $errors['pre_clos'];
                    }
                        ?></span></td>
                        </tr>
                        <tr>
                            <td>Mkt cap</td><td><input type="text" name="mkt_cap" id="mkt_cap" value="<?php echo $price->getMktCap(); ?>"/></td>
                            <td><span style="color: red; font-size: 12px;"><?php
                    if (isset($errors) && isset($errors['mkt_cap'])) {
                        echo $errors['mkt_cap'];
                    }
                        ?></span></td>
                        </tr>
                        <tr>
                            <td>open</td><td><input type="text" name="open" id="open" value="<?php echo $price->getOpen(); ?>"/></td>
                            <td><span style="color: red; font-size: 12px;"><?php
                    if (isset($errors) && isset($errors['open'])) {
                        echo $errors['open'];
                    }
                        ?></span></td>
                        </tr>
                        <tr>
                            <td ><input type="button" value="Close" name="close" onclick="_close()" /></td>
                            <td><input type="button" value="Update" name="update" onclick="_update()" />
                                <?php
                                switch ($page) {
                                    case 'search':
                                        ?>
                                        <input type="button" value="Remove" name="remove" onclick="_remove()" />
                                        <?php
                                        break;


                                    default:
                                        break;
                                }
                                ?>
                            </td>
                        </tr>
                    </table>


                </div> 
                <?php
            }
            break;
        case 'sym_remove': // if request remove
            $priceController = new PriceController();
            $priceController->DeletePriceRecordById();
            ?>
            <div style="border: 1px;" >
                <table>
                    <tr>
                        <td>Record remove was Successful</td>
                    </tr>
                    <tr>
                        <td>
                        <td ><input type="button" value="Close" name="close" onclick="_close()" /></td>
                        </td>
                    </tr>
                </table>
            </div>
            <?php
            break;
        case 'refresh_row':
            $id = $_REQUEST['id'];
            $price = new Price();
            $priceController = new PriceController();
            $price = $priceController->GetPriceDetailById();
            switch ($_REQUEST['page']) {
                case 'search':
                    ?>
                    <tr id="row_<?php echo $price->getId(); ?>" name="row_<?php echo $price->getId(); ?>" >
                        <td style="font-size: medium"><?php echo $price->getDate(); ?></td>
                        <td><input style="width: 40px" type="text" name="preclos_<?php echo $price->getId(); ?>" id="preclos_<?php echo $price->getId(); ?>" value="<?php echo $price->getPrevClos(); ?> " readonly/></td>
                        <td><input style="width: 40px" type="text" name="open_<?php echo $price->getId(); ?>" id="open_<?php echo $price->getId(); ?>" value="<?php echo $price->getOpen(); ?> " readonly/></td>
                        <td><input style="width: 40px" type="text" name="" id="" value="<?php echo $price->getWkHi(); ?> " readonly/></td>
                        <td><input style="width: 50px" type="text" name="" id="" value="<?php echo $price->getWkLo(); ?> " readonly/></td>
                        <td><input style="width: 50px" type="text" name="" id="" value="<?php echo $price->getMktCap(); ?> " readonly/></td>
                        <td><input style="width: 80px" type="text" name="" id="" value="<?php echo $price->getAvgDaiVol(); ?> " readonly/></td>

                        <td ><input type="button" value="Update/Remove" onclick="_infor(<?php echo $price->getId(); ?>)"></td>

                    </tr>
                    <?php
                    break;
                case 'dailyprice':
                    ?>
                    <tr id="row_<?php echo $price->getId(); ?>" name="row_<?php echo $price->getId(); ?>">
                        <td <?php if (!$price->getIsExistInSymbleTable()) { ?> style="background-color: #ff3333" <?php } ?>><?php echo $price->getSym(); ?></td>
                        <td><?php echo $price->getPrevClos(); ?></td>
                        <td><?php echo $price->getOpen(); ?></td>
                        <td><?php echo $price->getWkHi(); ?></td>
                        <td><?php echo $price->getWkLo(); ?></td>
                        <td><?php echo $price->getMktCap(); ?></td>
                        <td><?php echo $price->getAvgDaiVol(); ?></td>
                        <td <?php if (!$price->getIsExistInSymbleTable()) { ?> style="background-color: #ff3333" <?php } ?>><?php
                    if ($price->getIsExistInSymbleTable()) {
                        echo 'Yes';
                    } else {
                        echo 'No';
                    }
                    ?> </td>
                        <td ><input type="button" value="Update" onclick="_infor(<?php echo $price->getId(); ?>)"></td>
                    </tr>

                    <?php
                    break;

                default:
                    break;
            }
            break;

        case 'stock_review':// is stock review
            $catagory = $_REQUEST['catagory'];
            $id = $_REQUEST['id'];
            ?>
            <div>
                <table>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                    </tr>
                    <?php
                    switch ($catagory) {
                        case 0:
                            $securityTypeController = new SecurityTypeController();
                            $securityType = $securityTypeController->GetSecurityTypeByCode();
                            ?>


                            <tr>
                                <td><?php echo $securityType->getSecType(); ?></td>
                                <td><?php echo strip_tags($securityType->getTypeDesc()); ?></td>
                            </tr>


                            <?php
                            break;

                        case 1:
                            $gicsController = new GicsController();
                            $gics = $gicsController->GetGicsByCode();
                            ?>


                            <tr>
                                <td><?php echo $gics->getGicsCode(); ?></td>
                                <td><?php echo strip_tags($gics->getGicsDesc()); ?></td>


                            </tr>

                            <?php
                            break;
                        default:
                            break;
                    }
                    ?>
                    <tr>
                        <td>
                        <td ><input type="button" value="Close" name="close" onclick="_close()" /></td>
                        </td>
                    </tr> 
                </table>
            </div>
            <?php
            break;

        case 'user_list':
            $userController = new UserController();
            $loginUser = $_SESSION['loginSession'];
            $userType = $_REQUEST['userType'];
            $org = $loginUser->getOrganization();

            $accessRight = new AccessRight();
            $accessRight = $userController->GetAccessRight($loginUser);
            if ($accessRight->getCanUpdateAllOrganizations() && isset($_REQUEST['organization'])) {
                $org = $_REQUEST['organization'];
            }

            $hasRight = FALSE;
            switch ($userType) {
                case ApplicationKeyValues::$USER_TYPE_ADMIN:
                    $hasRight = $accessRight->getCanUpdateAdmins();
                    break;
                case ApplicationKeyValues::$USER_TYPE_TEACHER:
                    $hasRight = $accessRight->getCanUpdateTeachers();
                    break;
                case ApplicationKeyValues::$USER_TYPE_STUDENT:
                    $hasRight = $accessRight->getCanUpdateStudents();
                    break;
                default:
                    break;
            }

            $users = NULL;
            if ($hasRight && isset($userType)) {
                $users = $userController->GetUsersByTypeAndOrganization($userType, $org);
            }

            if ($users) {
                ?>
                <table class="altrowstable" id="alternatecolor">
                    <tr>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Create date</th>
                        <th>Status</th>
                        <th>&nbsp;</th>
                    </tr>
                    <?php
                    foreach ($users as $user) {
                        ?>
                        <tr>
                            <td><?php echo $user->getEmail(); ?></td>
                            <td><?php echo $user->getFirstName() . ' ' . $user->getLastName(); ?></td>
                            <td><?php echo $user->getCreateDate(); ?></td>
                            <td>
                                <select id="user_status_<?php echo $user->getId(); ?>" name="user_status" onchange="javascript:change_status('<?php echo $user->getId(); ?>');" >
                                    <option value="0" <?php if ($user->getUserStatus() == 0) { ?>  selected
                                    <?php } ?>
                                            >Active</option>
                                    <option value="1"
                                            <?php if ($user->getUserStatus() == 1) { ?>  selected
                                            <?php } ?>  
                                            >Inactive</option>
                                </select>
                            </td>
                            <td>
                                <a href="edit_profile.php?id=<?php echo $user->getId(); ?>">Edit</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
            } else {
                echo 'no records found';
            }
            break;

        case 'user_selection':
            $userController = new UserController();
            $loginUser = $_SESSION['loginSession'];
            $org = $_REQUEST['organization'];
            $users = NULL;

            $accessRight = new AccessRight();
            $accessRight = $userController->GetAccessRight($loginUser);

            if ($accessRight->getCanUpdateAdmins()) {
                echo $userController->SelectionMarkup($org, NULL, $errors);
            } else {
                echo 'no records found';
            }
            break;

        case 'contest_selection':
            $contestController = new ContestController();
            $loginUser = $_SESSION['loginSession'];
            $users = NULL;

            $accessRight = new AccessRight();
            $accessRight = $userController->GetAccessRight($loginUser);

            if ($accessRight->getCanUpdateAdmins()) {
                echo $userController->SelectionMarkup($org, NULL, $errors);
            } else {
                echo 'no records found';
            }
            break;

        case 'contest_list':
            $contestController = new ContestController();
            $loginUser = $_SESSION['loginSession'];
            $org = $loginUser->getOrganization();

            $accessRight = new AccessRight();
            $accessRight = $contestController->GetAccessRight($loginUser);
            if ($accessRight->getCanUpdateAllOrganizations() && isset($_REQUEST['organization'])) {
                $org = $_REQUEST['organization'];
            }

            $contests = NULL;
            if ($accessRight->getCanUpdateOwnOrganization()) {
                $contests = $contestController->GetContestsByOwnerOrganization($org);
            } else {
                $contests = $contestController->GetContestsByOwner($loginUser->getId());
            }

            if ($contests) {
                ?>
                <table class="altrowstable" id="alternatecolor">
                    <tr>
                        <th>Name</th>
                        <th>Owner</th>
                        <th>Date</th>
                        <th>&nbsp;</th>
                    </tr>
                    <?php
                    foreach ($contests as $contest) {
                        $owner = $contest->getOwnerObject();
                        ?>
                        <tr>
                            <td><?php echo $contest->getName(); ?></td>
                            <td><?php echo $owner->getFirstName() . ' ' . $owner->getLastName(); ?></td>
                            <td><?php echo $contest->getStartDate() . ' - ' . $contest->getEndDate(); ?></td>
                            <td>
                                <a href="edit_contest.php?id=<?php echo $contest->getId(); ?>">Edit</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
            } else {
                echo 'no records found';
            }
            break;

        case 'message_list':
            $messageController = new MessageController();
            $contestController = new ContestController();
            $loginUser = $_SESSION['loginSession'];

            $messages = NULL;
            $hasRight = FALSE;

            if (isset($_REQUEST['contest'])) {
                $contestId = $_REQUEST['contest'];
                $contest = $contestController->getContestById($contestId);
                if ($contest) $hasRight = $contest->getOwner() === $loginUser->getId();
            }
            if (!$hasRight) {
                $portfolioController = new PortfolioController();
                $portfolio = $portfolioController->GetOwnCurrentPortfolio();
                if ($portfolio) {
                    $contestId = $portfolio->getContest();
                    $hasRight = TRUE;
                }
            }

            if ($hasRight) {
                $messages = $messageController->GetMessagesByContest($contestId);
            }

            if ($messages) {
                foreach ($messages as $message) {
                    ?>
                    <div class="instructor-message">
                        <?php echo $message->getMessage(); ?><br><span class="message-date">Posted: <?php echo $message->getDate(); ?></span>
                    </div>
                    <?php
                }
            } else {
                echo 'no records found';
            }
            break;

        case 'get_portfolios':
            $portfolioController = new PortfolioController();
            $contestController = new ContestController();

            $contest = $contestController->GetContestById($_REQUEST['contest']);
            $owner = $contest->getOwnerObject();

            $loginUser = $_SESSION['loginSession'];
            $accessRight = $portfolioController->GetAccessRight($loginUser);

            $portfolios = NULL;
            $editLink = '';
            if ($accessRight->getCanUpdateOwnContests() && $owner->getId() === $loginUser->getId() || 
                    $accessRight->getCanUpdateOwnOrganization() && $owner->getOrganization() === $loginUser->getOrganization() || 
                    $accessRight->getCanUpdateAllOrganizations()) {
                $portfolios = $portfolioController->GetPortfoliosByContest($contest->getId());
                $editLink = '<a href="edit_contest_portfolios.php?id=' . $contest->getId() . '">Add or remove portfolios</a>';
            }

            if ($portfolios) {
                ?>
                <table class="altrowstable" id="alternatecolor">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                    <?php
                    foreach ($portfolios as $portfolio) {
                        $trader = $portfolio->getTraderObject();
                        ?>
                        <tr>
                            <td><?php echo $trader->getFirstName() . ' ' . $trader->getLastName(); ?></td>
                            <td><?php echo $trader->getEmail(); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
            } else {
                echo '<div>no records found</div>';
            }
            echo $editLink;
            break;

        default:
            break;
    }
}
?>

