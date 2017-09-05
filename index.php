<?php require 'header.php'; ?>

</form>


<form action="indexA.php" method="post">
PASS(7 numbers):<input type="password" name="pw" maxlength="5" />
<p>Input Your Name</p>
<input type="text" name="user" size="25">

<br>
<p>Choose place</p>
<br>

<select name="code1">
<option value="081">Tokyo</option>
<option value="066">Bangkok</option>
<option value="011">Washington</option>
</select>


<select name="code2">
<?php
$store=[
	'Tokyo'=>081, 'Bangkok'=>066, 'Washington'=>011
];
foreach ($store as $key=>$value) {
	echo '<option value="', $value, '">', $key, '</option>';
}
?>
</select>



<p><input type="submit" value="GO!!"></p>
</form>

<?php
	$rawData = strtoupper($_GET['d']);
	$uid = substr($rawData, 0, 14);



	echo "UID is " .$uid; <br>	

	
?>


<?php require 'footer.php'; ?>