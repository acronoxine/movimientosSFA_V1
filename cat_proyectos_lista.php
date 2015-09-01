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

if ((isset($_GET['idproyecto'])) && ($_GET['idproyecto'] != "")) {
  $deleteSQL = sprintf("DELETE FROM cat_proyecto WHERE idproyecto=%s",
                       GetSQLValueString($_GET['idproyecto'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO cat_proyecto (clave, descripcion,idsubprograma) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['clave'], "text"),
                       GetSQLValueString(strtoupper($_POST['descripcion']), "text"),
					   GetSQLValueString($_POST['sprograma'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
}

mysql_select_db($database_conexion, $conexion);
$query_proyectos = "SELECT py.idproyecto, py.clave, py.descripcion as proyecto_desc, sp.descripcion as sub_desc
					FROM cat_proyecto py inner join
					cat_subprograma sp on py.idsubprograma=sp.idsubprograma ";
$proyectos = mysql_query($query_proyectos, $conexion) or die(mysql_error());
$row_proyectos = mysql_fetch_assoc($proyectos);
$totalRows_proyectos = mysql_num_rows($proyectos);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>
<body topmargin="0" leftmargin="0">
<table class="tablagrid" border="0" cellpadding="2" cellspacing="1">
  <tr class="tablahead">
    <td width="37">&nbsp;</td>
    <td width="36">&nbsp;</td>
    <td width="90">Clave</td>
    <td width="250">Descripción</td>
    <td width="250">Subprograma</td>
  </tr>
  <?php do { ?>
    <tr class="tablaregistros">
      <td height="37"><a onClick="if(confirm('¿Confirma que desea eliminar el registro?')) location.href='cat_proyectos_lista.php?idproyecto=<?php echo $row_proyectos['idproyecto']; ?>';" href="#"><img src="imagenes/borrar.png" width="35" height="35"></a></td>
      <td><a target="_top" href="cat_proyectos_md.php?idproyecto=<?php echo $row_proyectos['idproyecto']; ?>"><img src="imagenes/editar.png" width="35" height="35"></a></td>
      <td><?php echo $row_proyectos['clave']; ?></td>
      <td><?php echo $row_proyectos['proyecto_desc']; ?></td>
      <td><?php echo $row_proyectos['sub_desc']; ?></td>
    </tr>
    <?php } while ($row_proyectos = mysql_fetch_assoc($proyectos)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($proyectos);
?>
