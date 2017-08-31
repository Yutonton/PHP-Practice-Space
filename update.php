<?php
    if((strlen($_GET["uid"]) == 14) && (strlen($_GET["key"]) == 20))
    {
        require_once "database.php";
        updateKeys($_GET["uid"], $_GET["key"]);
    }
    else
    {
        http_response_code(400);
    }
?>
