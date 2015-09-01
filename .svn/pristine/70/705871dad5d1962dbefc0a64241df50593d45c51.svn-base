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

if ((isset($_GET['idbeneficiarios'])) && ($_GET['idbeneficiarios'] != "")) {
	
  $deleteSQL = sprintf("DELETE FROM cat_beneficiarios WHERE idbeneficiarios=%s",
                       GetSQLValueString($_GET['idbeneficiarios'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
  
  if($Result1)
  {
	  $sql = "Delete from nomina where idbeneficiarios = '$_GET[idbeneficiarios]' and concepto = '252'";
	  $res = mysql_query($sql, $conexion);
  }
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO cat_beneficiarios (paterno, materno, nombres, porcentaje, importe, idnominaemp) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['paterno'], "text"),
                       GetSQLValueString($_POST['materno'], "text"),
                       GetSQLValueString($_POST['nombres'], "text"),
					   GetSQLValueString($_POST['porcentaje'], "int"),
					   GetSQLValueString($_POST['importe'], "int"),
					   GetSQLValueString($_POST['idnominaemp'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  
  if($Result1)
  {
      $idbeneficiarios = mysql_insert_id();
	  
	  $insertSQL = sprintf("INSERT INTO nomina (idnominaemp, concepto, importe, tipo, idbeneficiarios) VALUES (%s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['idnominaemp'], "int"),
						   GetSQLValueString("252", "int"),
						   GetSQLValueString($_POST['importe'], "double"),
						   GetSQLValueString("D", "text"),
						   GetSQLValueString($idbeneficiarios, "double"));
	
	  mysql_select_db($database_conexion, $conexion);
	  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  }
  
  echo "<script>";
  echo "parent.document.form1.reset();";
  echo "</script>";
}

mysql_select_db($database_conexion, $conexion);
$query_beneficiarios = "SELECT idbeneficiarios, b.paterno, b.materno, b.nombres, b.porcentaje, b.importe, concat(n.paterno, ' ', n.materno, ' ', n.nombres) as empleado FROM cat_beneficiarios b left join nominaemp n on b.idnominaemp = n.idnominaemp";
$beneficiarios = mysql_query($query_beneficiarios, $conexion) or die(mysql_error());
$row_beneficiarios = mysql_fetch_assoc($beneficiarios);
$totalRows_beneficiarios = mysql_num_rows($beneficiarios);
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

<script>

function miseleccion(dato, obj)
{
	document.getElementById(obj).checked = true;
	parent.document.getElementById("_idbeneficiarios").value = dato;
}

</script>

</head>
<body topmargin="0" leftmargin="0">
<div id="encabezado">
<table class="tablagrid" border="0" cellpadding="1" cellspacing="0" width="1535">
  <tr class="tablahead">
    <td width="38">&nbsp;</td>
    <td width="37">&nbsp;</td>
    <td width="203" align="center">Paterno</td>
    <td width="210" align="center">Materno</td>
    <td width="237" align="center">Nombres</td>
    <td width="237" align="center">Porcentaje</td>
    <td width="237" align="center">Importe</td>
    <td align="center">Empleado</td>
  </tr>
</table>
</div>

<table class="tablagrid" border="0" cellpadding="1" cellspacing="0" width="1535" style="padding-top: 32px;">
  <?php do { ?>
    <tr class="tablaregistros">
      <td width="38" height="39"><a href="#" onClick="if(confirm('¿Confirma que desea eliminar el registro?')) location.replace('cat_beneficiarios_lista.php?idbeneficiarios=<?php echo $row_beneficiarios['idbeneficiarios']; ?>');"><img src="imagenes/borrar.png" width="35" height="35"></a></td>
      <td width="37"><a target="_top" href="cat_beneficiarios_md.php?idbeneficiarios=<?php echo $row_beneficiarios['idbeneficiarios']; ?>"><img src="imagenes/editar.png" width="35" height="35"></a></td>
      <td width="203" align="center"><?php echo $row_beneficiarios['paterno']; ?></td>
      <td width="210" align="center"><?php echo $row_beneficiarios['materno']; ?></td>
      <td width="237" align="center"><?php echo $row_beneficiarios['nombres']; ?></td>
      <td width="237" align="center"><?php echo number_format($row_beneficiarios['porcentaje'], 2, ".", ","); ?></td>
      <td width="237" align="right"><?php echo number_format($row_beneficiarios['importe'], 2, ".", ""); ?></td>
      <td align="center"><?php echo $row_beneficiarios['empleado']; ?></td>
    </tr>
    <?php } while ($row_beneficiarios = mysql_fetch_assoc($beneficiarios)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($beneficiarios);
?>
