<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Confirmation</title>
<style type="text/css">
#container {
	width:760px;
	height:800px;
}

#email {
	width:400px;
	height:200px;
	margin-top:-200px;
	margin-left:-200px;
	position:absolute;
	top:50%;
	left:50%;
	font:12px "Tahoma";
	color:#333
}

input {
	padding:5px;
	border:2px solid #CCC;
	box-shadow:0px 0px 1px #EAEAEA;
	border-radius:3px;
	position:absolute;
	top:50%;
	left:50%;
	margin-top:-15px;
	margin-left:-150px;
	width:300px;
	height:30px;
}
</style>

<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
window.onload = function(){
	 
	$("input[name='email']").keypress(function(e){
				code= (e.keyCode ? e.keyCode : e.which);
				if (code == 13) sendForm("email");
	});
	
	$("input[name='code']").keypress(function(e){
				code= (e.keyCode ? e.keyCode : e.which);
				if (code == 13) sendForm("code");
	});
			
	function sendForm(type){
		$.ajax({
			   type: "POST",
			   url: "mail.php",
			   data: (type=='email')?"email=" + $("input[name='email']").val():"code=" + $("input[name='code']").val(),
			   success: function(msg){
				   if(type == "email"){
					   if(msg == "error"){
							alert("Email incorrecto");   
					   }else{
					   		$("input[name='email']").toggle('slow');	
					   }
				   }else{
					   if(msg == "true"){
					   		 window.location.href = "connect.php";
					   }else{
							alert("Código incorrecto");   
					   }
				   }
			   }
		});
	}
}
</script>
</head>

<body>
<div id="container">
	<div id="email">
    <h4 style="margin-bottom:3px">SchedUL</h4>
    Por razones de seguridad esta aplicación es solo para estudiantes de la Universidad de Lima, por lo que se enviará un código de confirmación a tu correo.
    <form method="post" action="null" onsubmit="return false;">
    	<input type="text" name="code" placeholder="Escribe el código recibido" />
    	<input type="email" name="email" placeholder="Ingresa tu correo universitario y dale enter"/>
    </form>

	</div>
</div>
</body>
</html>
