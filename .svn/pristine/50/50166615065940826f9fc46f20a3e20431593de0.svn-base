<?
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);


$sql = "SELECT idcategoria, nivel, clave, descripcion, (sueldobase+hom) as sueldobase FROM cat_categoria where idcategoria = '$_POST[idcategoria]'";
$res = mysql_query($sql, $conexion);
$ren = mysql_fetch_array($res);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
</head>
<body>
	<table border="0" cellpadding="1" cellspacing="2">
      <tr valign="baseline">
        <td nowrap align="left"><label class="label">Sueldo base:</label><br>
        <input class="campo" type="text" name="sueldobase" value="<? echo $ren["sueldobase"]; ?>" size="15" maxlength="15" style="text-align:right;" onKeypress="return solonumeros(this.form, event);"></td>
      </tr>
      <?
	
		if($_POST["idnominaemp"] != "")
		{
			$sql_comp = "Select importe from nomina where concepto = '114' and idnominaemp = '$_POST[idnominaemp]'";
			$res_comp = mysql_query($sql_comp, $conexion);
			$ren_comp = mysql_fetch_array($res_comp);
			$importe = $ren_comp["importe"];
			mysql_free_result($res_comp);
		}else{
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
			}
		}
		
	  ?>
      <tr valign="baseline">
        <td nowrap align="left"><label class="label">Compensaci&oacute;n:</label><br>
        <input class="campo" type="text" name="compensacion" value="<? echo $importe; ?>" size="15" maxlength="15" style="text-align:right;" onKeypress="return solonumeros(this.form, event);"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="left"><label class="label">Plaza:</label><br>
        <input class="campo" type="text" name="plaza" value="<? echo $ren["nivel"]; ?>" size="3" maxlength="3" onKeyPress="return solonumeros(this.form, event);"></td>
      </tr>
      </table>
</body>
</html>