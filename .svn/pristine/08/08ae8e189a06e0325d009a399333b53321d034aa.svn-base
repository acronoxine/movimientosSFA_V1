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
  $insertSQL = sprintf("INSERT INTO cat_subprograma (clave, descripcion, idprograma) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['clave'], "text"),
                       GetSQLValueString(strtoupper($_POST['descripcion']), "text"),
					   GetSQLValueString($_POST['programa'], "text"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
}

if ((isset($_GET['idsubprograma'])) && ($_GET['idsubprograma'] != "")) {
  $deleteSQL = sprintf("DELETE FROM cat_subprograma WHERE idsubprograma=%s",
                       GetSQLValueString($_GET['idsubprograma'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
}

mysql_select_db($database_conexion, $conexion);
$query_subprogramas = "SELECT p.clave as clave_programa,idsubprograma, s.clave, s.descripcion, p.descripcion as programa FROM cat_subprograma s left join cat_programa p on s.idprograma = p.idprograma";

if(isset($_GET["consulta"]))
{
	$query_subprogramas .= " where s.descripcion like '%$_GET[consulta]%'";
}

$subprogramas = mysql_query($query_subprogramas, $conexion) or die(mysql_error());
$row_subprogramas = mysql_fetch_assoc($subprogramas);
$totalRows_subprogramas = mysql_num_rows($subprogramas);
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
<table class="tablagrid" border="0" cellpadding="0" cellspacing="0" width="700">
  <tr class="tablahead">
    <td width="40">&nbsp;</td>
    <td width="40">&nbsp;</td>
    <td width="113">PROGRAMA</td>
    <td width="113">CLAVE</td>
    <td width="220">DESCRIPCIÓN</td>
    <td width="200">NOMBRE DEL PROGRAMA</td>
  </tr>
</table>
</div>
<table class="tablagrid" border="0" cellpadding="0" cellspacing="0" width="700" style="padding-top: 32px;">
  <?php do { ?>
    <tr class="tablaregistros">
      <td width="40"><a onClick="if(confirm('¿Confirma que desea eliminar el registro?')) location.href='cat_subprogramas_lista.php?idsubprograma=<?php echo $row_subprogramas['idsubprograma']; ?>';" href="#"><img src="imagenes/borrar.png" width="35" height="35"></a></td>
      <td width="40"><a target="_top" href="cat_subprogramas_md.php?idsubprograma=<?php echo $row_subprogramas['idsubprograma']; ?>"><img src="imagenes/editar.png" width="35" height="35"></a></td>
      <td width="113" align="center"><?php echo $row_subprogramas['clave_programa']; ?></td>
      <td width="113" align="center"><?php echo $row_subprogramas['clave']; ?></td>
      <td width="220"><?php echo $row_subprogramas['descripcion']; ?></td>
      <td width="200"><?php echo $row_subprogramas['programa']; ?></td>
    </tr>
    <?php } while ($row_subprogramas = mysql_fetch_assoc($subprogramas)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($subprogramas);
?>
