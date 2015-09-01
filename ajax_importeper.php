<?
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);

switch($_POST["concepto"])
{
	case '101':
		$sql = "Select sueldobase as importe from nominaemp where idnominaemp = '$_POST[idnominaemp]'";
		$res = mysql_query($sql, $conexion);
		$ren = mysql_fetch_array($res);
		mysql_free_result($res);
	
		$importe = $ren["importe"];
	break;
	case '114':
		$sql = "Select sueldobase as importe from nominaemp where idnominaemp = '$_POST[idnominaemp]'";
		$res_sb = mysql_query($sql, $conexion);
		$ren_sb = mysql_fetch_array($res_sb);
		mysql_free_result($res_sb);
	
	
		$sql = "Select importe, porcentaje, dias, uso from cat_conceptos where clave = '114'";
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
				$sueldodiario = ($ren_sb["importe"] * 2) / 30;
				$importe = $sueldodiario * $ren["dias"];
				$importe = number_format($importe, 2, ".", "");
			break;
			default:
				$importe = 0;
			break;
		}
		
		$sueldobase = $importe * 2;
		
		$sql = "select limiteinferior, cuotafija, porciento";
		$sql .= " from isr";
		$sql .= " where ($sueldobase between limiteinferior and limitesuperior) or ($sueldobase >= limiteinferior and limitesuperior=0)";
		$res = mysql_query($sql, $conexion);
		$ren = mysql_fetch_array($res);
		mysql_free_result($res);
		
		$resultado = (($sueldobase - $ren["limiteinferior"]) * ($ren["porciento"]/100)) + $ren["cuotafija"];
		
		$resultado /= 2;
		
		$isr = number_format($resultado, 2, ".", "");
		
		echo "<script>";
		echo "form1.imported.value = '$isr';";
		echo "form1.deducciones.value = '251'";
		echo "</script>";
		
	break;
	//--------------------------- Para el aguinaldo en porcentaje -----------------
	case '115':
		$sql="SELECT e.fechaingr,SUM(n.importe) AS importe FROM";
		$sql.=" nomina n INNER JOIN nominaemp e ON n.idnominaemp=e.idnominaemp";
  		$sql.=" WHERE (n.concepto=101 AND n.idnominaemp=$_POST[idnominaemp]) OR
		 			(n.concepto=114 AND n.idnominaemp=$_POST[idnominaemp])";
	     //echo $sql;
		$res_sb = mysql_query($sql, $conexion);
		$ren_sb = mysql_fetch_array($res_sb);
		mysql_free_result($res_sb);
		$sueldoDiario=($ren_sb['importe']*2)/30;
		$ultimoDA=date('Y')."-12-31";
		$diasTranscurridos=dias_transcurridos($ren_sb['fechaingr'],$ultimoDA);
		$importe=(($sueldoDiario*40)/365)*$diasTranscurridos;
		$importe = number_format($importe, 2, ".", "");
	break;
		//--------------------------- Para prima vacacional -----------------
	case '119':
		$diasP=$_POST['dias'];
		$sql="SELECT e.fechaingr,SUM(n.importe) AS importe FROM";
		$sql.=" nomina n INNER JOIN nominaemp e ON n.idnominaemp=e.idnominaemp";
  		$sql.=" WHERE (n.concepto=101 AND n.idnominaemp=$_POST[idnominaemp]) OR
		 			(n.concepto=114 AND n.idnominaemp=$_POST[idnominaemp])";
		$res_sb = mysql_query($sql, $conexion);
		$ren_sb = mysql_fetch_array($res_sb);
		mysql_free_result($res_sb);
		$sueldoDiario=($ren_sb['importe']*2)/30;
		$ultimoDA=date('Y')."-12-31";
		$diasTranscurridos=dias_transcurridos($ren_sb['fechaingr'],$ultimoDA);
		if($diasTranscurridos>184)
			$importe=(($sueldoDiario*0.25)*$diasP);
		else
			$importe=0;
		$importe = number_format($importe, 2, ".", "");
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
				$sueldodiario = ($ren_sb["importe"] * 2) / 30;
				$importe = $sueldodiario * $ren["dias"];
				$importe = number_format($importe, 2, ".", "");
			break;
			default:
				$importe = 0;
			break;
		}
	break;
}

echo "<input class='campo' type='text' name='importep' id='importep' value='$importe' style=\"width:100px; text-align:right; font-weight:bold;\" onKeyPress=\"return solonumeros(this.form, event);\">";
function dias_transcurridos($fecha_i,$fecha_f)
{
	$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
	$dias 	= abs($dias); $dias = floor($dias);		
	return $dias+1;
}

?>