<?
session_start();
if($_SESSION["m_sesion"] != 1)
{
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit();
}
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link type="image/x-icon" href="imagenes/logomich200.ico" rel="icon" />
<link type="image/x-icon" href="imagenes/logomich200.ico" rel="shortcut icon" />
<title>Sistema de N&oacute;mina de Empleados</title>




<link rel="stylesheet" type="text/css" href="css/estilos.css">

</head>
<body topmargin="0" leftmargin="0">
<div id="todo">
	<div id="cabeza_prin">
    </div>
    <div id="tituloarriba">
   		<div id="titulosup">CFDI individual</div>    
    </div>
    <div id="cuerpo">
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
                <div id="cfdi" style="margin: 10px auto; width: 350px; padding-top: 50px;">
                	<iframe name="factura" id="factura" src="http://nomina.cetic.michoacan.gob.mx/xml_nominacetic/nominaindividual.cfm" style="width: 400px; height: 200px; border: 0px;"></iframe>
                </div>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">CFDI individual</div>    
    </div>
</div>
</body>
</html>