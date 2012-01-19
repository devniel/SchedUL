<?php
session_start();

$id = $_SESSION["id"];

$_SESSION["friends"];

//Connect To Database
			$hostname='xxx';
			$username='xxx';
			$password='xxx';
			$dbname='xxx';
	
mysql_connect($hostname,$username, $password) OR DIE ('FAIL');
mysql_select_db($dbname);

$query = mysql_query("select student.id, course.id course, course.name name, student.type from schedules student, courses course where student.id = '" . mysql_real_escape_string($id) . "' and course.id = student.course;");

$rows = array();

while($r = mysql_fetch_assoc($query)){
	$rows[] = $r;	
}

$courses = array();

foreach($rows as $course){
	$courses[$course["name"]] = array();
}

foreach($rows as $course){
	
	foreach($_SESSION["friends"] as $friend){
		
		$query = mysql_query("SELECT * FROM schedules WHERE id = '" . mysql_real_escape_string($friend->id) . 
							"' AND course = '" . mysql_real_escape_string($course["course"]) . 
							"' AND type = '" . mysql_real_escape_string($course["type"]) . "';");
							
		/*$query = mysql_query("SELECT * FROM schedules WHERE id = '" . mysql_real_escape_string($friend->id) . 
							"' AND course = '" . mysql_real_escape_string($course["course"]) . "';");*/
							
		//$query = mysql_query("SELECT * FROM schedules WHERE course = '" . mysql_real_escape_string($course["course"]) . "';");
							
							
		
		if(mysql_num_rows($query) != 0){
			array_push($courses[$course["name"]],$friend->id);
		}
	}	
}

//print_r($courses);

//echo $i;

/*if($i == 0){
		echo "<span class='ad'>No coincides con ninguno de tus amigos.</span>";
}
else {
$cont = "";
$ids = array();
// Delete repeats.
foreach($courses as $course=>$students){
	$ids[$course] = array();
	foreach($students as $student){
		$h = 0;
		$found = false;
		while($h < count($ids[$course]) && $found == false){
			if($ids[$course][$h] == $student["id"]){
				$found = true;
			}
		}
		
		if(!$found){
			array_push($ids[$course], $student["id"]);	
		}
	}	
}*/

foreach($courses as $course=>$students){
	if(count($students) > 0){
		$cont.="<div class='match'>";
		$cont.="<header>&nbsp;" . $course . ".</header>";
		foreach($students as $sid){
			$std_url = "https://graph.facebook.com/" .  $sid;
			$stdt = json_decode(file_get_contents($std_url));
			$cont.="<div class='friend' data-friend-id='" . $sid . "' data-friend-name='" . $stdt->name . "'>
			<img src='https://graph.facebook.com/" . $sid . "/picture'></div>";
		}
		$cont.="<div style='clear:both'></div></div>";	
	}	
}
	
/*foreach($ids as $course => $sids){
	if(count($sids) > 0){
		$cont.="<div class='match'>";
		$cont.="<header>&nbsp;" . $course . ".</header>";
		foreach($sids as $sid){
			$std_url = "https://graph.facebook.com/" .  $sid;
			$stdt = json_decode(file_get_contents($std_url));
			$cont.="<div class='friend' data-friend-id='" . $sid . "' data-friend-name='" . $stdt->name . "'>
			<img src='https://graph.facebook.com/" . $sid . "/picture'></div>";
		}
		$cont.="<div style='clear:both'></div></div>";	
	}
}*/

if($cont != ""){
	echo $cont;
}
else {
	echo "<span class='ad'>&nbsp; No coincides con ninguno de tus amigos.</span>";
}

//print_r($courses);

//print_r($matches);
//}
?>