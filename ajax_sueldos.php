<?
include ("Connections/conexion.php");
//mysql_select_db($database_conexion, $conexion);

if (isset($_POST[idcategoria])) {
	$sql = "SELECT idcategoria, nivel, clave, descripcion, (sueldobase+hom) as sueldobase FROM cat_categoria where idcategoria = '$_POST[idcategoria]'";
}
?>
<script>
/*alert(<//?php echo $sql?>)*/</script>
<?
$res = mysqli_query($conexion,$sql);
$ren = mysqli_fetch_array($res);
?>
<!doctype html>
<html>
	<head>
		<meta charset="iso-8859-1">
	</head>
	<body>
		<table border="0" >
			<tr valign="baseline">
				<td nowrap align="left"><label class="label">Sueldo base:</label></td>
				<td>
				<input type="text" name="sueldobase" readonly  value="<? echo $ren["sueldobase"]; ?>" size="15" maxlength="15" style="text-align:right;" onKeypress="return solonumeros(this.form, event);">
				</td>
			</tr>
			<?
			/*
			 $sql_comp = "Select importe, porcentaje, dias, uso from cat_conceptos where clave = '114'";
			 $res_comp = mysql_query($sql_comp, $conexion);
			 $ren_comp = mysql_fetch_array($res_comp);
			 mysql_free_result($res_comp);

			 switch($ren_comp["uso"])
			 {
			 case 'IMP':
			 $importe = $ren_comp["importe"];
			 break;
			 case 'POR':
			 $importe = $ren["sueldobase"] * ($ren_comp["porcentaje"]/100);
			 $importe = number_format($importe, 2, ".", "");
			 break;
			 case 'DIA':
			 $sueldodiario = ($ren["sueldobase"] * 2) / 30;
			 $importe = $sueldodiario * $ren_comp["dias"];
			 $importe = number_format($importe, 2, ".", "");
			 break;
			 default:
			 $importe = 0;
			 break;
			 }*/
			?>
			<!-- <tr valign="baseline">
			<td nowrap align="left"><label class="label">Compensaci&oacute;n:</label></td>
			<td><input class="campo" type="text" name="compensacion" disabled value="<? echo $importe; ?>" size="15" maxlength="15" style="text-align:right;" onKeypress="return solonumeros(this.form, event);"></td>
			</tr>-->
		</table>
	</body>
</html>