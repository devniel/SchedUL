<?php
session_start();
	if($_SESSION["confirmated"]){
			$_SESSION["authorized"] = true;
			$student = $_SESSION["student"];
			
			//Connect To Database
			$hostname='xxx';
			$username='xxx';
			$password='xxx';
			$dbname='xxx';
		
			mysql_connect($hostname,$username, $password) OR DIE ('Unable to connect to database! Please try again later.');
			
			mysql_select_db($dbname);
			
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
	}
?>
