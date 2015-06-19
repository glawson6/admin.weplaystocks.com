<?php
include_once 'init.php';
if (!isset($_SESSION['loginSession'])) {
    header('location:logout.php');
}

$messageController = new MessageController();
$loginUser = $_SESSION['loginSession'];
$accessRight = $messageController->GetAccessRight($loginUser);
$canAdd = $accessRight->getCanUpdateOwnContests();

print_r($errors);

if ($canAdd && isset($_POST['submit'])) {
    $errors = array();
    $messageController->AddMessage();
}

?>
<script type="text/javascript" >
    function get_message_list() {
        var e = $('#contest');
        var contest = e ? e.val() : '';

        $.ajax({
            type: 'GET',
            url: 'ajax_help.php?flag=message_list&contest=' + contest,
            success: function(result) {
                $('#message-list').remove();
                $('fieldset').append('<div id="message-list">' + result + '</div>');
                addMessageListeners();
            }
        });
    }
</script>
<div id="form-wrapper">
    <div>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
            <fieldset>
                <legend>Messages</legend>
                <?php
                if ($canAdd) {
                    $contestController = new ContestController();
                    echo $contestController->SelectionMarkup($_REQUEST['contest']);
                    ?>
                    <div>
                        <textarea name="message"<?php if(isset($errors) && isset($errors['message'])){ ?> style="border: 1px solid red;" <?php } ?>><?php if(isset ($_REQUEST['message'])) { echo $_REQUEST['message']; } ?></textarea>
                        <span class="error"><?php if(isset($errors) && isset($errors['message'])){ echo $errors['message']; } ?></span>
                    </div>
                    <div>
                        <input type="submit" name="submit" value="Post Message" />
                    </div>
                    <?php
                }
                ?>
            </fieldset>
        </form>
    </div>     
</div>
<script>
    $('#contest').change(get_message_list);
    get_message_list();
</script>
<?php
$pagecontent = ob_get_contents();
ob_end_clean();
$pagetitle = "Messages";
include 'master.php';
?>

