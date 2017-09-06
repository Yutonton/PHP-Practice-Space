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
	$flagTamper = substr($rawData, 14, 2);
	$timeStampTag = (double)hexdec(substr($rawData, 16, 8));

	$rollingCodeTag = substr($rawData, 24, 8);
	require_once "database.php";
	$rawRowData = readRowDatabase($uid);
	require_once "keystream.php";
	$rollingCodeServer = keystream(hexbit($rawRowData["key"]), hexbit(substr($rawData, 16, 8)), 4);

	if((strlen($rawRowData["key"]) == 20) && ($rollingCodeServer === $rollingCodeTag)){
		$judge="correct";
	}else{
		$judge="incorrect";}




	
	echo "UID is " .$uid ; 		
	echo "<br>";
	echo "TamperStatus is " .$flagTamper ;
	echo "<br>";
	echo "TimeStamp is" .$timeStampTag ;
	echo "<br>";
	echo "RollingCode is " .$rollingCodeTag ;
	echo "<br>";
	echo "RollingCode from server is " .$rollingCodeServer ;
	echo "<br>";
	echo "This RolligCode is " .$judge ;
	
?>


<?php require 'footer.php'; ?>