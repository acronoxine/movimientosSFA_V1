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

  $theValue = function_exists("mysql_real_escape_string") ? mysqli_real_escape_string($theValue) : mysql_escape_string($theValue);

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

if ((isset($_GET['idconceptos'])) && ($_GET['idconceptos'] != "")) {
  $deleteSQL = sprintf("DELETE FROM cat_conceptos WHERE idconceptos=%s",
                       GetSQLValueString($_GET['idconceptos'], "int"));

  //mysql_select_db($database_conexion, $conexion);
  $Result1 = mysqli_query($deleteSQL, $conexion) or die(mysqli_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
if(!isset($_POST["importe"]))
{
	$_POST["importe"] = 0;
}

if(!isset($_POST["dias"]))
{
	$_POST["dias"] = 0;
}

if(!isset($_POST["porcentaje"]))
{
	$_POST["porcentaje"] = 0;
}
	
	
  $insertSQL = sprintf("INSERT INTO cat_conceptos (clave, descripcion, ver, afectacion, importe, dias, porcentaje, uso, tipo) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['clave'], "text"),
                       GetSQLValueString(strtoupper($_POST['descripcion']), "text"),
                       GetSQLValueString('SI', "text"),
                       GetSQLValueString($_POST['afectacion'], "text"),
                       GetSQLValueString($_POST['importe'], "double"),
                       GetSQLValueString($_POST['dias'], "text"),
                       GetSQLValueString($_POST['porcentaje'], "text"),
					   GetSQLValueString($_POST['_activa'], "text"),
                       GetSQLValueString($_POST['tipo'], "text"));

  //mysql_select_db($database_conexion, $conexion);
  $Result1 = mysqli_query($conexion,$insertSQL) or die(mysqli_error());
  
  echo "<script>";
  echo "parent.document.form1.reset();";
  echo "</script>";
}

//mysql_select_db($database_conexion, $conexion);
$query_conceptos = "SELECT idconceptos, clave, descripcion, case when afectacion = 'G' then 'GENERAL' else 'INDIVIDUAL' end as afectacion, importe, dias, porcentaje, case when uso = 'IMP' then 'IMPORTE' when uso = 'DIA' then 'DÍAS' when uso = 'POR' then 'PORCENTAJE' else 'NINGUNO' end as uso, case when tipo = 'P' then 'PERCEPCIÓN' else 'DEDUCCIÓN' end as tipo FROM cat_conceptos where ver = 'SI'";

if(isset($_GET["consulta"]))
{
	$query_conceptos .= " and descripcion like '%$_GET[consulta]%'";
}

$query_conceptos .= " order by descripcion, tipo";

$conceptos = mysqli_query($conexion,$query_conceptos) or die(mysqli_error());
$row_conceptos = mysqli_fetch_assoc($conceptos);
$totalRows_conceptos = mysqli_num_rows($conceptos);
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

<script text="text/javascript">
	function loadDemo(){
			
		  // nice one David Walsh	
		  var demoTwo = new ScrollSpy({
			 min: 0, // acts as position-x: absolute; left: 50px;
			 mode: 'horizontal',
			 onEnter: function(position,enters) {
			
			 },
			 onLeave: function(position,leaves) {
			
			 },
			 onTick: function(position,state,enters,leaves) {
				$("encabezado").style.left = -position.x+"px";
			 },
			 container: window
			}); 

	}
</script>

</head>

<body topmargin="0" leftmargin="0">

<div id="encabezado">
<table class="tablagrid" border="0" cellpadding="0" cellspacing="0" width="958">
  <tr class="tablahead">
    <td width="37" align="center">&nbsp;</td>
    <td width="71" align="center">CLAVE</td>
    <td width="239" align="center">DESCRIPCIÓN</td>
    <td width="83" align="center">AFECTACIÓN</td>
    <td width="125" align="center">IMPORTE</td>
    <td width="47" align="center">DÍAS</td>
    <td width="82" align="center">PORCENTAJE</td>
    <td width="87" align="center">USO</td>
    <td align="center">TIPO</td>
  </tr>
</table>
</div>
<table class="tablagrid" border="0" cellpadding="0" cellspacing="0" width="958" style="padding-top: 32px;">
  <?php do { ?>
    <tr class="tablaregistros">
      <td width="37" align="center"><a target="_top" href="cat_conceptos_md.php?idconceptos=<?php echo $row_conceptos['idconceptos']; ?>"><img src="imagenes/editar.png" width="32" height="32"></a></td>
      <td width="71" align="center"><?php echo $row_conceptos['clave']; ?></td>
      <td width="239"><?php echo $row_conceptos['descripcion']; ?></td>
      <td width="83" align="center"><?php echo $row_conceptos['afectacion']; ?></td>
      <td width="125" align="right"><?php echo number_format($row_conceptos['importe'], 2, ".", ","); ?></td>
      <td width="47" align="center"><?php echo $row_conceptos['dias']; ?></td>
      <td width="82" align="center"><?php echo $row_conceptos['porcentaje']; ?></td>
      <td width="87" align="center"><?php echo $row_conceptos['uso']; ?></td>
      <td align="center"><?php echo $row_conceptos['tipo']; ?></td>
    </tr>
    <?php } while ($row_conceptos = mysqli_fetch_assoc($conceptos)); ?>
</table>
</body>
</html>
<?php
mysqli_free_result($conceptos);
?>
