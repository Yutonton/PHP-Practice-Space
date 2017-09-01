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
echo 'These Inteternational Access number are ', $_REQUEST['code1'],  ' and ' , $_REQUEST['code2'] ;
?>

<?php
$file='board.txt';
if (file_exists($file)) {
	$board=json_decode(file_get_contents($file));
}
$board[]=$_REQUEST['user'];
file_put_contents($file, json_encode($board));
foreach ($board as $user) {
	echo '<p>', $user, '</p><hr>';
}
?>

<?php require 'footer.php'; ?>