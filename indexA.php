<?php require 'header.php'; ?>


<?php
echo 'Your PASS is ,' , $_REQUEST['pw'];
?>
<br>
<?php
echo 'Hello ,' , $_REQUEST['user'];
?>
<br>
<?php
echo 'These Inteternational Access number are ', $_REQUEST['code1'], <br> ' and ' , $_REQUEST['code2'] ;
?>


<?php require 'footer.php'; ?>