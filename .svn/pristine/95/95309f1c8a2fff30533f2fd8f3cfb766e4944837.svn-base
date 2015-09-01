<?
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);

switch($_POST["concepto"])
{
	case '251':

		$baseGr=0;
		//$sql = "Select sueldobase from nominaemp where idnominaemp = '$_POST[idnominaemp]'";
		$sql="
			SELECT SUM(importe)-
			(SELECT CASE WHEN SUM(importe)>0 THEN SUM(importe) ELSE 0 END FROM nomina WHERE idnominaemp='$_POST[idnominaemp]' AND concepto='258') AS sueldobase 
			FROM nomina 
			WHERE (idnominaemp='$_POST[idnominaemp]' AND concepto='101') 
			OR (idnominaemp='$_POST[idnominaemp]' AND concepto='114')";
		$res = mysql_query($sql, $conexion);
		$ren = mysql_fetch_array($res);
		mysql_free_result($res);
		
		$sueldobase = $ren["sueldobase"];
/*		
		$sql = "Select importe from nomina where idnominaemp = '$_POST[idnominaemp]' and concepto = '251'";
		$res_isr = mysql_query($sql, $conexion);
		
		if(mysql_num_rows($res_isr) > 0)
		{
			$sql = "Select importe from nomina where idnominaemp = '$_POST[idnominaemp]' and concepto = '114'";
			$res = mysql_query($sql, $conexion);
			$ren = mysql_fetch_array($res);
			
			if(mysql_num_rows($res) > 0){
				$sueldobase = $ren["importe"] * 2;
//--------------------------------- isr para aguinaldo ------------------------------
				$sql = "Select importe from nomina where idnominaemp = '$_POST[idnominaemp]' and concepto = '115'";
				$res = mysql_query($sql, $conexion);
				if(mysql_num_rows($res) > 0){
					$ren = mysql_fetch_array($res);
					$importeC=$ren['importe'];
					$sqls="SELECT importe from salmin";
					$res=mysql_query($sqls);
					$ren=mysql_fetch_array($res);
					$salarioMinimo=$ren['importe'];//salario minimo
					$parteExenta=30*$salarioMinimo;//parte exenta 
					$baseGr=$importeC-$parteExenta;//base gravable
				
					//-- calculo de isr sobre la base gravable
					$sql = "select limiteinferior, cuotafija, porciento";
					$sql .= " from isr_mensual";
					$sql .= " where ($baseGr between limiteinferior and limitesuperior) or ($baseGr >= limiteinferior and limitesuperior=0)";
					$res = mysql_query($sql, $conexion);
					$ren = mysql_fetch_array($res);
					mysql_free_result($res);
					$resultado = (($baseGr - $ren["limiteinferior"]) * ($ren["porciento"]/100)) + $ren["cuotafija"];
					$importe = number_format($resultado, 2, ".", "");
				}
				//echo "baseGra:".$baseGr." importe:".$importe;
//-----------------------------------------------------------------------------------
			}
			else{
				$sueldobase = 0;
			}
			mysql_free_result($res);
		}
	*/	
		mysql_free_result($res_isr);
		if($baseGr==0){
			$sql = "select limiteinferior, cuotafija, porciento";
			$sql .= " from isr";
			$sql .= " where ($sueldobase between limiteinferior and limitesuperior) or ($sueldobase >= limiteinferior and limitesuperior=0)";
			$res = mysql_query($sql, $conexion);
			$ren = mysql_fetch_array($res);
			mysql_free_result($res);
		
			$resultado = (($sueldobase - $ren["limiteinferior"]) * ($ren["porciento"]/100)) + $ren["cuotafija"];
		
			//$resultado /= 2;
		
			$importe = number_format($resultado, 2, ".", "");
		}
	break;
	case '274':
	
		$conexion_extra= mysqli_connect("localhost", "adminnomina", "GNgOLWSQR780", "nominacetic", "3606");
		$PA=mysqli_query($conexion_extra,"CALL calcula_imss('$_POST[idnominaemp]',@sec)");
		$row=mysqli_fetch_array($PA);
		$importe=$row['total'];
		mysqli_close($conexion_extra);
	
	break;
	case '258':
			$importe = 0;
			if(isset($_POST["dias"]))
			{
				$sql = "Select sueldobase as importe from nominaemp where idnominaemp = '$_POST[idnominaemp]'";
				$res_sb = mysql_query($sql, $conexion);
				$ren_sb = mysql_fetch_array($res_sb);
				mysql_free_result($res_sb);
		
				$sueldodiario = ($ren_sb["importe"] * 2) / 30;
				$importe = $sueldodiario * $_POST["dias"];
				$importe = number_format($importe, 2, ".", "");
			}
			
			
	break;
	case '252':
			$importe = 0;
			if(isset($_POST["importepension"]))
			{
				$importe = number_format($_POST["importepension"], 2, ".", "");
			}
			
	break;
	
	default:
		$sql = "Select sueldobase as importe from nominaemp where idnominaemp = '$_POST[idnominaemp]'";
		$res_sb = mysql_query($sql, $conexion);
		$ren_sb = mysql_fetch_array($res_sb);
		mysql_free_result($res_sb);
	
	
		$sql = "Select importe, porcentaje, dias, uso from cat_conceptos where clave = '$_POST[concepto]'";
		$res = mysql_query($sql, $conexion);
		$ren = mysql_fetch_array($res);
		mysql_free_result($res);
		
		switch($ren["uso"])
		{
			case 'IMP':
				$importe = $ren["importe"];
			break;
			case 'POR':
				$importe = $ren_sb["importe"] * ($ren["porcentaje"]/100);
				$importe = number_format($importe, 2, ".", "");
			break;
			case 'DIA':
			
			break;
			default:
				$importe = 0;
			break;
		}
	break;
}

echo "<input class='campo' type='text' name='imported' id='imported' value='$importe' style=\"width:100px; text-align:right; font-weight:bold;\" onKeyPress=\"return solonumeros(this.form, event);\">";


?>