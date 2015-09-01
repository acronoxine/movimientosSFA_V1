<?
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);

$sql = "select ($_POST[importe] / sum(case when tipo = 'D' then importe * -1 else importe end)) * 100 as porcentaje from nomina where idnominaemp = '$_POST[idnominaemp]'";
$res_sn = mysql_query($sql, $conexion);
$ren_sn = mysql_fetch_array($res_sn);
mysql_free_result($res_sn);
$importe = number_format($ren_sn["porcentaje"], 2, ".", "");

?>

<input class="campo" type="text" name="porcentaje" value="<? echo $importe; ?>" size="3" maxlength="10" onKeyup="por2imp(this.value);" onClick = "select();" style="text-align:center;"><label class="label">%</label>