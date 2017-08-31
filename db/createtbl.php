<html>
<head>
<title>Create Table </title>
</head>
<body>

<?php
	require_once '..\vendor\autoload.php';
	require 'connectionstr.php';
	use WindowsAzure\Common\ServicesBuilder;
	use WindowsAzure\Common\ServiceException;
	echo "Connect to Table Storage...";
	
	// Create table REST proxy.
	$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);
	//echo $connectionString;
	echo "<br>Start to Create new Table";
	try {
		// Create table.
		$tableRestProxy->createTable("DatabaseSIC43NT");
		echo "Table had been created";
	}
	catch(Exception $e){
		$code = $e->getCode();
		$error_message = $e->getMessage();
		echo $error_message;
		// Handle exception based on error codes and messages.
		// Error codes and messages can be found here:
		// http://msdn.microsoft.com/library/azure/dd179438.aspx
	}
?>

</body>
</html>