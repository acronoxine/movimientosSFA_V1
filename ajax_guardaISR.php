<?
session_start();
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);

switch($_POST["campo"])
{
	case "a": $campo = "limiteinferior"; break;
	case "b": $campo = "limitesuperior"; break;
	case "c": $campo = "cuotafija"; break;
	case "d": $campo = "porciento"; break;
}


$sql = "update isr set $campo = '$_POST[valor]' where id = '$_POST[id]'";
$res = mysql_query($sql, $conexion);
?>