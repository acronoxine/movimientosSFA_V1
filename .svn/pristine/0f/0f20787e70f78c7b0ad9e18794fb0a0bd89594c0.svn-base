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
<?php 
require_once('Connections/conexion.php'); 
mysql_select_db($database_conexion, $conexion);

if(isset($_POST["fecha"]))
{
	
	$update_sql="update empleado_plaza set fecha_inicial='$_POST[fecha]', 
	 			fecha_final=NULL where idnominaemp=$_POST[idnominaemp]";
	mysql_query($update_sql,$conexion);

	$update_empleado="update nominaemp set activo=0, estatus=$_POST[movimiento],fechabaja='$_POST[fecha]' where idnominaemp=$_POST[idnominaemp]";
	mysql_query($update_empleado,$conexion);
	$queryVacante="update empleado_plaza set idnominaemp=0, estado='VACANTE' where idnominaemp=$_POST[idnominaemp]";
	$_SESSION['BAJA']=$queryVacante;
	echo "<script>";
	echo "parent.document.afectacion.submit();";
	echo "</script>";
}

?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/estilos.css">

<link rel="stylesheet" type="text/css" href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<script src="controles_jquery/js/jquery-1.9.1.js"></script>
<script src="controles_jquery/js/jquery-ui-1.10.3.custom.js"></script>

<script>

var a = jQuery.noConflict();
a(document).ready(function(){
	
	a(function() {
		a("#fecha").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		a("#fecha").datepicker( "option", "showAnim", "show");
		a("#fecha").datepicker( "option", "dateFormat", "yy-mm-dd" );
	});
	
});
</script>

<script>

function valida(form)
{
	if(form.fecha.value == "")
	{
		alert("Indique la fecha de baja.");
		form.fecha.focus();
		return false;
	}
	
	return true;
}

</script>

</head>
<body>
<form name="fechabaja" id="fechabaja" method="post" action="fechabaja.php">
<table width="350" border="0">
  <tr>
    <td>
    <label class="label">Fecha de baja:</label><br>
    <input class="campo" type="text" name="fecha" id="fecha" value=""></td>
    <td valign="bottom"><input class="boton2" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();"></td>
  </tr>
</table>
<input type="hidden" name="idnominaemp" id="idnominaemp" value="<? echo $_GET[idnominaemp] ?>">
<input type="hidden" name="movimiento" id="movimiento" value="<? echo $_GET[movimiento] ?>">
</form>
</body>
</html>
