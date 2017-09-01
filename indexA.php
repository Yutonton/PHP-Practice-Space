<?php require 'header.php'; ?>


<br>
<?php
echo 'Hello ,' , $_REQUEST['user'];
?>
<br>
<?php
echo 'These Inteternational Access number are ', $_REQUEST['code1'],  ' and ' , $_REQUEST['code2'] ;
?>

<?php
$pass=$_REQUEST['pw'];
if (preg_match('/^[0-9]{7}$/', $pass)) {
	echo 'OK , Your Pass "', $pass, '" is correrct';
} else {
	echo $pass, ' is not correct';
}
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