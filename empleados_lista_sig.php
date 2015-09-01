<?
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

$idnominaemp = $_GET["idnominaemp"];

mysql_select_db($database_conexion, $conexion);
$query_empleados = "SELECT idnominaemp, case when n.activo = '1' then 'SI' else 'NO' end as activo, concat(ifnull(rfc_iniciales, ''), ifnull(rfc_fechanac, ''), ifnull(rfc_homoclave, '')) as rfc, curp, folio, paterno, materno, nombres, calle, numint, numext, colonia, cp, mun.municipio as ciudad, est.estado";
$query_empleados .= " , curp, a.descripcion as area, c.descripcion as categoria, n.sueldobase, plaza, concat(lpad(day(fechanacimiento), 2, '0'), '/', lpad(month(fechanacimiento), 2, '0'), '/', year(fechanacimiento)) as fechanacimiento, case when sexo = 'M' then 'MASCULINO' when sexo = 'F' then 'FEMENINO' else '' end as sexo,";
$query_empleados .= " case when ecivil = '1' then 'SOLTERO' when ecivil = '2' then 'CASADO' when ecivil = '3' then 'DIVORCIADO' when ecivil = '4' then 'VIUDO' when ecivil = '5' then 'UNION LIBRE' else '' end as ecivil, p.descripcion as programa, s.descripcion as subprograma, pr.descripcion as proyecto,";
$query_empleados .= " concat(lpad(day(fechaingr), 2, '0'), '/', lpad(month(fechaingr), 2, '0'), '/', year(fechaingr)) as fechaingr,";
$query_empleados .= "  case when escolaridad = 'P' then 'PRIMARIA' when escolaridad = 'S' then 'SECUNDARIA' when escolaridad = 'B' then 'BACHILLERATO' when escolaridad = 'L' then 'LICENCIATURA' when escolaridad = 'O' then 'POSGRADO' else '' end as escolaridad";
$query_empleados .= " , case when nacionalidad = '1' then 'NACIONAL' when nacionalidad = '2' then 'EXTRANJERA' else '' end as nacionalidad, nafiliacionissste, clabe, oficinadepago, cartillaSMN";
$query_empleados .= " , nafiliacion, ";
$query_empleados .= " case when salariofv = 'F' then 'FIJO' else 'VARIABLE' end as salariofv, ";
$query_empleados .= " case when contrato = 'P' then 'PERMANENTE' when contrato = 'A' then 'ASIMILADO' when contrato = '11' then 'EVENTUAL' end as contrato";
$query_empleados .= " , case when nomina = 'Q' then 'QUINCENAL' else 'SEMANAL' end as nomina, case when jornada = '1' then 'DIURNA' when jornada = '2' then 'NOCTURNA' when jornada = '3' then 'MIXTA' when jornada = '4' then 'ESPECIAL' else '' end as jornada, de_hrs, a_hrs, ";
$query_empleados .= " case when formapago = 'DP' then 'DEPOSITO' else 'CHEQUE' end as formapago , ncuenta, ";
$query_empleados .= " mov.descripcion as estatus";
$query_empleados .= " , concat(lpad(day(fechainicio), 2, '0'), '/', lpad(month(fechainicio), 2, '0'), '/', year(fechainicio)) as fechainicio, fechabaja, clabe";
$query_empleados .= " , b.banco FROM nominaemp n"; 
$query_empleados .= " left join cat_categoria c on n.categoria = c.idcategoria"; 
$query_empleados .= " left join cat_programa p on n.programa = p.idprograma"; 
$query_empleados .= " left join cat_area a on n.area = a.idarea"; 
$query_empleados .= " left join cat_subprograma s on n.subprograma = s.idsubprograma"; 
$query_empleados .= " left join cat_proyecto pr on n.proyecto = pr.idproyecto"; 
$query_empleados .= " left join bancos b on n.idbancos = b.idbancos"; 
$query_empleados .= " left join cat_estados est on n.estado = est.idestados"; 
$query_empleados .= " left join cat_municipios mun on n.ciudad = mun.idmunicipios"; 
$query_empleados .= " left join cat_movimientos mov on n.estatus = mov.idmovimiento"; 
$query_empleados .= " where idnominaemp < '$idnominaemp'";

if(isset($_GET["consulta"]))
{
	$query_empleados .= " and concat(paterno, ' ', materno, ' ', nombres) like '%$_GET[consulta]%'";
}

$query_empleados .= " order by idnominaemp desc limit 50";

$idnominaemp = "";

