﻿<?php require 'header.php'; ?>



 <section id="bodyflame">
 
	<div id="fb-root"></div>
		<script>(function(d, s, id) {
  			var js, fjs = d.getElementsByTagName(s)[0];
  			if (d.getElementById(id)) return;
  			js = d.createElement(s); js.id = id;
		  	js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.10&appId=280487102433701";
            fjs.parentNode.insertBefore(js, fjs);
           }(document, 'script', 'facebook-jssdk'));
		</script>


  
<?php

       		 require_once '/vendor/autoload.php';

			$fb = new Facebook\Facebook([
				'app_id' => '280487102433701',
				'app_secret' => 'a35095e11b54fa8a131234feeb6852c0',
				'default_graph_version' => 'v2.10',
				]);
			
		
   	    	$authenUser = $_SERVER['HTTP_X_MS_CLIENT_PRINCIPAL_NAME'];
        	$headers = getallheaders();
        	$accessToken = $headers['X-Ms-Token-Facebook-Access-Token'];   

        	try {
        		$response = $fb->get('/me?fields=id,name,picture', $accessToken);
        	} catch(Facebook\Exceptions\FacebookResponseException $e) {
        		echo 'Graph returned an error: ' . $e->getMessage();
        		exit;
        	} catch(Facebook\Exceptions\FacebookSDKException $e) {
        		echo 'Facebook SDK returned an error: ' . $e->getMessage();
        		exit;
        	}

        	$user = $response->getGraphUser();
?>
        <h1 class="summer">This Product has been opened</h1>
	<	<p> <?php	// echo 'Hello : ' . $user['id'] ;?></p>



	<!--	<h1 class="summer">SUMMER CAMPAIGN</h1>

		<h2>Earn Points & Get Free Drink</h2> -->
		
		<?php	//$profile_picture = $user['picture'];
		
        //	echo '<img src="' . $profile_picture['url'] . '" alt="Profile Image" style="width:60px;height:60px;">';     
       ?>

 	
      


	  <p></p>
<!--
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
	/*	$store=[
			'Tokyo'=>081, 'Bangkok'=>066, 'Washington'=>011
		];
		foreach ($store as $key=>$value) {
			echo '<option value="', $value, '">', $key, '</option>';
		} 
	*/?>
</select>



<p><input type="submit" value="GO!!"></p>
</form>
-->
<?php
/*	$rawData = strtoupper($_GET['d']);
	$uid = substr($rawData, 0, 14);
	$flagTamper = substr($rawData, 14, 2);
	$timeStampTag = (double)hexdec(substr($rawData, 16, 8));
	
//	echo "debug1";
//	echo "\nDebug";
//	echo "\nrawData:".$rawData;
//	echo "\nid:".$uid;

//	var_dump($uid);
	$rollingCodeTag = substr($rawData, 24, 8);
	require_once "database1.php";
	
//	echo "debug2";

	require_once "databaseFB.php";

//	echo "debug3";

	$rawRowData = readRowDatabase($uid);
	require_once "keystream.php";
	$rollingCodeServer = keystream(hexbit($rawRowData["key"]), hexbit(substr($rawData, 16, 8)), 4);



	if((strlen($rawRowData["key"]) == 20) && ($rollingCodeServer === $rollingCodeTag)){
		$judge="correct";
	}else{
		$judge="incorrect";}
		

	//echo $rawRowData["OwnerID"];
	// if(empty($rawRowData["OwnerID"])){

	
/*	if($flagTamper=="AA" && empty($rawRowData["OwnerID"]))
	{
		 //SIC43NTDB
		 $rawRowData["OwnerID"]=$user['id']; 
		 updateRowDatabase($rawRowData);

		 //CustomerDB
		 $uidfb = $user['id'];
		 $rawRowDataFB = readRowDatabaseFB($uidfb);
	//	 var_dump ($rawRowDataFB);
		 if(empty($rawRowDataFB["uid"]))
		 {
			echo "\ndebug001";
			addDBFB($uidfb);
	//		echo "\ndebug002";
		 }
	//	 echo $rawRowData["OwnerID"];

		 $rawRowDataFB = readRowDatabaseFB($uidfb);
	//	 echo "\ndebug003";
		 $rawRowDataFB["Name"]=$user['name']; 
		 $rawRowDataFB["Age"]=$user['age_range']; 
		 $rawRowDataFB["Sex"]=$user['gender']; 
		 $rawRowDataFB["Locale"]=$user['locale']; 
	
	//	 echo "\ndebug004";
		 updateRowDatabaseFB($rawRowDataFB);
	//	 echo "\ndebug005";

	

	}else{
	
	<meta http-equiv="Refresh" content="15;URL=http://www.example.com/">

	} */



/*	echo "UID is " .$uid ; 		
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
*/	
?>





	<div class="fb-share-button" data-href="https://internsilicon01.azurewebsites.net/" data-layout="box_count" data-size="large" data-mobile-iframe="true">
		<a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Finternsilicon01.azurewebsites.net%2F&amp;src=sdkpreparse">Share</a>
	</div>


	<a href="https://twitter.com/share" class="twitter-share-button" data-size="large">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
	
	</section>

<?php require 'footer.php'; ?>



