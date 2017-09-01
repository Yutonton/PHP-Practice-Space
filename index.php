<?php require 'header.php'; ?>

</form>

<form action="#" >
PASS:<input type="password" name="pw" maxlength="5" />
</form>

<p>Input Your Name</p>
<form action="indexA.php" method="post">
<input type="text" name="user" size="25">
<input type="submit" value="GO!!">
<br>
<p>Choose place</p>
<br>
<select name="code">
<option value="100">Tokyo</option>
<option value="101">Bangkok</option>
<option value="102">Washington</option>
</select>
<p><input type="submit" value="SELECT"></p>
</form>

<?php require 'footer.php'; ?>