<?php require 'header.php'; ?>

<?php
echo 'Hello ,' , $_REQUEST['user'];
?>
<br>
<?php
echo 'Code are ', $_REQUEST['code1'],  ' and ' ,$_REQUEST['code2'];
?>


<?php require 'footer.php'; ?>