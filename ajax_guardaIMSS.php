<?
session_start();
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);

switch($_POST["campo"])
{
	case "a": $campo = "cuotaespecie"; break;
	case "b": $campo = "excedente"; break;
	case "c": $campo = "cuotadinero"; break;
	case "d": $campo = "invalidezyvida"; break;
	case "e": $campo = "cesantiayvejez"; break;
	case "f": $campo = "3smgdf"; break;
}


$sql = "update imss set $campo = '$_POST[valor]' where id = '$_POST[id]'";
$res = mysql_query($sql, $conexion);
?>