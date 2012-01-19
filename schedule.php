<?php
session_start();

$secure = false;

$id = $_GET["id"];

if($id == $_SESSION["id"]){
	$secure = true;
}else{
	foreach($_SESSION["friends"] as $friend){
		if($id == $friend->id){
			$secure = true;
			break;
		}
	}	
}

if($secure){
//Connect To Database
			$hostname='xxx';
			$username='xxx';
			$password='xxx';
			$dbname='xxx';

	mysql_connect($hostname,$username, $password) OR DIE ('Unable to connect to database! Please try again later.');
	mysql_select_db($dbname);
	
	// FROM MY FATHER :) SAMUEL FLORES
	
	$query = mysql_query("select course.id as code, course.name ,schtype.classroom, schtype.day, schtype.shour, schtype.ehour from `courses` `course`,`types` `schtype`, `schedules` `student` WHERE student.id = '" . $id . "' AND course.id = schtype.id AND schtype.id = student.course AND schtype.type = student.type;");
	
	if(!$query){
		echo "null";
	}
	
	$rows = array();
	while($r = mysql_fetch_assoc($query)){
		$rows[] = $r;	
	}
	
	print json_encode($rows);
}else{
	print "¬¬ Error";
}