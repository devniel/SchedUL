<?php
	session_start();
	header('P3P: CP="CAO PSA OUR"');
	if($_SESSION["authorized"]){
	$student = $_SESSION["student"];
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=100" > <!-- IE9 mode -->
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="schedule.js"></script>
<script type="text/javascript" src="table.js"></script>
<script type="text/javascript" src="save.js"></script>
<script type="text/javascript" src="schedule2picture.js"></script>
<script src="http://viralpatel.net/blogs/demo/jquery/get-text-without-child-element/jquery.justtext.1.0.js"></script>
<script type="text/javascript">
var me = true;
schedule(<?=$student->id?>,null,<?=$_SESSION['id']?>,"start");

$("#modify").live("click",function(){
			$("#modify").html("Guardar horario");
			$("#schedule").attr("contenteditable","true");
			$(this).attr("id","save");
	});


var courses = [];
	
	$("#save").live("click",function(){
		courses = [];
		courses = table(courses);
		
		data = JSON.stringify(courses);
		
		obj = {
			data: data
		};
			
			save(obj);
			
			FB.ui(
			   {
				 method: 'feed',
				 name: 'SchedUL',
				 link: 'http://apps.facebook.com/schedul/',
				 caption: 'Alpha. Soporte para Google Chrome y Firefox',
				 picture: 'http://stendev.com/images/horus3.png',
				 description: '<?=$student->name?> ha publicado su horario.',
				 message: 'Este es mi horario.'
			   },
			   function(response) {
				 
			   }
			);		
	});
	
	// FOR GET THE ID OF FRIEND
	
	$(".friend").live("click",function(){
		schedule($(this).attr("data-friend-id"),$(this).attr("data-friend-name"),<?=$_SESSION['id']?>);
		me = false;
	});
	
	$("#student").live("click",function(){
		schedule("<?=$student->id?>",null,"<?=$_SESSION['id']?>","std");
		me = true;
	});
	
	$("#schedule2picture").live("click",function(){
		if(me){
		 schedule2picture()
		  $("<div id='dialog'/>").css({
			 width : 300,
			 height : 150,
			 background : "url('img/ajax-loader.gif') no-repeat center #FFF",
			 border : "5px solid #CCC",
			 position: "absolute",
			 "margin-top" : -200,
			 "margin-left" : -150,
			 top : "50%",
			 left : "50%",
			 "border-radius": "5px",
			 display : "block",
			"box-shadow": "0px 0px 15px #666",
			"font" : "12px Tahoma"
		 }).appendTo($("body"));
		 
		 $("<div id='dialog_text'/>").css({
			 "padding-top" : "45px",
			 "padding-left" : "100px",
			 "font-weight" : "bold"
		 }).appendTo($("#dialog"));
		 
		 $("#dialog_text").text("Publicando foto ...");
		 
		 //alert("Desactivado temporalmente");
		}
	});
	
	// FOR INVITE FRIENDS

	$("#invite").live("click",function(){
		sendRequests();
	});
	
	function sendRequests() {
		FB.ui({
			method: 'apprequests',
			message: 'Comparte tu horario con SchedUL',
			data: 'Invitaciones'
		}, function(response) {
			if (response != null && response.request_ids && response.request_ids.length > 0) {
				for (var i = 0; i < response.request_ids.length; i++) {
				   // alert("Invitados: " + response.request_ids[i]);
				}
			} else {
				//alert('Invitaciones no enviadas');
			}
		});
	}
	
	$.ajax({
		type: "GET",
		url: "matches.php",
		success: function(data){
			 $("#matches").html(data);
			}
 		});
		
	$.ajax({
		type: "GET",
		url: "friends.php",
		success: function(data){
			 $("#friends-inner").html(data);
			}
 		});
		
	setInterval(function(){
		$.ajax({
		type: "GET",
		url: "matches.php",
		success: function(data){
			 $("#matches").html(data);
			}
 		});
	},60000);
	
	setInterval(function(){
		$.ajax({
		type: "GET",
		url: "friends.php",
		success: function(data){
			 $("#friends-inner").html(data);
			}
 		});
	},60000);
	
</script>
</head>
<body>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({appId: '101438079941277', status: true, cookie: true,
             xfbml: true});
	FB.Canvas.setAutoResize();
	
  };
  (function() {
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol +
      '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
  }());
