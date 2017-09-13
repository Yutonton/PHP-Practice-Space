<?php
use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\Table\Models\Entity;
use WindowsAzure\Table\Models\EdmType;

function addDB($uid, $key) 
{
	require_once 'vendor\autoload.php';
	require 'db\connectionstr.php';
	
	// Create table REST proxy.
	$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);
	
	$entity = new Entity();
	$entity->setPartitionKey("SIC43NT_Demo");
	$entity->setRowKey($uid);
	$entity->addProperty("SecretKey", EdmType::STRING, $key);
	$entity->addProperty("TimeStampServer", EdmType::INT32, -1);
	$entity->addProperty("RollingCodeServer", EdmType::STRING, "00000000");
	$entity->addProperty("TamperFlag", EdmType::STRING, "00");
	$entity->addProperty("TamperStatusOpened", EdmType::STRING, "false");
	$entity->addProperty("CountTimeStampError", EdmType::INT32, 0);
	$entity->addProperty("CountRollingCodeError", EdmType::INT32, 0);
	$entity->addProperty("CountRollingCodeOK", EdmType::INT32, 0);
	$entity->addProperty("LastCountTimeStampError", EdmType::DATETIME, new DateTime());
	$entity->addProperty("LastCountRollingCodeError", EdmType::DATETIME, new DateTime());
    $entity->addProperty("LastCountRollingCodeOK", EdmType::DATETIME, new DateTime());
    
    $entity->addProperty("OwnerID", EdmType::INT32,NULL);
	
	try{
		$tableRestProxy->insertEntity("DatabaseSIC43NT", $entity);
	}
	catch(Exception $e){
		// Handle exception based on error codes and messages.
		// Error codes and messages are here:
		// http://msdn.microsoft.com/library/azure/dd179438.aspx
		$code = $e->getCode();
        $error_message = $e->getMessage();
	}
}

function updateKeys($uid, $key) 
{
	require_once 'vendor\autoload.php';
	require 'db\connectionstr.php';

	// Create table REST proxy.
	$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);
	try {
		$result = $tableRestProxy->getEntity("DatabaseSIC43NT", "SIC43NT_Demo", $uid);

		$entity = $result->getEntity();

		$entity->setPropertyValue("SecretKey", $key); //Modified Time Stamp.
		
		$tableRestProxy->updateEntity("DatabaseSIC43NT", $entity);
		
	} catch(Exception $e){
		// Handle exception based on error codes and messages.
		// Error codes and messages are here:
		// http://msdn.microsoft.com/library/azure/dd179438.aspx
		$code = $e->getCode();
		$error_message = $e->getMessage();

		addDB($uid, $key);
	}	
}

function updateRowDatabase($arrayRawUpdateData)
{
	require_once 'vendor\autoload.php';
	require 'db\connectionstr.php';

	// Create table REST proxy.
	$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);

	$result = $tableRestProxy->getEntity("DatabaseSIC43NT", "SIC43NT_Demo", $arrayRawUpdateData["uid"]);

	$entity = $result->getEntity();

	$entity->setPropertyValue("TimeStampServer", $arrayRawUpdateData["TimeStampServer"]);
	$entity->setPropertyValue("RollingCodeServer", $arrayRawUpdateData["RollingCodeServer"]);
	$entity->setPropertyValue("TamperFlag", $arrayRawUpdateData["TamperFlag"]);
	$entity->setPropertyValue("TamperStatusOpened", $arrayRawUpdateData["TamperStatusOpened"]);
	$entity->setPropertyValue("CountTimeStampError", $arrayRawUpdateData["CountTimeStampError"]);
	$entity->setPropertyValue("CountRollingCodeError", $arrayRawUpdateData["CountRollingCodeError"]);
	$entity->setPropertyValue("CountRollingCodeOK", $arrayRawUpdateData["CountRollingCodeOK"]);
	$entity->setPropertyValue("LastCountTimeStampError", $arrayRawUpdateData["LastCountTimeStampError"]);
	$entity->setPropertyValue("LastCountRollingCodeError", $arrayRawUpdateData["LastCountRollingCodeError"]);
	$entity->setPropertyValue("LastCountRollingCodeOK", $arrayRawUpdateData["LastCountRollingCodeOK"]);

	$entity->setPropertyValue("OwnerID", $arrayRawUpdateData["OwnerID"]);
	
	try {	
		$tableRestProxy->updateEntity("DatabaseSIC43NT", $entity);
	}
	catch(Exception $e){
		// Handle exception based on error codes and messages.
		// Error codes and messages are here:
		// http://msdn.microsoft.com/library/azure/dd179438.aspx
		$code = $e->getCode();
        $error_message = $e->getMessage();
        echo "Error code:"; //. $code; ."\n";
        echo "Error msg:"; // $error_messagecode "\n";
	}
}

function readRowDatabase($uid)
{
	require_once 'vendor\autoload.php';
	require 'db\connectionstr.php';

	//Create table REST proxy.
	$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);
	$filter = "RowKey eq '" . $uid ."'";
	
	try {
		$result = $tableRestProxy->queryEntities("DatabaseSIC43NT", $filter);
	} catch(ServiceException $e){
		// Handle exception based on error codes and messages.
		// Error codes and messages are here:
		// http://msdn.microsoft.com/library/azure/dd179438.aspx
		$code = $e->getCode();
		$error_message = $e->getMessage();
	}

	$entities = $result->getEntities();
	
	if(!empty($entities))
	{
		$arrayRawData["uid"] = $entities[0]->getProperty("RowKey")->getValue();
		$arrayRawData["key"] = $entities[0]->getProperty("SecretKey")->getValue();
		$arrayRawData["TimeStampServer"] = $entities[0]->getProperty("TimeStampServer")->getValue();
		$arrayRawData["RollingCodeServer"] = $entities[0]->getProperty("RollingCodeServer")->getValue();
		$arrayRawData["TamperFlag"] = $entities[0]->getProperty("TamperFlag")->getValue();
		$arrayRawData["TamperStatusOpened"] = $entities[0]->getProperty("TamperStatusOpened")->getValue();
		$arrayRawData["CountTimeStampError"] = $entities[0]->getProperty("CountTimeStampError")->getValue();
		$arrayRawData["CountRollingCodeError"] = $entities[0]->getProperty("CountRollingCodeError")->getValue();
		$arrayRawData["CountRollingCodeOK"] = $entities[0]->getProperty("CountRollingCodeOK")->getValue();
		$arrayRawData["LastCountTimeStampError"] = $entities[0]->getProperty("LastCountTimeStampError")->getValue();
		$arrayRawData["LastCountRollingCodeError"] = $entities[0]->getProperty("LastCountRollingCodeError")->getValue();
        $arrayRawData["LastCountRollingCodeOK"] = $entities[0]->getProperty("LastCountRollingCodeOK")->getValue();
        
        $arrayRawData["OwnerID"] = $entities[0]->getProperty("OwnerID")->getValue();

		return $arrayRawData;
	} 
	else 
	{
		$arrayRawData["uid"] = "";
		return $arrayRawData;
	}	

}

?>