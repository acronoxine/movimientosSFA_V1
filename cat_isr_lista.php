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
  $insertSQL = sprintf("INSERT INTO isr (limiteinferior, limitesuperior, cuotafija, porciento) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['limiteinferior'], "double"),
                       GetSQLValueString($_POST['limitesuperior'], "double"),
                       GetSQLValueString($_POST['cuotafija'], "double"),
                       GetSQLValueString($_POST['porciento'], "double"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  
  echo "<script>";
  echo "parent.document.form1.reset();";
  echo "</script>";
}

mysql_select_db($database_conexion, $conexion);
$query_isr = "SELECT id, limiteinferior, limitesuperior, cuotafija, porciento FROM isr";
$isr = mysql_query($query_isr, $conexion) or die(mysql_error());
$row_isr = mysql_fetch_assoc($isr);
$totalRows_isr = mysql_num_rows($isr);
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
<table class="tablagrid" border="0" cellpadding="1" cellspacing="0" width="550">
  <tr class="tablahead">
    <td width="38" align="center">&nbsp;</td>
    <td width="37" align="center">&nbsp;</td>
    <td width="137" align="center">Límite inferior</td>
    <td width="140" align="center">Límite superior</td>
    <td width="117" align="center">Cuota fija</td>
    <td align="center">Porcentaje</td>
  </tr>
</table>
</div>

<table class="tablagrid" border="0" cellpadding="1" cellspacing="0" width="550" style="padding-top: 32px;">
  <?php do { ?>
    <tr class="tablaregistros">
      <td width="38" align="center"><a href="#" onClick="if(confirm('¿Confirma que desea eliminar el registro?')) location.replace('cat_isr_lista.php?id=<?php echo $row_isr['id']; ?>');"><img src="imagenes/borrar.png" width="35" height="35"></a></td>
      <td width="37" align="center"><a target="_top" href="cat_isr_md.php?id=<?php echo $row_isr['id']; ?>"><img src="imagenes/editar.png" width="35" height="35"></a></td>
      <td width="137" align="center"><?php echo number_format($row_isr['limiteinferior'], 2, ".", ","); ?></td>
      <td width="140" align="center"><?php echo number_format($row_isr['limitesuperior'], 2, ".", ","); ?></td>
      <td width="117" align="center"><?php echo number_format($row_isr['cuotafija'], 2, ".", ","); ?></td>
      <td align="center"><?php echo number_format($row_isr['porciento'], 2, ".", ","); ?></td>
    </tr>
    <?php } while ($row_isr = mysql_fetch_assoc($isr)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($isr);
?>
