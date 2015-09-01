<?
session_start();
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);

switch($_POST["campo"])
{
	case "a": $campo = "ingresos_de"; break;
	case "b": $campo = "ingresos_a"; break;
	case "c": $campo = "subsidio"; break;
}


$sql = "update isrsubsidio set $campo = '$_POST[valor]' where idisrsubsidio = '$_POST[id]'";
$res = mysql_query($sql, $conexion);
?>