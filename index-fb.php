<html>
   <head>
      <title>Online PHP Script Execution</title>      
   </head>
   
   <body>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.10&appId=1909305535989351";
        fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

      <?php
        if (isset($_GET['d'])) {
            echo "<h1>Hello, PHP! : Value is " .$_GET['d'] ."</h1>";
            $uid = $_GET['d'];
        }else{
            // Fallback behaviour goes here
            echo "<h1>Hello, PHP! no parameter</h1>";
        }
        
        echo "start fb 001 <br>";
        phpinfo();
        require_once '/vendor/autoload.php';
        try {
        $fb = new \Facebook\Facebook([
            'app_id' => '1909305535989351',
            'app_secret' => '61db0b81059abc688802c1c2dac13101',
            'default_graph_version' => 'v2.10',
        ]);
        }catch(Exception $e){
            echo 'Caught exception:',$e->getMessage(),"\n";
        }
        
        echo "start fb 002 <br>";

        $authenUser = $_SERVER['HTTP_X_MS_CLIENT_PRINCIPAL_NAME'];
        //echo "<h1>Logged in as $authenUser</h1>";
        $headers = getallheaders();
        $accessToken = $headers['X-Ms-Token-Facebook-Access-Token'];   
        echo "start fb 003 <br>";

        try {
        // Returns a `Facebook\FacebookResponse` object
        $response = $fb->get('/me?fields=id,name,picture', $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
        }

        $user = $response->getGraphUser();

        echo '<h2>Name    : ' . $user['name'] .'</h2>';
        $profile_picture = $user['picture'];
        echo '<img src="' . $profile_picture['url'] . '" alt="Mountain View" style="width:50px;height:50px;">';
        echo '<h2>UID     : ' . $uid .'</h2>';     
        
      ?>
   <div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></div>
   </body>
</html>