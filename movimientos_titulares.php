<?

session_start ();
if ($_SESSION ["m_sesion"] != 1) {
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit ();
}
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<title>Sistema de Nomina de Empleados</title>
<link rel="shortcut icon" type="image/x-icon"
href="http://www.michoacan.gob.mx/wp-content/themes/mich2015/img/favicon.ico">

<link rel="stylesheet" type="text/css" href="css/estilos.css">
<link rel="stylesheet" type="text/css" href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<script src="controles_jquery/js/jquery-1.9.1.js"></script>
<script src="controles_jquery/js/jquery-ui-1.10.3.custom.js"></script>
	
</head>
<body topmargin="0" leftmargin="0">
	<div id="todo">
		<div id="cabeza_prin"></div>
		<div id="tituloarriba">
			<div id="titulosup">Movimientos Titulares</div>
		</div>
		<div id="cuerpo">
			<div id="panelizq">
					<?
					include ("menu.php");
					?>
				</div>
			<div id="centro_prin"></div>
		</div>
		<div id="tituloabajo">
			<div id="tituloinf">Movimiento Titular</div>
		</div>
	</div>
</body>
</html>