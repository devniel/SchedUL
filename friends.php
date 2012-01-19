<?php
session_start();
header('P3P: CP="CAO PSA OUR"');

//Connect To Database
			$hostname='xxx';
			$username='xxx';
			$password='xxx';
			$dbname='xxx';
	
mysql_connect($hostname,$username, $password) OR DIE ('Unable to connect to database! Please try again later.');
mysql_select_db($dbname);

$i = 0;

$data = "";

foreach($_SESSION["friends"] as $friend){
	$query = mysql_query("SELECT * FROM schedules WHERE id = '" . $friend->id . "';");
	if(mysql_num_rows($query) != 0){
		$data.="<div class='friend' data-friend-id='" . $friend->id . "' data-friend-name='" . $friend->name . "'>
		<img src='https://graph.facebook.com/" . $friend->id . "/picture'></div>";
		$i++;
	}
}

if($i == 0){
	echo "<span class='ad'>&nbsp;Ningún amigo ha guardado su horario.</span>";
}
else{
	echo $data;
}

?>
