<?php
session_start();

require("facebook.php");

function getUniqueCode($length = "")
{	
	$n = uniqid(rand(),true);
	$code = md5($n);
	if ($length != "") return substr($code, 0, $length);
	else return $code;
}

if (isset($GLOBALS["HTTP_RAW_POST_DATA"]))
{
	// Get the data
	$imageData=$GLOBALS['HTTP_RAW_POST_DATA'];

	// Remove the headers (data:,) part.  
	// A real application should use them according to needs such as to check image type
	$filteredData=substr($imageData, strpos($imageData, ",")+1);

	// Need to decode before saving since the data we received is already base64 encoded
	$unencodedData=base64_decode($filteredData);

	//echo "unencodedData".$unencodedData;

	$uniqueCode = getUniqueCode(10);
	$md5Code = md5($uniqueCode);
	
	// Save file.  This example uses a hard coded filename for testing, 
	// but a real application can specify filename in POST variable
	$fp = fopen( 'schedules/' . $md5Code . '.png', 'wb' );
	fwrite( $fp, $unencodedData);
	fclose( $fp );
	
	
	$facebook = new Facebook(array(
		  'appId'  => '101438079941277',
		  'secret' => 'cd110fd1d80dad9f5c6c74f7cc0f13d3',
		  'cookie' => true,
		));
		
	
	$img = realpath("schedules/" . $md5Code . ".png");
	// allow uploads
	$facebook->setFileUploadSupport("http://" . $_SERVER['SERVER_NAME']);
	// add a status message
	$photo = $facebook->api('/me/photos', 'POST',
			array(
					'source' => '@' . $img,
					'message' => 'Mi SchedUL : http://apps.facebook.com/schedul/'
			)
	);
	
	echo $photo["id"];
	
	//echo "Foto publicada en tu muro :" . $photo;
	
	unlink("schedules/" . $md5Code . ".png");
	
	//echo "http://stendev.com/horus/schedules/" . $_SESSION["id"] . ".png";
}
?>