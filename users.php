<?php
$loginUser = new User();
$userController = new UserController();
//$loginUser = unserialize($_SESSION['loginSession']);
$loginUser = $_SESSION['loginSession'];

$accessRight = new AccessRight();
$accessRight = $userController->GetAccessRight($loginUser);

?>
<script type="text/javascript" >
    function change_status(id)
    {
        var e = document.getElementById("user_status_"+id);
        var status = e.options[e.selectedIndex].value;
      
        $.ajax({
      
            type: "POST",
            url: "ajax_help.php?id="+id+"&status="+status,
            success: function (result) {
              
            }
        }); 
        
    }

    function get_user_list() {
        var e = $('#organization');
        var org = e.val();

        $('#user_list').remove();
        $.ajax({
            type: 'GET',
            url: 'ajax_help.php?flag=user_list&userType=' + <?php echo $userType ?> + '&organization=' + org,
            success: function(result) {
                $('fieldset').append('<div id="user_list">' + result + '</div>');
                altRows('alternatecolor');
                if (org) {
                    var registerLink = $('#register-link').attr('href');
                    if (registerLink.indexOf('?') >= 0) registerLink = registerLink.substring(0, registerLink.indexOf('?'));
                    $('#register-link').attr('href', registerLink + '?organization=' + org);
                }
            }
        });
    }
</script>
<div id="form-wrapper">
    <div><a id="register-link" href="register_<?php echo $typeLabel; ?>.php">Add</a></div>
    <?php if ($userType === ApplicationKeyValues::$USER_TYPE_STUDENT) { ?>
        <div><a id="register-link" href="import_students.php">Import from file</a></div>
    <?php } ?>
    <div>
        <fieldset>
            <legend><?php echo ucfirst($typeLabel); ?>s</legend>
            <?php
            if ($accessRight->getCanUpdateAllOrganizations()) {
                $orgController = new OrganizationController();
                echo $orgController->SelectionMarkup($loginUser->getOrganization(), $errors);
            }
            ?>
        </fieldset>
    </div>     
</div>
<script>
    $('#organization').change(get_user_list);
    get_user_list();
</script>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = ucfirst($typeLabel) . 's';
include 'master.php';
?>
