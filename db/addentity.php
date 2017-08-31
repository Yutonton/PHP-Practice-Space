<html>
<head>
<title>Add Entity </title>
</head>
	<head>
	<title>Add Entity </title>
	</head>
	<h1>Add Entity Page</h1>
<body>
	
<?php

	require_once '..\vendor\autoload.php';
	require 'connectionstr.php';

	use WindowsAzure\Common\ServicesBuilder;
	use WindowsAzure\Common\ServiceException;
	use WindowsAzure\Table\Models\Entity;
	use WindowsAzure\Table\Models\EdmType;

	// The value of the variable name is found
	echo "<h1>Row = " . $_GET["UID"] . "</h1>";
	 
	// The value of the variable age is found
	echo "<h1>Value = " . $_GET["TS"] . "</h1>";
	
	// Create table REST proxy.
	$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);

	$entity = new Entity();
	$entity->setPartitionKey("SIC_Demo");
	$entity->setRowKey($_GET["UID"]);
	$entity->addProperty("TagTimeStamp",EdmType::INT64, $_GET["TS"]);
	$entity->addProperty("LastOKTimeStamp",
						 EdmType::DATETIME,
						 new DateTime());
	$entity->addProperty("LastNGTimeStamp",
						 EdmType::DATETIME,
						 new DateTime());
	$entity->addProperty("NGType",EdmType::STRING, "Unknown");
	$entity->addProperty("TamperStatus",EdmType::STRING, "Unknown");
	
	echo "<h1>Value = ";
	try{
		$tableRestProxy->insertEntity("SIC43NTUIDTSTBL", $entity);
		echo "<h1>Member complete";
	}
	catch(ServiceException $e){
		// Handle exception based on error codes and messages.
		// Error codes and messages are here:
		// http://msdn.microsoft.com/library/azure/dd179438.aspx
		$code = $e->getCode();
		$error_message = $e->getMessage();
		echo "<h1>Member fail";
	}
?>

</body>'
</html>