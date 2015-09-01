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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO usuarios (usuario, clave, nombre, derechos, idempresa) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['usuario'], "text"),
                       GetSQLValueString($_POST['clave'], "text"),
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['derechos'], "text"),
                       GetSQLValueString($_POST['idempresa'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());

  echo "<script>";
  echo "parent.document.form1.reset();";
  echo "</script>";
}

if ((isset($_GET['idusuario'])) && ($_GET['idusuario'] != "")) {
  $deleteSQL = sprintf("DELETE FROM usuarios WHERE idusuario=%s",
                       GetSQLValueString($_GET['idusuario'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
}

mysql_select_db($database_conexion, $conexion);
$query_usuarios = "SELECT idusuario, usuario, clave, nombre, case when derechos = 'usuario' then 'Usuario' when derechos = 'admin' then 'Administrador' when derechos = 'operador' then 'Operador' else '' end as derechos, concat(e.upp, ' ', e.razonsocial) as idempresa FROM usuarios u left join empresa e on u.idempresa = e.idempresa";

if(isset($_GET["consulta"]))
{
	$query_usuarios .= " where nombre like '%$_GET[consulta]%'";
}

$usuarios = mysql_query($query_usuarios, $conexion) or die(mysql_error());
$row_usuarios = mysql_fetch_assoc($usuarios);
$totalRows_usuarios = mysql_num_rows($usuarios);
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
<table class="tablagrid" border="0" cellpadding="0" cellspacing="0" width="1286">
  <tr class="tablahead">
    <td width="40">&nbsp;</td>
    <td width="40">&nbsp;</td>
    <td width="111" align="center">USUARIO</td>
    <td width="133" align="center">CLAVE</td>
    <td width="217" align="center">NOMBRE</td>
    <td width="157" align="center">DERECHOS</td>
    <td align="center">DEPENDENCIA</td>
  </tr>
</table>
</div>
<table class="tablagrid" border="0" cellpadding="0" cellspacing="0" width="1286" style="padding-top: 32px;">
  <?php do { ?>
    <tr class="tablaregistros">
      <td width="40"><a onClick="if(confirm('¿Confirma que desea eliminar el registro?')) location.href='usuarios_lista.php?idusuario=<?php echo $row_usuarios['idusuario']; ?>';" href="#"><img src="imagenes/borrar.png" width="33" height="33"></a></td>
      <td width="40"><a target="_top" href="usuarios_md.php?idusuario=<?php echo $row_usuarios['idusuario']; ?>"><img src="imagenes/editar.png" width="33" height="33"></a></td>
      <td width="111" align="center"><?php echo $row_usuarios['usuario']; ?></td>
      <td width="133" align="center"><?php echo $row_usuarios['clave']; ?></td>
      <td width="217"><?php echo $row_usuarios['nombre']; ?></td>
      <td width="157" align="center"><?php echo $row_usuarios['derechos']; ?></td>
      <td><?php echo $row_usuarios['idempresa']; ?></td>
    </tr>
    <?php } while ($row_usuarios = mysql_fetch_assoc($usuarios)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($usuarios);
?>
