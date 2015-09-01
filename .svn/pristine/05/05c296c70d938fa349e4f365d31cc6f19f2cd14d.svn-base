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

if ((isset($_GET['id'])) && ($_GET['id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM isranual WHERE id=%s",
                       GetSQLValueString($_GET['id'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO isranual (limiteinferior, limitesuperior, cuotafija, porciento, mes, anio) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['limiteinferior'], "double"),
                       GetSQLValueString($_POST['limitesuperior'], "double"),
                       GetSQLValueString($_POST['cuotafija'], "double"),
                       GetSQLValueString($_POST['porciento'], "double"),
                       GetSQLValueString($_POST['mes'], "text"),
                       GetSQLValueString($_POST['anio'], "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  
  echo "<script>";
  echo "parent.document.form1.limiteinferior.value = '0';";
  echo "parent.document.form1.limitesuperior.value = '0';";
  echo "parent.document.form1.cuotafija.value = '0';";
  echo "parent.document.form1.porciento.value = '0';";
  echo "</script>";
}

mysql_select_db($database_conexion, $conexion);
$query_isranual = "SELECT id, limiteinferior, limitesuperior, cuotafija, porciento, 
case 
	when mes = 1 then 'Enero'
	when mes = 2 then 'Febrero'
	when mes = 3 then 'Marzo'
	when mes = 4 then 'Abril'
	when mes = 5 then 'Mayo'
	when mes = 6 then 'Junio'
	when mes = 7 then 'Julio'
	when mes = 8 then 'Agosto'
	when mes = 9 then 'Septiembre'
	when mes = 10 then 'Octubre'
	when mes = 11 then 'Noviembre'
	when mes = 12 then 'Diciembre' end as mes
, anio FROM isranual";

if(isset($_GET["bmes"]))
{
	$query_isranual .= " where mes = '$_GET[bmes]' and anio = '$_GET[banio]'";
}else{
	$query_isranual .= " where mes = '" . date("n") . "' and anio = '" . date("Y") . "'";
}

$isranual = mysql_query($query_isranual, $conexion) or die(mysql_error());
$row_isranual = mysql_fetch_assoc($isranual);
$totalRows_isranual = mysql_num_rows($isranual);
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
<body leftmargin="0" topmargin="0">

<div id="encabezado">
<table class="tablagrid" border="0" cellpadding="1" cellspacing="0" width="794">
  <tr class="tablahead">
    <td align="center" width="43">&nbsp;</td>
    <td align="center" width="36">&nbsp;</td>
    <td align="center" width="134">Límite inferior</td>
    <td align="center" width="145">Límite superior</td>
    <td align="center" width="117">Cuota fija</td>
    <td align="center" width="118">Porcentaje</td>
    <td align="center" width="101">Mes</td>
    <td align="center">Año</td>
  </tr>
</table>
</div>

<table class="tablagrid" border="0" cellpadding="1" cellspacing="0" width="794" style="padding-top: 32px;">
  <?php do { ?>
    <tr class="tablaregistros">
      <td align="center" width="43"><a href="#" onClick="if(confirm('¿Confirma que desea eliminar el registro?')) location.replace('cat_isranual_lista.php?id=<?php echo $row_isranual['id']; ?>');"><img src="imagenes/borrar.png" width="35" height="35"></a></td>
      <td align="center" width="36"><a target="_top" href="cat_isranual_md.php?id=<?php echo $row_isranual['id']; ?>"><img src="imagenes/editar.png" width="35" height="35"></a></td>
      <td align="center" width="134"><?php echo number_format($row_isranual['limiteinferior'], 2, ".", ","); ?></td>
      <td align="center" width="145"><?php echo number_format($row_isranual['limitesuperior'], 2, ".", ","); ?></td>
      <td align="center" width="117"><?php echo number_format($row_isranual['cuotafija'], 2, ".", ","); ?></td>
      <td align="center" width="118"><?php echo number_format($row_isranual['porciento'], 2, ".", ","); ?></td>
      <td align="center" width="101"><?php echo $row_isranual['mes']; ?></td>
      <td align="center"><?php echo $row_isranual['anio']; ?></td>
    </tr>
    <?php } while ($row_isranual = mysql_fetch_assoc($isranual)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($isranual);
?>
