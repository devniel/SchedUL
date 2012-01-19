<?php
session_start();
header('P3P: CP="CAO PSA OUR"');
if($_SESSION["authorized"]){
			$student = $_SESSION["student"];
			
			//Connect To Database
			$hostname='xxx';
			$username='xxx';
			$password='xxx';
			$dbname='xxx';
		
			mysql_connect($hostname,$username, $password) OR DIE ('Unable to connect to database! Please try again later.');
			
			mysql_select_db($dbname);
			
			// SNIPPET FROM : http://tttony.blogspot.com/2009/01/php5-mysql5-y-los-charsets.html
mysql_query("SET NAME 'utf8'");
mysql_query("SET CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
			
			$query = 'INSERT INTO students(id,name) VALUES ("' . $student->id . '","' . $_SESSION["name"] . '");';
			
			$result = mysql_query($query);
			
			//echo $result;
		
			if (!$result) {
				// Registered user. UNIQUE is used so, this returns nothing.
				include("page.php");
				//include("page.php");	
				//header('Location: http://www.example.com/');
			}
			else {
				include("page.php");
			}
}else{
	header("Location: http://stendev.com/horus");	
}
?>