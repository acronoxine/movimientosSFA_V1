<?php
require_once('Connections/conexion.php'); 
//mysql_select_db($database_conexion, $conexion);
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  //$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
  if(function_exist){
  	$obj = new database();
  	$link = $obj->connect();
  	$theValue = mysqli_real_escape_string($link,$theValue);
  }
  switch ($theType) {
    case "text":
      $theValue = ( $theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ( $theValue != "") ? intval( $theValue) : "NULL";
      break;
    case "double":
      $theValue = ( $theValue != "") ? doubleval( $theValue) : "NULL";
      break;
    case "date":
      $theValue = ( $theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ( $theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  	}
  return $theValue;
}
}
/* creaExcel
 * Crea un archivo con extension xlsx
 * Recibe un arreglo que almacena los dtos y un numero de datos como indice del arreglo
 *  */
function crearExcel($arrayError, $numeroErroresE) {
    $tbHtml='<table class="tablagrid" border="0" cellpadding="1" cellspacing="0" width="1535">

                    <tr class="tablahead">
						<td width="80" align="center">idnominaemp</td>
                        <td width="80" align="center">cve</td>
                        <td width="80" align="center">importe</td>
                        <td width="80" align="center">tipo</td>
                        <td width="80" align="center">quincena</td>
                        <td width="80" align="center">anio</td>
                        <td width="80" align="center">fechaem</td>
                        <td width="80" align="center">fechaasis</td>
                        <td width="80" align="center">usuario</td>
                        <td width="80" align="center">cve_sat</td>
                        <td width="80" align="center">idarea</td>
                     </tr>
                </table>
                <table >';
    for ($i = 1; $i <= $numeroErroresE; $i++) {
        $tbHtml.= '<tr class="tablaregistros">
								<td width="80" align="center"> '.$arrayError[$i][1].' </td>
                                <td width="80" align="center"> '.$arrayError[$i][2].' </td>
                                <td width="80" align="center"> '.$arrayError[$i][3].' </td>
                                <td width="80" align="center"> '.$arrayError[$i][4].' </td>
                                <td width="80" align="center"> '.$arrayError[$i][5].' </td>
                                <td width="80" align="center"> '.$arrayError[$i][6].' </td>
                                <td width="80" align="center"> '.$arrayError[$i][7].' </td>
                                <td width="80" align="center"> '.$arrayError[$i][8].' </td>
                                <td width="80" align="center"> '.$arrayError[$i][9].' </td>
                                <td width="80" align="center"> '.$arrayError[$i][10].' </td>
                                <td width="80" align="center"> '.$arrayError[$i][11].' </td>
                            </tr>   
</table>';
    }
    $tbHtml .= "</html>";
	echo $tbHtml;
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=erroresNomina.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}

/*
	FUNCION PARA INSERTAR LAS FALTAS EN LA TABLA NOMINA
	se insertan de forma individual

*/
function modificaRegistro($tabla,$opcion,$fecha,$hora,$empleado,$conexion){
		if($opcion==1){// insertar en nominapago
			$importe = 0;
			$sql = "Select sueldobase as importe from nominaemp where idnominaemp = '$empleado'";
			$res_sb = mysqli_query( $conexion, $sql);
			$ren_sb = mysqli_fetch_array($res_sb);
			mysqli_free_result($res_sb);
			$sueldodiario = ($ren_sb["importe"] * 2) / 30;
			$importe = number_format($sueldodiario, 2, ".", "");
			$sql="INSERT INTO nomina (idnominaemp,concepto,importe,tipo,idbeneficiarios) VALUES('$empleado','258','$importe','D','0')";
			$insert=mysqli_query( $conexion, $sql );
			recalculoISR($empleado);
			$sql="UPDATE asistencias SET registrado=1 WHERE idnominaemp='$empleado' AND fecha='$fecha' AND hora='$hora' and tipo='$tabla'";
			mysqli_query($conexion, $sql );
		}
		elseif($opcion==2){
			$sql="UPDATE asistencias SET registrado=0 WHERE idnominaemp='$empleado' AND fecha='$fecha' AND hora='$hora' and tipo='$tabla'";
			mysqli_query( $conexion,$sql );
		}
		elseif($opcion==3){
			$sql="DELETE FROM nomina WHERE concepto='258' and idnominaemp='$empleado'";
			//echo $sql;
			mysql_query( $conexion, $sql );
			recalculoISR($empleado);
		}
}
function recalculoISR($idnominaemp){
	$hostname_conexion = "localhost";
	$database_conexion = "nominacetic";
	$username_conexion = "adminnomina";
	$password_conexion = "GNgOLWSQR780";
	//$conexion = mysql_pconnect($hostname_conexion, $username_conexion, $password_conexion) or trigger_error(mysql_error(),E_USER_ERROR); 

		$sql_isr="SELECT SUM(importe)-
			(SELECT CASE WHEN SUM(importe)>0 THEN SUM(importe) ELSE 0 END FROM nomina WHERE idnominaemp='$idnominaemp' AND concepto='258') AS sueldobase 
			FROM nomina 
			WHERE (idnominaemp='$idnominaemp' AND concepto='101') 
			OR (idnominaemp='$idnominaemp' AND concepto='114')";
		$resisr=mysqli_query( $conexion,$sql_isr );
		$renisr=mysqli_fetch_array( $resisr );
		$sueldoB=$renisr['sueldobase'];

		mysqli_free_result($res);
		$sql = "select limiteinferior, cuotafija, porciento";
		$sql .= " from isr";
		$sql .= " where ($sueldoB between limiteinferior and limitesuperior) or ($sueldoB >= limiteinferior and limitesuperior=0)";
		$res = mysqli_query( $conexion, $sql );
		$ren = mysqli_fetch_array($res);
		mysqli_free_result($res);
		$resultado = (($sueldoB - $ren["limiteinferior"]) * ($ren["porciento"]/100)) + $ren["cuotafija"];//NUEVO ISR
		$sql="UPDATE nomina SET importe='$resultado' WHERE idnominaemp='$idnominaemp' and concepto='251'";
		mysql_query( $conexion, $sql);

}
?>