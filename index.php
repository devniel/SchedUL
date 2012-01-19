<?php
session_start();
header('P3P: CP="CAO PSA OUR"');	



require 'facebook.php';
	
$app_id = '155712214488482';

$canvas_page = "http://apps.facebook.com/horus_offline/";

$auth_url = "http://www.facebook.com/dialog/oauth?client_id=" . $app_id . "&redirect_uri=" . urlencode($canvas_page) . "&scope=publish_stream";

$signed_request = $_REQUEST["signed_request"];

list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

    if (empty($data["user_id"])) {
            echo("<script> top.location.href='" . $auth_url . "'</script>");
    } 
	else 
	{

		// Create our Application instance (replace this with your appId and secret).
		$facebook = new Facebook(array(
		  'appId'  => '155712214488482',
		  'secret' => '3eadc89535ea8ac74567ecbb14e83d28',
		  'cookie' => true,
		));
		
		
		
		$accessToken = $facebook->getAccessToken();
		// $session is only != null, when you have the session-cookie, that is set by facebook, after the user logs in
		$session = $facebook->getSession();
	
		$_SESSION["facebook"] = $facebook;
		
		//echo($session);

		$_SESSION["token"] = $data["oauth_token"];
		
		//$graph_url = "https://graph.facebook.com/" . $data["user_id"] . "?access_token=" . $data["oauth_token"];
		
		$student = json_decode(json_encode($facebook->api('/me?access_token=' . $accessToken)));
		
		//print_r($student);
		
		$_SESSION["id"] = $student->id;
		
		$_SESSION["name"] = $student->name;
		
		$_SESSION["student"] = $student;
		
		$friends = $facebook->api('/me/friends?access_token=' . $accessToken );
		$friends = json_encode($friends);
		$friends = json_decode($friends);
		
		$_SESSION["friends"] = $friends->data;
		
		$_SESSION["authorized"] = false;
		
		//Connect To Database
			$hostname='xxx';
			$username='xxx';
			$password='xxx';
			$dbname='xxx';
		
		mysql_connect($hostname,$username, $password) OR DIE ('Unable to connect to database! Please try again later.');
		mysql_select_db($dbname);
		
		$query = mysql_query("SELECT * FROM students WHERE id = " . mysql_real_escape_string($_SESSION["id"]) );
		
		/*echo $_SESSION["id"] . "<br/>";
		echo mysql_real_escape_string($_SESSION["id"]) . "<br/>";
		echo mysql_num_rows($query);*/
		
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		
		if(mysql_num_rows($query) > 0){
			$_SESSION["authorized"] = true;
			$extra = 'page.php';
			header("Location: http://$host$uri/$extra");
		}else{
			$fql = "SELECT affiliations FROM user WHERE uid = " . $_SESSION["id"];
			
			$response = $facebook->api(array(
			'method' => 'fql.query',
			'query' =>$fql,
			));
			
			$objeto = json_decode(json_encode($response));
			$isUL = false;
			
			foreach($objeto[0]->affiliations as $affiliation){
				if($affiliation->name == "Universidad de Lima"){
					$isUL = true;
					break;	
				}
			}
			
			if($isUL){
					$_SESSION["authorized"] = true;				
					$extra = 'connect2.php';
					include("connect2.php");
			}else{
				 	$extra = 'isnotul.php';
					header("Location: http://stendev.com/horus/isnotul.php");
			}
		}
			
			
			
	}
?>
