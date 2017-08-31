<html>
<head>
<title>Read Table </title>
</head>
	<head>
	<title>Read string </title>
	</head>
	<h1>Read string Page</h1>
<body>
	
<?php

	require_once '..\vendor\autoload.php';
	require 'connectionstr.php';
	
	use WindowsAzure\Common\ServicesBuilder;
	use WindowsAzure\Common\ServiceException;

	// Create table REST proxy.
	//echo $connectionString
	$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);

	$filter = "PartitionKey eq 'SIC_Demo'";

	try {
		$result = $tableRestProxy->queryEntities("SIC43NTUIDTSTBL", $filter);
	}
	catch(ServiceException $e){
		// Handle exception based on error codes and messages.
		// Error codes and messages are here:
		// http://msdn.microsoft.com/library/azure/dd179438.aspx
		$code = $e->getCode();
		$error_message = $e->getMessage();
		echo $code.": ".$error_message."<br />";
	}

	$entities = $result->getEntities();

	foreach($entities as $entity){
		echo $entity->getPartitionKey().":".$entity->getRowKey().":".$entity->getProperty("TagTimeStamp")->getValue()."<br />";
	}
?>

</body>
</html>