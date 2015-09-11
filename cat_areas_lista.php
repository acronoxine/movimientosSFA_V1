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


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO cat_area (clave, descripcion, titular) VALUES (%s, %s, %s)",
                       GetSQLValueString(strtoupper($_POST['clave']), "text"),
                       GetSQLValueString(strtoupper($_POST['descripcion']), "text"),
					   GetSQLValueString(strtoupper($_POST['titular']), "text"));

  //mysqli_select_db($database_conexion, $conexion);
  $Result1 = mysqli_query($conexion,$insertSQL) or die(mysqli_error());
  
  echo "<script>";
  echo "parent.document.form1.reset();";
  echo "</script>";
}


if ((isset($_GET['idarea'])) && ($_GET['idarea'] != "")) {
  $deleteSQL = sprintf("DELETE FROM cat_area WHERE idarea=%s",
                       GetSQLValueString($_GET['idarea'], "int"));

  //mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($conexion,$deleteSQL) or die(mysqli_error());
}

//mysql_select_db($database_conexion, $conexion);
$query_areas = "SELECT * FROM cat_area";
$areas = mysqli_query($conexion,$query_areas) or die(mysqli_error());
$row_areas = mysqli_fetch_assoc($areas);
$totalRows_areas = mysqli_num_rows($areas);
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
<table class="tablagrid" border="0" cellpadding="0" cellspacing="0" width="670">
  <tr class="tablahead">
    <td width="40">&nbsp;</td>
    <td width="40">&nbsp;</td>
    <td width="80" align="center">CLAVE</td>
    <td width="250" align="center">DESCRIPCION</td>
    <td align="center">TITULAR</td>
  </tr>
</table>
</div>
<table class="tablagrid" border="0" cellpadding="0" cellspacing="0" width="670" style="padding-top: 32px;">
  <?php do { ?>
    <tr class="tablaregistros">
      <td width="40"><a onClick="if(confirm('�Confirma que desea eliminar el registro?')) location.href='cat_areas_lista.php?idarea=<?php echo $row_areas['idarea']; ?>';" href="#"><img src="imagenes/borrar.png" width="36" height="36"></a></td>
      <td width="40"><a target="_top" href="cat_areas_md.php?idarea=<?php echo $row_areas['idarea']; ?>"><img src="imagenes/editar.png" width="36" height="36"></a></td>
      <td width="80" align="center"><?php echo $row_areas['clave']; ?></td>
      <td width="250"><?php echo $row_areas['descripcion']; ?></td>
      <td><?php echo $row_areas['titular']; ?></td>
    </tr>
    <?php } while ($row_areas = mysqli_fetch_assoc($areas)); ?>
</table>
</body>
</html>
<?php
mysqli_free_result($areas);
?>
