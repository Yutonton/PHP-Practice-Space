<!DOCTYPE html>
<html>
<body background="http://www.sic.co.th/sic4310/NDEF/bkg-tag.jpg">

<h1>Calculate</h1>

<table style="width:80%">
	  <tr>
		<form>
			<td>Key Encoder:<br>
			<input type="text" name="key">
			<br><br></td>

			<td>Time Stamp:<br>
			<input type="text" name="tStamp">
			<br><br></td>
			
		<form action="calculate.php">
			<td><input type="submit" value="Calculate"></td>
		</form>	
		
		<form action="index.php">
			<td><input type="submit" value="Back"></td>
		</form>
	  </td>
	  
	  <td>
		<?php
			include 'keystream.php';

			$key = $_GET["key"];
			$iv = $_GET["tStamp"];

			perform_test($key, $iv);

			function perform_test ($key, $iv)
			{
				/* Generate keystream */
				$keystream = keystream (hexbit($key), hexbit($iv), 16);

				/* Display the key */
				echo "KEY: ";
				print_r($key);
				echo "<br><br>";
				
				/* Display the IV */
				echo "IV: ";
				print_r($iv);
				echo "<br><br>";
				
				/* Display the derived keytream */
				echo "Result: ";
				print_r($keystream);
				echo "<br>";
				
			}
		?>
	  </td></tr>
</table>



</body>
</html>