<?
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);

if($_POST["porcentaje"] == "")
	$_POST["porcentaje"] = 0;

$sql = "select sum(case when tipo = 'D' then importe * -1 else importe end) * ($_POST[porcentaje]/100) as pension from nomina where idnominaemp = '$_POST[idnominaemp]'";
$res_sn = mysql_query($sql, $conexion);
$ren_sn = mysql_fetch_array($res_sn);
mysql_free_result($res_sn);
$importe = number_format($ren_sn["pension"], 2, ".", "");

?>

<input class="campo" type="text" name="importe" value="<? echo $importe; ?>" size="15" maxlength="10" onKeyup="imp2por(this.value);" onClick = "select();" style="text-align:right;">