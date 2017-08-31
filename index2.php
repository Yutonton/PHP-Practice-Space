<?php
	if( !(isset($_GET['d'])) || (empty($_GET['d'])) || (strlen($_GET['d']) != 32) || !(ctype_xdigit($_GET['d'])))
	{
		header('Location: http://www.sic.co.th/index.php', true, 302);
		//echo "<h1>Invalid Parameter#1</h1><br/>";
		//echo "<h1>". $_GET['d'] ."</h1>";
		exit(0);
	}
	else
	{
		$rawData = strtoupper($_GET['d']);
		$uid = substr($rawData, 0, 14);
		$flagTamper = substr($rawData, 14, 2);
		if(substr($uid, 0, 4) != "3949")
		{
			header('Location: http://www.sic.co.th/index.php', true, 302);
			//echo "<h1>Invalid Parameter#2</h1><br/>";
		    //echo "<h1>". $_GET['d'] ."</h1>";
			exit(0);
		}

		$timeStampTag = (double)hexdec(substr($rawData, 16, 8)) ;
		$rollingCodeTag = substr($rawData, 24, 8);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>SIC43NT Demonstration</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<!--[if !mso]-->
	<!--Windows phone fix-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--<![endif]-->
	<link rel="shortcut icon" href="http://sic43nt.blob.core.windows.net/resource/images/favicon.ico">
</head>
<body>
	<div class="container">
		<div id="content">
			<div class="jumbotron text-center">
				<a href="http://www.sic.co.th/index.php">
					<img class="img-rounded" src="http://sic43nt.blob.core.windows.net/resource/images/logo.png" alt="Silicon Craft Technology"/>
					<h3><b>Silicon Craft Technology</b></h3>
				    <h5>SIC43NT Demonstration 55555</h5>
				</a>
			</div>
			<div class="text-center">
				<?php
					require_once "database.php";
					$rawRowData = readRowDatabase($uid);
					if(!empty($rawRowData["uid"]))
					{
						$rawRowData["TimeStampServer"] = (double)$rawRowData["TimeStampServer"];
						if($rawRowData["TimeStampServer"] >= ((double)(4294967295 - 255)))
						{
							$rawRowData["TimeStampServer"] = (double)-1;
						}
						if($timeStampTag > $rawRowData["TimeStampServer"])
						{
							require_once "keystream.php";
							$rollingCodeServer = keystream(hexbit($rawRowData["key"]), hexbit(substr($rawData, 16, 8)), 4);
							if((strlen($rawRowData["key"]) == 20) && ($rollingCodeServer === $rollingCodeTag))
							{
								echo "<div class=\"alert alert-success\" role=\"alert\">";
								echo "<h4>Genuine NDEF message</h4><br/>";
								echo "<h5>UID[7] : " . $uid . "</h5>";
								$flagClearTamperStatus = FALSE;
								if($rawRowData["TamperStatusOpened"] == "false")
								{
									if($flagTamper == "AA")
									{
										$flagClearTamperStatus = TRUE;
										$rawRowData["TamperStatusOpened"] = "true";
										echo "<h5>Tamper Status from server : Opened</h5>";
									}
									else
									{
										$flagClearTamperStatus = FALSE;
										echo "<h5>Tamper Status from server : Sealed</h5>";
									}
								}
								else
								{
									$flagClearTamperStatus = TRUE;
									echo "<h5>Tamper Status from server : Opened</h5>";
								}

								echo "<h5>Tamper Flag : " . $flagTamper . "</h5>";
								echo "<h5>Time Stamp(on tag side) : " . $timeStampTag . "</h5>";
								echo "<h5>Time Stamp(on server side) : " . $rawRowData["TimeStampServer"] . "</h5>";
								echo "<h5>Rolling Code : " . substr($rawData, 16, 16) . "</h5>";
								echo "</div>";

								$rawRowData["TimeStampServer"] = $timeStampTag;
								$rawRowData["RollingCodeServer"] = $rollingCodeServer;
								$rawRowData["TamperFlag"] = $flagTamper;
								$rawRowData["LastCountRollingCodeOK"] = new DateTime();
								if($rawRowData["CountRollingCodeOK"] < (2147483647 - 100))
								{
									$rawRowData["CountRollingCodeOK"] = $rawRowData["CountRollingCodeOK"] + 1;
								}
								
								updateRowDatabase($rawRowData);

								if($flagClearTamperStatus == TRUE)
								{
									echo "<form class=\"form-horizontal\" action=\"cleartamper.php\" method=\"post\">
											<div class=\"form-group\">        
												<input type=\"hidden\" name=\"d\" value=\"" . $rawRowData["uid"] . "\">
												<input type=\"hidden\" name=\"r\" value=\"". $rawRowData["RollingCodeServer"] ."\">
											</div>
											<button type=\"submit\" class=\"btn btn-primary\">Clear Tamper Status from Server</button>
										</form>";
								}

							} 
							else 
							{
								if($rawRowData["CountRollingCodeError"] < (2147483647 - 100))
								{
									$rawRowData["CountRollingCodeError"] = $rawRowData["CountRollingCodeError"] + 1;
								}
								$rawRowData["LastCountRollingCodeError"] = new DateTime();
								updateRowDatabase($rawRowData);
								echo "<br/>
									 <div class=\"alert alert-danger\" role=\"alert\">
									 <h4>Invalid NDEF message</h4>
									 <h4>Please try again</h4><br/>
									 <h5>UID[7] : " . $uid . "</h5>
									 </div>";
							}
						}
						else
						{
							if($rawRowData["CountTimeStampError"] < (2147483647 - 100))
							{
								$rawRowData["CountTimeStampError"] = $rawRowData["CountTimeStampError"] + 1;
							}
							$rawRowData["LastCountTimeStampError"] = new DateTime();
							updateRowDatabase($rawRowData);
							echo "<br/>
								 <div class=\"alert alert-danger\" role=\"alert\">
								 <h4>Obsolete NDEF message</h4>
								 <h4>Please try again</h4><br/>
								 <h5>UID[7] : " . $uid . "</h5>
								 </div>";
						}
					}
					else
					{
						echo "<br/>
						      <div class=\"alert alert-danger\" role=\"alert\">
							  <h4>UID is not found</h4><br/>
							  <h4>Please try again</h4><br/>
							  <h5>UID[7] : " . $uid . "</h5>
							  </div>";
					}
				?>
			</div>
		</div>
	</div>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<style type="text/css">body{background-image: url("http://sic43nt.blob.core.windows.net/resource/images/background.jpg");}</style>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
			ga('create', 'UA-86745015-1', 'auto');
			ga('send', 'pageview');
	</script>	
</body>
</html>