</script>

<div id="header">
	<div id="logo">SchedUL <span class="version">&alpha;</span></div>
    <div id="schedule2picture">Publicar horario</div>
    <div id="student">Mi horario</div>
</div>

<div id="cont">
<table id="schedule" cellspacing="1" cellpadding="2" width="100%" contenteditable="true">
<thead>
	<tr contenteditable="false">
    	<th class="hour"><span>Hora</span></th>
        <th><span>Lun</span></th>
        <th><span>Mar</span></th>
        <th><span>Mier</span></th>
        <th><span>Jue</span></th>
        <th><span>Vie</span></th>
        <th><span>Sab</span></th>
    </tr>
</thead>
<tbody>
<?php
$sh = 7;
for($i=0;$i<=14;$i++){
	echo "<tr>";
	for($j=0;$j<=6;$j++){
		if($j == 0){
			$hour = $sh . "-" . ($sh + 1);
			echo "<th class='hour' contenteditable='false'><span>" . $hour . "</span></th>";
		}
		else {
			$day = $j;
			echo "<td data-shour='" . $sh . "' data-ehour='" . ($sh + 1) . "' data-day='" . $day . "'></td>";
		}
	}
	$sh++;
	echo "</tr>";
}
?>
</tbody>

</table>
<div style="clear:both"></div>
</div>

<div id="sidebar">	
	<div id="friend_schedule"></div>
	<button id="save">Guardar horario</button>
    <div id="friends">
    	<div class="friends-header">Horario de Amigos</div>
       	<div id="friends-inner"></div>
        <div style="clear:both"></div>
    </div>
    
    <div id="matches">
    	<div class="matches-header">¿Estudiaré con mis amigos?</div>
        
        <div style="clear:both"></div>
    </div>
    
    <div id="instr">
    	Copia el texto <strong>(desde Chrome o Firefox)</strong> de cada celda del horario que aparece en el apartado "Aula Virtual" de tu cuenta universitaria y pégalo en la casilla correspondiente, <strong>si no guarda</strong> actualiza la página (F5) y haz el procedimiento de nuevo.<br/><br/>
        
        <strong><span style="color:#F00">Nueva función :</span> Publicar horario</strong><br/>
        'Toma' una <strong>foto</strong> a tu horario y lo guarda en tus álbumes, si tienes suerte lo publica en tu muro. La publicación del horario está bajo tu responsabilidad.
        
        <br/><br/>Esta aplicación es solo para estudiantes de la <strong>Universidad de Lima</strong>, previa <a href="http://stendev.com/horus/isnotul.php">confirmación</a> de afiliación a la red de la universidad desde tu Facebook, así que es seguro :) <br/><br/> Si por alguna razón no carga enteramente tu horario, prueba a actualizar la página (F5). Errores de servidor ¬¬<br/><br/>Se recomienda el uso de <a href="http://www.google.com/chrome?hl=es-419&brand=CHJL&utm_campaign=es-419&utm_source=es-419-pe-ha-BKWS&utm_medium=ha">Google Chrome</a> o <a href="http://www.mozilla.com/en-US/firefox/fx/">Firefox 4</a>.
        <br/>
        <br/>
        <button id="invite">Invitar amigos</button>
        <br/>
        <br/>
        ¿Errores,sugerencias,comentarios?
        <br/>&raquo; <a href="http://www.facebook.com/apps/application.php?id=101438079941277" title="Schedul">Página de la aplicación</a>
    </div>
 <div style="clear:both"></div>   
</div>

<div id="footer"><a href="http://www.facebook.com/terms.php">Políticas de Privacidad de Facebook</a></div>                            
</body>
</html>
<?php
	}
	else{
		header("Location: http://stendev.com/horus");	
	}
?>