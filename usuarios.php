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

mysql_select_db($database_conexion, $conexion);
$query_empresas = "SELECT idempresa, razonsocial, rfc, upp FROM empresa";
$empresas = mysql_query($query_empresas, $conexion) or die(mysql_error());
$row_empresas = mysql_fetch_assoc($empresas);
$totalRows_empresas = mysql_num_rows($empresas);
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

function busca(dato)
{
	parent.lista.document.location.replace('usuarios_lista.php?consulta='+form1.consulta.value);
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
          <form method="post" name="form1" action="usuarios_lista.php" target="lista">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Usuario:</label></td>
                <td><input class="campo" type="text" name="usuario" value="" size="10" maxlength="20"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Clave:</label></td>
                <td><input class="campo" type="text" name="clave" value="" size="15" maxlength="45"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Nombre:</label></td>
                <td><input class="campo" type="text" name="nombre" value="" size="40" maxlength="90"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Derechos:</label></td>
                <td>
                	<select name="derechos" class="lista">
                    <option value="usuario">Usuario</option>
                    <option value="admin">Administrador</option>
                    <option value="operador">Operador</option>
                    </select>
                </td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Dependencia(UPP):</label></td>
                <td><select name="idempresa" class="lista">
                  <?php 
do {  
?>
                  <option value="<?php echo $row_empresas['idempresa']?>" ><?php echo $row_empresas['upp'] . " " . $row_empresas['razonsocial']?></option>
                  <?php
} while ($row_empresas = mysql_fetch_assoc($empresas));
?>
                </select></td>
              <tr>
              <tr valign="baseline">
                <td nowrap align="right">&nbsp;</td>
                <td>
                	<input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();">
                    <label class="label">Consulta por nombre de usuario:</label>
                    <input class="campo" type="text" name="consulta" id="consulta" value="" onKeyup="busca(this.value);">
                </td>
              </tr>
              <tr>
              	<td colspan="2">
                	<iframe name="lista" id="lista" src="usuarios_lista.php" style="width:950px; height:500px;"></iframe>
                </td>
              </tr>
            </table>
            <input type="hidden" name="MM_insert" value="form1">
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
mysql_free_result($empresas);
?>
