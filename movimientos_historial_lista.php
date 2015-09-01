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


mysql_select_db($database_conexion, $conexion);
$query_plazas = " SELECT mh.idmovimiento as idmh, fecha_movimiento, CONCAT(e.paterno,' ',e.materno,' ',e.nombres) AS nombre,cm.tipo,mh.estatus,mh.cvecategoria,mh.categoria FROM movimiento_historial mh 
 INNER JOIN nominaemp e ON mh.idnominaemp=e.idnominaemp
 INNER JOIN cat_movimientos cm ON cm.clave=mh.movimiento ";

if(isset($_GET[consulta])){
	$query_plazas.=" where (concat(mh.paterno,' ',mh.materno,' ',mh.nombres) like '%".$_GET['consulta']."%') or 
					(fecha_movimiento like '%".$_GET['consulta']."%') or (cm.tipo like '%".$_GET['consulta']."%') ";
}
$query_plazas.="ORDER BY mh.idmovimiento DESC LIMIT 0,50";
//echo $query_empleados;
$plazas = mysql_query($query_plazas, $conexion) or die(mysql_error());
$row_plazas = mysql_fetch_assoc($plazas);
$totalRows_plazas = mysql_num_rows($plazas);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/estilos.css">

<style>
	#encabezado{
		position: fixed;
		top: 0px;
	}
</style>

<script src="encabezadofijo/mootools-yui-compressed.js"></script>
<script src="encabezadofijo/funciones.js" type="text/javascript"></script>

<script>
	var numero=window.parent.document.getElementById('numeroEntradas'); 
	numero.value='<? echo $totalRows_plazas?>';

	function ver(datos){
		parent.formato(datos);
	}
</script>

</head>

<body topmargin="0" leftmargin="0">
<form>
<div id="encabezado">
<table class="tablagrid" border="0" cellpadding="0" cellspacing="0" width="950">
  <tr class="tablahead">
    <td width="50" align="center">&nbsp;</td>
    <td width="120" align="center">FECHA</td>
    <td width="200" align="center">EMPLEADO</td>
    <td width="90" align="center">TIPO</td>
    <td width="150" align="center">ESTATUS</td>
	<td width="80" align="center">CATEGORIA</td>
    <td width="120" align="center">DESCRIPCIÓN</td>
  </tr>
</table>
</div>
<table class="tablagrid" border="0" cellpadding="0" cellspacing="0" width="950" style="padding-top: 32px;">
  <?php do { ?>
    	<tr id="<? echo $row_plazas["idnominaemp"]; ?>" class="message_box tablaregistros" onClick="miseleccion('<?php echo $row_plazas['idnominaemp']; ?>', 'id_<?php echo $row_plazas['idnominaemp']; ?>')">

      		<td width="50" height="60" align="center"><a onClick="ver(<?php echo $row_plazas['idmh']; ?>)" href="#"><img src="imagenes/pdf-h.png" width="34" height="34"></a></td>
             <td width="120" align="center">
			 <?php 
			 	$fecha_formato=explode(' ',$row_plazas['fecha_movimiento']);
				echo $fecha_formato[0]?><span style="vertical-align:bottom"> <img src="imagenes/reloj2.png" width="12" height="12"></span>
                <?php echo $fecha_formato[1]?>
             
             </td>
      		<td width="200" align="center"><?php echo $row_plazas['nombre']; ?></td>
            <td width="90" align="center"><?php echo $row_plazas['tipo']; ?></td>
            <td width="150" align="center"><?php echo $row_plazas['estatus']; ?></td>
            <td width="80" align="center"><?php echo $row_plazas['cvecategoria']; ?></td>
            <td width="120" align="center"><?php echo $row_plazas['categoria']; ?></td>
    	</tr>
    <?php } while ($row_plazas = mysql_fetch_assoc($plazas)); ?>
</table>
<div id="enespera"></div>
<input type="hidden" name="consulta" id="consulta" value="<? if(isset($_GET["consulta"])) echo $_GET["consulta"]; ?>">
</form>
</body>
</html>
<?php
mysql_free_result($plazas);
?>