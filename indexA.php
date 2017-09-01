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
echo 'The Intetnational Access number are ', $_REQUEST['code1'],  ' and ' ,$_REQUEST['code2'];
?>


<?php require 'footer.php'; ?>