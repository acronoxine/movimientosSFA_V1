<?php
session_start();
if($_SESSION["m_sesion"] != 1)
{
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit();
}
?>
<?php require_once('Connections/conexion.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_conexion, $conexion);
$colname_empleado = "-1";
	$nombreCompleto=explode(":",$_POST['nombreUS']);
	$rfc_TOTAL=$nombreCompleto[1];
	$rfc_PARTIDO=explode("-",$rfc_TOTAL);
	$rfc_iniciales=$rfc_PARTIDO[0];
	$rfc_fechanac=$rfc_PARTIDO[1];
	$rfc_homoclave=$rfc_PARTIDO[2];
	$sinLetra=strcmp($rfc_TOTAL[12],')');

	$sql = "Select idnominaemp from nominaemp where ifnull(rfc_iniciales, '') = '$rfc_iniciales' and ifnull(rfc_fechanac, '') = '$rfc_fechanac' and ifnull(rfc_homoclave, '') like '%$rfc_homoclave%'";

	$res = mysql_query($sql, $conexion);
	$ren = mysql_fetch_array($res);

if(mysql_num_rows($res) > 0)
{

$colname_empleado = $ren["idnominaemp"];

$query_empleado = "SELECT n.idnominaemp, case when n.activo = '1' then 'SI' else 'NO' end as activo, concat(ifnull(rfc_iniciales, ''), ifnull(rfc_fechanac, ''), ifnull(rfc_homoclave, '')) as rfc, curp, folio, paterno, materno, nombres, calle, numint, numext, colonia, cp, mun.municipio as ciudad, est.estado";
$query_empleado .= " , curp, a.descripcion as area, c.descripcion as categoria, n.sueldobase, concat(lpad(day(fechanacimiento), 2, '0'), '/', lpad(month(fechanacimiento), 2, '0'), '/', year(fechanacimiento)) as fechanacimiento, case when sexo = 'M' then 'MASCULINO' when sexo = 'F' then 'FEMENINO' else '' end as sexo,";
$query_empleado .= " case when ecivil = '1' then 'SOLTERO' when ecivil = '2' then 'CASADO' when ecivil = '3' then 'DIVORCIADO' when ecivil = '4' then 'VIUDO' when ecivil = '5' then 'UNION LIBRE' else '' end as ecivil, p.descripcion as programa, s.descripcion as subprograma, pr.descripcion as proyecto,";
$query_empleado .= " concat(lpad(day(fechaingr), 2, '0'), '/', lpad(month(fechaingr), 2, '0'), '/', year(fechaingr)) as fechaingr,";
$query_empleado .= "  case when escolaridad = 'P' then 'PRIMARIA' when escolaridad = 'S' then 'SECUNDARIA' when escolaridad = 'B' then 'BACHILLERATO' when escolaridad = 'L' then 'LICENCIATURA' when escolaridad = 'I' then 'INGENIERÍA' when escolaridad = 'M' then 'MAESTRÍA' when escolaridad = 'D' then 'DOCTORADO' else '' end as escolaridad";
$query_empleado .= " , case when nacionalidad = '1' then 'NACIONAL' when nacionalidad = '2' then 'EXTRANJERA' else '' end as nacionalidad, nafiliacionissste, clabe, oficinadepago, cartillaSMN";
$query_empleado .= " , nafiliacion, ";
$query_empleado .= " case when salariofv = 'F' then 'FIJO' else 'VARIABLE' end as salariofv, ";
$query_empleado .= " case when contrato = 'P' then 'PERMANENTE' when contrato = 'A' then 'ASIMILADO' when contrato = '11' then 'EVENTUAL' end as contrato";
$query_empleado .= " , case when nomina = 'Q' then 'QUINCENAL' else 'SEMANAL' end as nomina, case when jornada = '1' then 'DIURNA' when jornada = '2' then 'NOCTURNA' when jornada = '3' then 'MIXTA' when jornada = '4' then 'ESPECIAL' else '' end as jornada, ";
$query_empleado .= " case when formapago = 'DP' then 'DEPOSITO' else 'CHEQUE' end as formapago , ncuenta, ";
$query_empleado .= " mov.descripcion as estatus";
$query_empleado .= " , concat(lpad(day(fechainicio), 2, '0'), '/', lpad(month(fechainicio), 2, '0'), '/', year(fechainicio)) as fechainicio, fechabaja, clabe";

$query_empleado .= " , b.banco FROM nominaemp n"; 
$query_empleado .= " LEFT JOIN empleado_plaza epz ON n.idnominaemp = epz.idnominaemp ";
$query_empleado .= " LEFT JOIN cat_plazas pz ON epz.plaza_id=pz.plaza_id";
$query_empleado .= " left join cat_programa p ON pz.programa = p.idprograma"; 
$query_empleado .= " left join cat_area a ON p.idarea = a.idarea "; 
$query_empleado .= " LEFT JOIN cat_subprograma s ON pz.subprograma = s.idsubprograma "; 
$query_empleado .= " LEFT JOIN cat_proyecto pr ON pz.proyecto = pr.idproyecto "; 
$query_empleado .= " LEFT JOIN cat_categoria c ON pz.categoria=c.idcategoria";
$query_empleado .= " left join bancos b on n.idbancos = b.idbancos"; 
$query_empleado .= " left join cat_estados est on n.estado = est.idestados"; 
$query_empleado .= " left join cat_municipios mun on n.ciudad = mun.idmunicipios"; 
$query_empleado .= " left join cat_movimientos mov on n.estatus = mov.idmovimiento"; 
$query_empleado .= " where n.idnominaemp='$colname_empleado'";
$empleado = mysql_query($query_empleado, $conexion) or die(mysql_error());
$row_empleado = mysql_fetch_assoc($empleado);
$totalRows_empleado = mysql_num_rows($empleado);

if($totalRows_empleado == 0)
{
	echo "<script>";
	echo "alert('No se econtraron resultados');";
	echo "</script>";
	exit();
}
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">

<link rel="stylesheet" type="text/css" href="css/estilos.css">

</head>

<body leftmargin="0" topmargin="0">
<table align="center" border="0">
<tr>
 <td valign="top">
  <table align="center" border="0">
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">RFC:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['rfc'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">CURP:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['curp'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">UR:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['area'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Programa:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['programa'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
<!--    <tr valign="top">
      <td nowrap align="left"><label class="label">Subprograma:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['subprograma'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>-->
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Proyecto:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['proyecto'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Categor&iacute;a:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['categoria'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Sueldo base:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['sueldobase'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Plaza:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['plaza'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Fecha de inicio:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['fechainicio'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Fecha de ingreso:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['fechaingr'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Fecha de baja:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['fechabaja'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    </table>
 </td>
 <td valign="top">    
    <table align="center" border="0">
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Apellido Paterno:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['paterno'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Apellido Materno:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['materno'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Nombre(s):</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['nombres'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Calle:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['calle'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">N&uacute;m. int:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['numint'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">N&uacute;m. ext:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['numext'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Colonia:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['colonia'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Fecha de nacimiento:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['fechanacimiento'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Sexo:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['sexo'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Estado civil:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['ecivil'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Oficina de pago:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['oficinadepago'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    </table>
 </td>
 <td valign="top">    
    <table align="center" border="0">
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Afiliaci&oacute;n IMSS:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['nafiliacion'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Salario:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['salariofv'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Contrato:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['contrato'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Nomina:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['nomina'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Jornada:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['jornada'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Forma de pago:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['formapago'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">N&uacute;m. cuenta:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['ncuenta'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Tipo de movimiento:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['estatus'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Activo:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['activo'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">CLABE:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['clabe'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Escolaridad:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['escolaridad'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Afiliaci&oacute;n ISSSTE:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['nafiliacionissste'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="left"><label class="label">Tipo de Recurso:</label></td><td>
      <div class="dato"><?php echo htmlentities($row_empleado['trecurso'], ENT_COMPAT, 'iso-8859-1'); ?></div></td>
    </tr>
  </table>
 </td>
</tr>
</table>
</body>
</html>
<?php

echo "<script>";
echo "parent.document.mismovimientos.idnominaemp.value = '$row_empleado[idnominaemp]';";
echo "</script>";

mysql_free_result($empleado);
}else{
	echo "<script>";
	echo "alert('No se econtraron resultados para el empleado.');";
	echo "</script>";
}
mysql_free_result($res);
?>