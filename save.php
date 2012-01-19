<?php
session_start();

$courses =  stripslashes($_POST["data"]);

echo $courses;

$obj = json_decode($courses);

//Connect To Database
			$hostname='xxx';
			$username='xxx';
			$password='xxx';
			$dbname='xxx';
	
$link = mysql_connect($hostname,$username, $password) OR DIE ('Unable to connect to database! Please try again later.');
mysql_select_db($dbname,$link);

mysql_query("SET SQL_SAFE_UPDATES=0");

foreach( $obj as $courses){
			
		// Seach if the course exists or the name;
		// $obj[0][0] -> $courses[0]
		$search = mysql_query("SELECT name FROM courses WHERE id = '" . $courses[0]->code . "';");
		
		if(mysql_num_rows($search) == 0){
			$insert = mysql_query("INSERT INTO courses (id,name) values ('" . $courses[0]->code . "','" . $courses[0]->name . "');");
		}
	
		// We have the name $course->name and code $course->code
		// Search the schedule
		$founded = false;
		
		// GET AVAILABLE TYPES
		// GET LAS TYPE
		$last_type = mysql_query("SELECT type FROM types where id = '" . $courses[0]->code . "' ORDER BY type DESC;");
		$ltype = mysql_fetch_row($last_type);
		$ltype = (int)$ltype[0];
		//echo $ltype;
		
		while($ltype >= 1 && $founded == false){
			
			$types = mysql_query("SELECT * FROM types where id = '" .$courses[0]->code . "' AND type = '" . $ltype . "';");
			
			$r = null;
			$row = null;
			
			while($r = mysql_fetch_assoc($types)){
				$row[] = $r;
			}
			
			//print_r($row);
			$q = 0;
			$found = 0;
			//echo "----------------------------\n";
			while($q<count($courses)){
				for($t=0;$t<count($row);$t++){
					/*echo $courses[$q]-> classroom . " " . $row[$t]["classroom"] . "";
					echo $courses[$q]-> shour. " " . $row[$t]["shour"] . "";
					echo $courses[$q]-> ehour . " " . $row[$t]["ehour"] . "";
					echo $courses[$q]-> day . " " . $row[$t]["day"] . "";
					echo "############\n";*/
					
					if($courses[$q]->classroom == $row[$t]["classroom"] && 	$courses[$q]->shour == $row[$t]["shour"] && 
						$courses[$q]->ehour == $row[$t]["ehour"] &&	$courses[$q]->day == $row[$t]["day"]){
						
						//echo "COINCIDENCIA";
						$found++;
						$type_schedule = $row[$t]["type"]; // WE HAVE TYPE_SCHEDULE
					}
				}
				$q++;
			}
			
			//echo $found . "\n";
			//echo $type_schedule;
			
			if($found == count($courses) && $found == count($row)){
				$founded = true;
			}
			
			$ltype--;
		}
		
		
		//INSERT TYPE
		if(!$founded){					
			// SEARCH LAST TYPE OF THIS COURSE
			$ntype = mysql_query("SELECT type FROM types WHERE id = '" . $courses[0]->code . "' ORDER BY type DESC;");
					
			if(mysql_num_rows($ntype) == 0) {
				$type_schedule = 1; // FIRST TYPE FOR THE COURSE
			}
			else {
				$row = mysql_fetch_row($ntype);
				$type_schedule = (int)$row[0] + 1; // A NEW TYPE;
			}
			
			for($j=0;$j<count($courses);$j++){	
				
			$newtype = mysql_query("INSERT INTO types (id,type,classroom,shour,ehour,day) VALUES('"
									. $courses[$j]->code . "','"
									. $type_schedule . "','"
									. $courses[$j]->classroom . "','"
									. $courses[$j]->shour . "','"
									. $courses[$j]->ehour . "','"
									. $courses[$j]->day . "');");
			}
		}
		
		// WE HAVE TYPE SCHEDULE.

		// WE HAVE CODE AND TYPE
			$search = mysql_query("SELECT * FROM schedules WHERE id = '" . $_SESSION["id"] . "' AND course = '" . $courses[0]->code . "';");
			if(mysql_num_rows($search) != 0){ // EXISTS
				mysql_query("SET SQL_SAFE_UPDATES=0");
				$query = mysql_query("UPDATE schedules SET type = '" . $type_schedule . "' WHERE id = '" . $_SESSION["id"] . "' AND course = '" . $courses[0]->code . "'",$link);
			}
			else{ // DONT EXISTS
				$query = mysql_query("INSERT INTO schedules(id,course,type) values('" . $_SESSION["id"] . "','" . $courses[0]->code . "','" . $type_schedule . "');");
			}
			
}


	$query = mysql_query("SELECT * FROM schedules WHERE id = '" . $_SESSION["id"] . "'",$link);
	$size = mysql_num_rows($query);
	while($row=mysql_fetch_assoc($query)) {
		   $return[] = $row;
	}
	
	$h = 0;
	for($h;$h<$size;$h++){
		$found = false;
		$j=0;
		while($j<count($obj) && $found == false){
			if($return[$h]["course"] == $obj[$j][0]->code){
				$found = true;
			}
			$j++;
		}
		if($found == false){
			mysql_query("SET SQL_SAFE_UPDATES=0");
			$query = mysql_query("DELETE from schedules where id = '" . $_SESSION["id"] . "' AND course = '" . $return[$h]["course"] . "';");	
		}
	}


?>