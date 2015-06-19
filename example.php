<?php
include_once 'init.php'; /* load requried pages and initialized */
if(isset($_POST['submit']))
{
   $sampleMapper=new SampleMapper();
$sampleMapper->Insert($_REQUEST['txt']); 
}

?>
<script >
function _get()
{
    var txt=document.getElementById('txt').value;
    alert(txt);
}
</script>
<html>

    <body>
         <form action="<?php // $_SERVER['PHP_SELF'] ?>" method="post">
        <textarea id="txt" name="txt"></textarea>
        <input type="button" name="submit" value="submit" onclick="_get();"/>
        </form>
    </body>
</html>

<?php
//include_once 'init.php'; /* load requried pages and initialized */

//   $sampleMapper=new SampleMapper();
//   $txt=$sampleMapper->get(); 

?>

<html>

<!--    <body>
         <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
             <textarea><?php echo $txt; ?></textarea>
        
        </form>
    </body>-->
</html>
