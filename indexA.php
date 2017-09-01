<?php require 'header.php'; ?>

<?php
echo 'Hello ,' , $_REQUEST['user'];
?>

<?php
echo '店舗コードは', $_REQUEST['code'], 'です。';
?>


<?php require 'footer.php'; ?>