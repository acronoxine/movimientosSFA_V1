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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO isr_sub (para, hasta, subsidio) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['para'], "double"),
                       GetSQLValueString($_POST['hasta'], "double"),
                       GetSQLValueString($_POST['subsidio'], "double"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
}

if ((isset($_GET['id'])) && ($_GET['id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM isr_sub WHERE id=%s",
                       GetSQLValueString($_GET['id'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
}

$colname_subsidio = "-1";
if (isset($_GET['para'])) {
  $colname_subsidio = $_GET['para'];
}
mysql_select_db($database_conexion, $conexion);
$query_subsidio = "SELECT id, para, hasta, subsidio FROM isr_sub";
$subsidio = mysql_query($query_subsidio, $conexion) or die(mysql_error());
$row_subsidio = mysql_fetch_assoc($subsidio);
$totalRows_subsidio = mysql_num_rows($subsidio);
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
<table border="0" class="tablagrid" width="490" cellpadding="2" cellspacing="0">
  <tr class="tablahead">
    <td width="36">&nbsp;</td>
    <td width="36">&nbsp;</td>
    <td width="134">Para ingresos</td>
    <td width="147">Hasta ingresos</td>
    <td>Subsidio</td>
  </tr>
</table>
</div>

<table border="0" class="tablagrid" width="490" cellpadding="2" cellspacing="0" style="padding-top: 36px;">
  <?php do { ?>
    <tr class="tablaregistros">
      <td width="36"><a onClick="if(confirm('¿Confirma que desea eliminar el registro?')) location.replace('cat_isrsub_lista.php?id=<?php echo $row_subsidio['id']; ?>');" href="#"><img src="imagenes/borrar.png" width="35" height="35"></a></td>
      <td width="36"><a target="_top" href="cat_isrsub_md.php?id=<?php echo $row_subsidio['id']; ?>"><img src="imagenes/editar.png" width="35" height="35"></a></td>
      <td width="134"><?php echo $row_subsidio['para']; ?></td>
      <td width="147"><?php echo $row_subsidio['hasta']; ?></td>
      <td><?php echo $row_subsidio['subsidio']; ?></td>
    </tr>
    <?php } while ($row_subsidio = mysql_fetch_assoc($subsidio)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($subsidio);
?>
