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


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO cat_categoria (clave, descripcion, nivel, sueldobase) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString(strtoupper($_POST['clave']), "text"),
                       GetSQLValueString(strtoupper($_POST['descripcion']), "text"),
					   GetSQLValueString(strtoupper($_POST['nivel']), "text"),
					   GetSQLValueString(strtoupper($_POST['sueldobase']), "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  
  echo "<script>";
  echo "parent.document.form1.reset();";
  echo "</script>";
}


if ((isset($_GET['idcategoria'])) && ($_GET['idcategoria'] != "")) {
  $deleteSQL = sprintf("DELETE FROM cat_categoria WHERE idcategoria=%s",
                       GetSQLValueString($_GET['idcategoria'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
}

mysql_select_db($database_conexion, $conexion);
$query_areas = "SELECT * FROM cat_categoria";

if(isset($_GET["consulta"]))
{
	$query_areas .= " where descripcion like '%$_GET[consulta]%'";
}


$areas = mysql_query($query_areas, $conexion) or die(mysql_error());
$row_areas = mysql_fetch_assoc($areas);
$totalRows_areas = mysql_num_rows($areas);
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
<table class="tablagrid" border="0" cellpadding="1" cellspacing="0" width="643">
  <tr class="tablahead">
    <td width="40">&nbsp;</td>
    <td align="center" width="63">NIVEL</td>
    <td align="center" width="67">CLAVE</td>
    <td width="264">DESCRIPCION</td>
    <td align="center">SUELDO BASE</td>
    <td align="center">HOM.</td>
  </tr>
</table>
</div>
<table class="tablagrid" border="0" cellpadding="1" cellspacing="0" width="643" style="padding-top: 32px;">
  <?php do { ?>
    <tr class="tablaregistros">
      <td width="40"><a target="_top" href="cat_categorias_md.php?idcategoria=<?php echo $row_areas['idcategoria']; ?>"><img src="imagenes/editar.png" width="36" height="36"></a></td>
      <td width="63" align="center"><?php echo $row_areas['nivel']; ?></td>      
      <td width="67" align="center"><?php echo $row_areas['clave']; ?></td>
      <td width="264"><?php echo $row_areas['descripcion']; ?></td>
      <td align="right"><?php echo number_format($row_areas['sueldobase'], 2, ".", ","); ?></td>
      <td align="right"><?php echo number_format($row_areas['hom'], 2, ".", ","); ?></td>
    </tr>
    <?php } while ($row_areas = mysql_fetch_assoc($areas)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($areas);
?>
