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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE usuarios SET usuario=%s, clave=%s, nombre=%s, derechos=%s, idempresa=%s WHERE idusuario=%s",
                       GetSQLValueString($_POST['usuario'], "text"),
                       GetSQLValueString($_POST['clave'], "text"),
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['derechos'], "text"),
                       GetSQLValueString($_POST['idempresa'], "int"),
                       GetSQLValueString($_POST['idusuario'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

  $updateGoTo = "usuarios.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_usuarios = "-1";
if (isset($_GET['idusuario'])) {
  $colname_usuarios = $_GET['idusuario'];
}
mysql_select_db($database_conexion, $conexion);
$query_usuarios = sprintf("SELECT idusuario, usuario, clave, nombre, derechos, idempresa FROM usuarios WHERE idusuario = %s", GetSQLValueString($colname_usuarios, "int"));
$usuarios = mysql_query($query_usuarios, $conexion) or die(mysql_error());
$row_usuarios = mysql_fetch_assoc($usuarios);
$totalRows_usuarios = mysql_num_rows($usuarios);

mysql_select_db($database_conexion, $conexion);
$query_empresa = "SELECT idempresa, razonsocial, upp FROM empresa";
$empresa = mysql_query($query_empresa, $conexion) or die(mysql_error());
$row_empresa = mysql_fetch_assoc($empresa);
$totalRows_empresa = mysql_num_rows($empresa);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link type="image/x-icon" href="imagenes/logomich200.ico" rel="icon" />
<link type="image/x-icon" href="imagenes/logomich200.ico" rel="shortcut icon" />
<title>Sistema de N&oacute;mina de Empleados</title>

<link rel="stylesheet" type="text/css" href="css/estilos.css">

<script>

function valida(form)
{
	if(form.usuario.value == "")
	{
		alert("Indique el nombre de usuario");
		form.usuario.focus();
		return false;
	}
	
	if(form.clave.value == "")
	{
		alert("Indique una contraseña");
		form.clave.focus();
		return false;
	}
	
	if(form.nombre.value == "")
	{
		alert("Indique el nombre del usuario");
		form.nombre.focus();
		return false;
	}
	
	if(form.derechos.value == "")
	{
		alert("Seleccione un tipo de derecho");
		form.derechos.focus();
		return false;
	}
	
	if(form.idempresa.value == "")
	{
		alert("Seleccione una dependencia");
		form.idempresa.focus();
		return false;
	}
	
	return true;
}

</script>

</head>
<body topmargin="0" leftmargin="0">
<div id="todo">
	<div id="cabeza_prin">
    </div>
    <div id="cuerpo">
    <div id="tituloarriba">
   		<div id="titulosup">Usuarios</div>    
    </div>
    	<div id="panelizq">
			<? include("menu.php"); ?>
      </div>
        <div id="centro_prin">
          <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Usuario:</label></td>
                <td><input class="campo" type="text" name="usuario" value="<?php echo htmlentities($row_usuarios['usuario'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Clave:</label></td>
                <td><input class="campo" type="text" name="clave" value="<?php echo htmlentities($row_usuarios['clave'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Nombre:</label></td>
                <td><input class="campo" type="text" name="nombre" value="<?php echo htmlentities($row_usuarios['nombre'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Derechos:</label></td>
                <td><select name="derechos" class="lista">
                  <option value="usuario" <?php if (!(strcmp("usuario", htmlentities($row_usuarios['derechos'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Usuario</option>
                  <option value="admin" <?php if (!(strcmp("admin", htmlentities($row_usuarios['derechos'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Administrador</option>
                  <option value="operador" <?php if (!(strcmp("operador", htmlentities($row_usuarios['derechos'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Operador</option>
                </select></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Dependencia:</label></td>
                <td><select name="idempresa" class="lista">
                	<option value="">Seleccione</option>
                  <?php 
do {  
?>
                  <option value="<?php echo $row_empresa['idempresa']?>" <?php if (!(strcmp($row_empresa['idempresa'], htmlentities($row_usuarios['idempresa'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>><?php echo $row_empresa['razonsocial']?></option>
                  <?php
} while ($row_empresa = mysql_fetch_assoc($empresa));
?>
                </select></td>
              <tr>
              <tr valign="baseline">
                <td nowrap align="right"><input class="boton" type="button" name="regresar" id="regresar" value="REGRESAR" onClick="location.replace('usuarios.php');"></td>
                <td><input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();"></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="idusuario" value="<?php echo $row_usuarios['idusuario']; ?>">
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Usuarios</div>    
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($usuarios);

mysql_free_result($empresa);
?>
