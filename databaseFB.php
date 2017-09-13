<?php
use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\Table\Models\Entity;
use WindowsAzure\Table\Models\EdmType;

function addDBFB($uidfb) 
{
	require_once 'vendor\autoload.php';
	require 'db\connectionstr.php';
	
	// Create table REST proxy.
	$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);
	
	$entity = new Entity();
	$entity->setPartitionKey("InternShip");
    $entity->setRowKey($uidfb);
    
	$entity->addProperty("TimeStampServer", EdmType::INT32, -1);
	$entity->addProperty("Name", EdmType::STRING, "abc");
	$entity->addProperty("Age", EdmType::STRING, "abc");
	$entity->addProperty("Sex", EdmType::STRING, "abc");
	$entity->addProperty("Locale", EdmType::STRING,"abc");

    
	
	try{
		$tableRestProxy->insertEntity("CustomerDB", $entity);
	}
	catch(Exception $e){
		// Handle exception based on error codes and messages.
		// Error codes and messages are here:
		// http://msdn.microsoft.com/library/azure/dd179438.aspx
		$code = $e->getCode();
        $error_message = $e->getMessage();
        echo "Erro code: " . $code."\n";
        echo "Erro msg: " . $error_message."\n";
	}
}

function updateKeysFB($uidfb) 
{
	require_once 'vendor\autoload.php';
	require 'db\connectionstr.php';

	// Create table REST proxy.
	$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);
	try {
		$result = $tableRestProxy->getEntity("CustomerDB", "InternShip", $uidfb);

		$entity = $result->getEntity();
		
		$tableRestProxy->updateEntity("CustomerDB", $entity);
		
	} catch(Exception $e){
		// Handle exception based on error codes and messages.
		// Error codes and messages are here:
		// http://msdn.microsoft.com/library/azure/dd179438.aspx
		$code = $e->getCode();
		$error_message = $e->getMessage();

		addDBFB($uidfb);
	}	
}

function updateRowDatabaseFB($arrayRawUpdateData)
{
	require_once 'vendor\autoload.php';
	require 'db\connectionstr.php';

	// Create table REST proxy.
	$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);

	$result = $tableRestProxy->getEntity("CustomerDB", "InternShip", $arrayRawUpdateData["uid"]);

	$entity = $result->getEntity();

	$entity->setPropertyValue("TimeStampServer", $arrayRawUpdateData["TimeStampServer"]);
	$entity->setPropertyValue("Name", $arrayRawUpdateData["Name"]);
	$entity->setPropertyValue("Age", $arrayRawUpdateData["Age"]);
	$entity->setPropertyValue("Sex", $arrayRawUpdateData["Sex"]);
	$entity->setPropertyValue("Locale", $arrayRawUpdateData["Locale"]);

	

	
	
	try {	
		$tableRestProxy->updateEntity("CustomerDB", $entity);
	}
	catch(Exception $e){
		// Handle exception based on error codes and messages.
		// Error codes and messages are here:
		// http://msdn.microsoft.com/library/azure/dd179438.aspx
		$code = $e->getCode();
        $error_message = $e->getMessage();
	}
}

function readRowDatabaseFB($uidfb)
{
	require_once 'vendor\autoload.php';
	require 'db\connectionstr.php';

	//Create table REST proxy.
	$tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);
	$filter = "RowKey eq '" . $uidfb ."'";
	
	try {
		$result = $tableRestProxy->queryEntities("CustomerDB", $filter);
	} catch(ServiceException $e){
		// Handle exception based on error codes and messages.
		// Error codes and messages are here:
		// http://msdn.microsoft.com/library/azure/dd179438.aspx
		$code = $e->getCode();
        $error_message = $e->getMessage();

	}
    //echo "Error code:";// . $code; ."\n";
    //echo "Error msg:";// $error_messagecode "\n";
	$entities = $result->getEntities();
    
    //var_dump($entities);

	if(!empty($entities))
	{
		$arrayRawData["uid"] = $entities[0]->getProperty("RowKey")->getValue();
	//	$arrayRawData["key"] = $entities[0]->getProperty("SecretKey")->getValue();
		$arrayRawData["TimeStampServer"] = $entities[0]->getProperty("TimeStampServer")->getValue();
        $arrayRawData["Name"] = $entities[0]->getProperty("Name")->getValue();
        $arrayRawData["Age"] = $entities[0]->getProperty("Age")->getValue();
        $arrayRawData["Sex"] = $entities[0]->getProperty("Sex")->getValue();
        $arrayRawData["Locale"] = $entities[0]->getProperty("Locale")->getValue();
    
	

		return $arrayRawData;
	} 
	else 
	{
		$arrayRawData["uid"] = "";
		return $arrayRawData;
	}	

}

?>