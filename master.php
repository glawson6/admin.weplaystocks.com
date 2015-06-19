<?php
if (isset($_SESSION['loginSession'])) {
    $loginSession = new User();
//    $loginSession = unserialize($_SESSION['loginSession']);
    $loginSession = $_SESSION['loginSession'];
} else {
    $loginSession = NULL;
}

$globalVarsController = new GlobalVariablesController();
$globalVars = $globalVarsController->GetGlobalVariables();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8;" />
        <title><?php echo $globalVars->getSystemName(); ?><?php if (isset($pagetitle)) echo " | " . $pagetitle; ?></title>
        <link href="contents/styles/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="contents/js/jquery-1.7.2.min.js"></script>
        
        <script type="text/javascript">

            function altRows(className){
                /*if(document.getElementsByTagName){  
                    var table = document.getElementById(id);  
                    var rows = table.getElementsByTagName("tr"); 
                    for(i = 0; i < rows.length; i++){          
                        if(i % 2 == 0){
                            rows[i].className = "evenrowcolor";
                        }else{
                            rows[i].className = "oddrowcolor";
                        }      
                    }
                }*/

                $('.' + className).each(function() {
                    $(this).find('tr').each(function(i) {
                        $(this).addClass((i % 2 ? 'odd' : 'even') + 'rowcolor');
                    });
                });
            }

            window.onload=function(){
                //altRows('alternatecolor');
                altRows('altrowstable');
            }
        </script>
    </head>
    <body >
        <div id="big-wrapper">
            <div id="header">
                <img src="contents/image/logo.png">
                <?php
                if (isset($loginSession)) {
                    ?>
                    <div id="welcome-message">
                        <h2>Welcome Back, <?php echo $loginSession->getFirstName() . ' ' . $loginSession->getLastName() . '!'; ?></h2>
                        <h4><?php echo $loginSession->getOrganizationName() ?></h4>
                    </div>
                    <a href="logout.php"><div id="logout-button" class="nav-item">LOGOUT</div></a>
                    <?php
                }
                ?>
            </div>
            <div id="nav">
                <?php
                if (isset($loginSession)) {
                    ?>
                    <a href="edit_profile.php"><div class="nav-item nav-tab">PROFILE</div></a>
                    <?php
                    switch ($loginSession->getUserType()) {
                        case ApplicationKeyValues::$USER_TYPE_SUPER_ADMIN:
                            ?><a href="organizations.php"><div class="nav-item nav-tab">ORGS</div></a><?php
                            ?><a href="contests.php"><div class="nav-item nav-tab">CONTESTS</div></a><?php
                            ?><a href="admins.php"><div class="nav-item nav-tab">ADMINS</div></a><?php
                            ?><a href="teachers.php"><div class="nav-item nav-tab">TEACHERS</div></a><?php
                            ?><a href="students.php"><div class="nav-item nav-tab">STUDENTS</div></a><?php
                            ?><a href="home.php"><div class="nav-item nav-tab">DASHBOARD</div></a><?php
                            break;
                        case ApplicationKeyValues::$USER_TYPE_ADMIN:
                            ?><a href="messages.php"><div class="nav-item nav-tab">MESSAGES</div></a><?php
                            ?><a href="edit_organization.php"><div class="nav-item nav-tab">MY ORG</div></a><?php
                            ?><a href="contests.php"><div class="nav-item nav-tab">CONTESTS</div></a><?php
                            ?><a href="teachers.php"><div class="nav-item nav-tab">TEACHERS</div></a><?php
                            ?><a href="students.php"><div class="nav-item nav-tab">STUDENTS</div></a><?php
                            ?><a href="home.php"><div class="nav-item nav-tab">DASHBOARD</div></a><?php
                            break;
                        case ApplicationKeyValues::$USER_TYPE_TEACHER:
                            ?><a href="messages.php"><div class="nav-item nav-tab">MESSAGES</div></a><?php
                            ?><a href="contests.php"><div class="nav-item nav-tab">CONTESTS</div></a><?php
                            ?><a href="students.php"><div class="nav-item nav-tab">STUDENTS</div></a><?php
                            ?><a href="summary.php"><div class="nav-item nav-tab">SUMMARY</div></a><?php
                            break;
                        case ApplicationKeyValues::$USER_TYPE_STUDENT:
                            ?><a href="messages.php"><div class="nav-item nav-tab">MESSAGES</div></a><?php
                            ?><a href="trades.php"><div class="nav-item nav-tab">TRADES</div></a><?php
                            ?><a href="stocks.php"><div class="nav-item nav-tab">STOCKS</div></a><?php
                            ?><a href="summary.php"><div class="nav-item nav-tab">SUMMARY</div></a><?php
                            break;
                        default:
                            break;
                    }
                } else {
                    ?><a href="login.php"><div class="nav-item nav-tab">LOGIN</div></a><?php
                }
                ?>
            </div>
            <div id="content-wrapper">
                <?php
                if (isset($pagecontent)) {
                    echo $pagecontent;
                }
                ?>
                <div class="clear"></div>
                <div id="footer">Copyright &copy; 2014 The Community Foundation for Financial Literacy | www.thecommunityfoundation-ffl.org</div>
            </div>
        </div>
        <script type="text/javascript" src="contents/js/global.js"></script>
    </body>
</html>
