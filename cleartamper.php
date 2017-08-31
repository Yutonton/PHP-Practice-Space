<?php
    $flagValidParameter = FALSE;
    if( !(isset($_POST['d'])) || (empty($_POST['d'])) || (strlen($_POST['d']) != 14) || !(ctype_xdigit($_POST['d'])) ||
        !(isset($_POST['r'])) || (empty($_POST['r'])) || (strlen($_POST['r']) != 8) || !(ctype_xdigit($_POST['r'])) )
    {
        $flagValidParameter = FALSE;
    }
    else
    {
        $uid = strtoupper($_POST['d']);
        $rollingCode = strtoupper($_POST['r']);
        if(substr($uid, 0, 4) == "3949")
		{
            $flagValidParameter = TRUE;
		}
        else
        {
            $flagValidParameter = FALSE;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>SIC43NT Clear Tamper</title>
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
				    <h5>SIC43NT Clear Tamper</h5>
				</a>
			</div>
			<div class="text-center">
				<?php
                    if($flagValidParameter == TRUE)
                    {
                        require_once "database.php";
                        $rawRowData = readRowDatabase($uid);
                        if( !(empty($rawRowData["uid"])) && ($rawRowData["RollingCodeServer"] === $rollingCode) )
                        {
                            if($rawRowData["TamperStatusOpened"] == "true")
                            {
                                $rawRowData["TamperStatusOpened"] = "false";
                            }
                            
                            updateRowDatabase($rawRowData);

                            echo "<div class=\"alert alert-success\" role=\"alert\">
                                    <h4>Clear Tamper</h4><br/>
                                    <h4>Success</h4><br/>
                                </div>";
                        }
                        else
                        {
                            echo "<br/>
                                <div class=\"alert alert-danger\" role=\"alert\">
                                    <h4>UID/RC is not found</h4><br/>
                                    <h4>Please try again</h4><br/>
                                </div>";
                        }
                    }
                    else
                    {
                        echo "<br/>
                                <div class=\"alert alert-danger\" role=\"alert\">
                                    <h4>Error</h4><br/>
                                    <h4>Please try again</h4><br/>
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