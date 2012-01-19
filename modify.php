<?php
session_start();

$courses =  stripslashes($_POST["data"]);

$obj = json_decode($courses);

//Connect To Database
			$hostname='xxx';
			$username='xxx';
			$password='xxx';
			$dbname='xxx';
	
mysql_connect($hostname,$username, $password) OR DIE ('Unable to connect to database! Please try again later.');
mysql_select_db($dbname);

$delete = "DELETE from schedules where id='" . $_SESSION["id"] . "';";

if($delete){
	foreach( $obj as $course){
		$query = "INSERT INTO schedules(id,code,name,classroom,hour,day,thours) VALUES('"
		. $_SESSION["id"] . "','"
		. $course->code . "','"
		. $course->name . "','"
		. $course->classroom . "','"
		. $course->hour . "','"
		. $course->day . "','"
		. $course->thours . "');";
		
		$result = mysql_query($query);
	}
	
	echo $courses;
}

echo "Borrado?";

?>