$empleados = mysql_query($query_empleados, $conexion) or die(mysql_error());
$totalRows_empleados = mysql_num_rows($empleados);
?>

  <?php while($row_empleados = mysql_fetch_array($empleados)) { ?>
    <tr id="<? echo $row_empleados["idnominaemp"]; ?>" class="message_box tablaregistros" onClick="miseleccion('<?php echo $row_empleados['idnominaemp']; ?>', 'id_<?php echo $row_empleados['idnominaemp']; ?>')">
      <td width="54" height="64" align="center"><a onClick="if(confirm('¿Confirma que desea eliminar el registro?')) location.href='empleados_lista.php?idnominaemp=<?php echo $row_empleados['idnominaemp']; ?>';" href="#"><img src="imagenes/borrar.png" width="34" height="34"></a></td>
      <td align="center"><input type="radio" name="seleccion" id="id_<?php echo $row_empleados['idnominaemp']; ?>" value="<?php echo $row_empleados['idnominaemp']; ?>"></td>
      <td width="119" align="center"><?php echo $row_empleados['activo']; ?></td>
      <td width="119" align="center"><?php echo $row_empleados['rfc']; ?></td>
      <td width="157" align="center"><?php echo $row_empleados['paterno']; ?></td>
      <td width="158" align="center"><?php echo $row_empleados['materno']; ?></td>
      <td width="164" align="center"><?php echo $row_empleados['nombres']; ?></td>
      <td width="135" align="center"><?php echo $row_empleados['curp']; ?></td>
      <td width="129" align="center"><?php echo $row_empleados['folio']; ?></td>      
      <td width="133" align="center"><?php echo $row_empleados['area']; ?></td>
      <td width="170" align="center"><?php echo $row_empleados['programa']; ?></td>
      <td width="165" align="center"><?php echo $row_empleados['proyecto']; ?></td>
      <td width="169" align="center"><?php echo $row_empleados['categoria']; ?></td>
      <td width="184" align="right"><?php echo number_format($row_empleados['sueldobase'], 2, ".", ","); ?></td>
      <td width="90" align="center"><?php echo $row_empleados['plaza']; ?></td>
      <td width="116" align="center"><?php echo $row_empleados['fechainicio']; ?></td>
      <td width="112" align="center"><?php echo $row_empleados['fechaingr']; ?></td>
      <td width="284"><?php echo $row_empleados['calle']; ?></td>
      <td width="97" align="center"><?php echo $row_empleados['numint']; ?></td>
      <td width="101" align="center"><?php echo $row_empleados['numext']; ?></td>
      <td width="297"><?php echo $row_empleados['colonia']; ?></td>
      <td width="78" align="center"><?php echo $row_empleados['cp']; ?></td>
      <td width="290" align="center"><?php echo $row_empleados['ciudad']; ?></td>
      <td width="305" align="center"><?php echo $row_empleados['estado']; ?></td>
      <td width="145" align="center"><?php echo $row_empleados['fechanacimiento']; ?></td>
      <td width="90" align="center"><?php echo $row_empleados['sexo']; ?></td>
      <td width="90" align="center"><?php echo $row_empleados['ecivil']; ?></td>
      <td width="126" align="center"><?php echo $row_empleados['nacionalidad']; ?></td>
      <td width="114" align="center"><?php echo $row_empleados['nafiliacion']; ?></td>
      <td width="108" align="center"><?php echo $row_empleados['salariofv']; ?></td>
      <td width="106" align="center"><?php echo $row_empleados['contrato']; ?></td>
      <td width="100" align="center"><?php echo $row_empleados['nomina']; ?></td>
      <td width="102" align="center"><?php echo $row_empleados['jornada']; ?></td>
      <td width="102" align="center"><?php echo $row_empleados['de_hrs']; ?></td>
      <td width="102" align="center"><?php echo $row_empleados['a_hrs']; ?></td>
      <td width="118" align="center"><?php echo $row_empleados['formapago']; ?></td>
      <td width="105" align="center"><?php echo $row_empleados['ncuenta']; ?></td>
      <td width="102" align="center"><?php echo $row_empleados['estatus']; ?></td>
      <td width="211" align="center"><?php echo $row_empleados['clabe']; ?></td>
      <td width="213" align="center"><?php echo $row_empleados['banco']; ?></td>
      <td width="123" align="center"><?php echo $row_empleados['escolaridad']; ?></td>
      <td width="142" align="center"><?php echo $row_empleados['nafiliacionissste']; ?></td>
      <td width="135" align="center"><?php echo $row_empleados['oficinadepago']; ?></td>
      <td width="119" align="center"><?php echo $row_empleados['cartillaSMN']; ?></td>
      <td align="center"><?php echo $row_empleados['fechabaja']; ?></td>

    </tr>
    <?php }  ?>
