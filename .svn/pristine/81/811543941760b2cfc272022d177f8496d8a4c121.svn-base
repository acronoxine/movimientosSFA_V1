<?
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);
?>
<select name="empleados" class="lista" onChange="seleccionaemp(this.value, this.options[selectedIndex].text);" size="5" style="width:300px;">
<?
	echo $sql = "Select idnominaemp, concat(paterno, ' ', materno, ' ', nombres) as nombre from nominaemp where concat(paterno, ' ', materno, ' ', nombres) like '%$_POST[consulta]%'";
	$res = mysql_query($sql, $conexion);
	
	while($ren = mysql_fetch_array($res))
	{
		echo "<option value='$ren[idnominaemp]'>$ren[nombre]";
	}
?>
</select>