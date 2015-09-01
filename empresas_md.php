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
  $updateSQL = sprintf("UPDATE empresa SET razonsocial=%s, titular=%s, rfc=%s, clavepatronal=%s, calle=%s, numeroint=%s, numeroext=%s, colonia=%s, cp=%s, ciudad=%s, estado=%s, upp=%s, idbancos=%s WHERE idempresa=%s",
                       GetSQLValueString($_POST['razonsocial'], "text"),
					   GetSQLValueString($_POST['titular'], "text"),
                       GetSQLValueString($_POST['rfc'], "text"),
                       GetSQLValueString($_POST['clavepatronal'], "text"),
                       GetSQLValueString($_POST['calle'], "text"),
                       GetSQLValueString($_POST['numeroint'], "text"),
                       GetSQLValueString($_POST['numeroext'], "text"),
                       GetSQLValueString($_POST['colonia'], "text"),
                       GetSQLValueString($_POST['cp'], "text"),
                       GetSQLValueString($_POST['ciudad'], "text"),
                       GetSQLValueString($_POST['estado'], "text"),
                       GetSQLValueString($_POST['upp'], "text"),
                       GetSQLValueString($_POST['idbancos'], "int"),
                       GetSQLValueString($_POST['idempresa'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

  $updateGoTo = "empresas.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


mysql_select_db($database_conexion, $conexion);
$query_bancos = "SELECT * FROM bancos";
$bancos = mysql_query($query_bancos, $conexion) or die(mysql_error());
$row_bancos = mysql_fetch_assoc($bancos);
$totalRows_bancos = mysql_num_rows($bancos);

$colname_empresa = "-1";
if (isset($_GET['idempresa'])) {
  $colname_empresa = $_GET['idempresa'];
}
mysql_select_db($database_conexion, $conexion);
$query_empresa = sprintf("SELECT idempresa, razonsocial, titular, rfc, clavepatronal, calle, numeroint, numeroext, colonia, cp, ciudad, estado, upp, idbancos FROM empresa WHERE idempresa = %s", GetSQLValueString($colname_empresa, "int"));
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
	if(form.razonsocial.value == "")
	{
		alert("Indique el nombre o raz�n social de la dependencia");
		form.razonsocial.focus();
		return false;
	}
	
	if(form.titular.value == "")
	{
		alert("Indique el nombre del titular de la dependencia");
		form.titular.focus();
		return false;
	}
	
	if(form.rfc.value == "")
	{
		alert("Indique el rfc de la dependencia");
		form.rfc.focus();
		return false;
	}
	
	if(form.clavepatronal.value == "")
	{
		alert("Indique la clave patronal de la dependencia");
		form.clavepatronal.focus();
		return false;
	}
	
	if(form.calle.value == "")
	{
		alert("Indique la calle del domicilio de la dependencia");
		form.calle.focus();
		return false;
	}
	
	if(form.numeroint.value == "" && form.numeroext.value == "")
	{
		alert("Indique el n�mero del domicilio");
		form.numeroint.focus();
		return false;
	}
	
	if(form.colonia.value == "")
	{
		alert("Indique la colonia del domicilio");
		form.colonia.focus();
		return false;
	}
	
	if(form.cp.value == "")
	{
		alert("Indique el c�digo postal del domicilio");
		form.cp.focus();
		return false;
	}
	
	if(form.ciudad.value == "")
	{
		alert("Indique la ciudad donde se localiza la dependencia");
		form.ciudad.focus();
		return false;
	}
	
	if(form.estado.value == "")
	{
		alert("Indique el estado o entidad federativa");
		form.estado.focus();
		return false;
	}
	
	if(form.upp.value == "")
	{
		alert("Indique la unidad program�tica presupuestal(UPP)");
		form.upp.focus();
		return false;
	}
	
	if(form.idbancos.value == "")
	{
		alert("Indique el nombre del banco donde se les deposita a los empleados");
		form.idbancos.focus();
		return false;
	}
	
	return true;
}

function sololetras(form, e)
{
    if(e.keyCode != 0)
      letra = e.keyCode;
    else
      letra = e.which;
    
    if(letra >= 48 && letra <= 57)
       return false;
    else
       return true;
}

function solonumeros(form, e)
{
    if(e.keyCode != 0)
      letra = e.keyCode;
    else
      letra = e.which;
    
    if((letra < 48 || letra > 57) && letra != 37 && letra != 38 && letra != 39 && letra != 40 && letra != 8 && letra != 46)
       return false;
    else
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
   		<div id="titulosup">Dependencia</div>    
    </div>
    	<div id="panelizq">
			<? include("menu.php"); ?>
      </div>
        <div id="centro_prin">
          <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
          <table align="center">
          <tr>
          	<td>
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Raz�n social:</label></td>
                <td><input class="campo" type="text" name="razonsocial" value="<?php echo htmlentities($row_empresa['razonsocial'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" maxlength="150"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Titular:</label></td>
                <td><input class="campo" type="text" name="titular" value="<?php echo htmlentities($row_empresa['titular'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" maxlength="90"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">RFC:</label></td>
                <td><input class="campo" type="text" name="rfc" value="<?php echo htmlentities($row_empresa['rfc'], ENT_COMPAT, 'iso-8859-1'); ?>" size="13" maxlength="13"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Clave patronal:</label></td>
                <td><input class="campo" type="text" name="clavepatronal" value="<?php echo htmlentities($row_empresa['clavepatronal'], ENT_COMPAT, 'iso-8859-1'); ?>" size="15" maxlength="15"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Calle:</label></td>
                <td><input class="campo" type="text" name="calle" value="<?php echo htmlentities($row_empresa['calle'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" maxlength="60"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Numero int:</label></td>
                <td><input class="campo" type="text" name="numeroint" value="<?php echo htmlentities($row_empresa['numeroint'], ENT_COMPAT, 'iso-8859-1'); ?>" size="7" maxlength="7" onKeyPress="return solonumeros(this.form, event)"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Numero ext:</label></td>
                <td><input class="campo" type="text" name="numeroext" value="<?php echo htmlentities($row_empresa['numeroext'], ENT_COMPAT, 'iso-8859-1'); ?>" size="7" maxlength="7" onKeyPress="return solonumeros(this.form, event)"></td>
              </tr>
            </table>
            </td>
          	<td>
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Colonia:</label></td>
                <td><input class="campo" type="text" name="colonia" value="<?php echo htmlentities($row_empresa['colonia'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" maxlength="60"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Cp:</label></td>
                <td><input class="campo" type="text" name="cp" value="<?php echo htmlentities($row_empresa['cp'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" maxlength="5" onKeyPress="return solonumeros(this.form, event)"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Ciudad:</label></td>
                <td><input class="campo" type="text" name="ciudad" value="<?php echo htmlentities($row_empresa['ciudad'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" maxlength="60"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Estado:</label></td>
                <td><input class="campo" type="text" name="estado" value="<?php echo htmlentities($row_empresa['estado'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" maxlength="60"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Upp:</label></td>
                <td><input class="campo" type="text" name="upp" value="<?php echo htmlentities($row_empresa['upp'], ENT_COMPAT, 'iso-8859-1'); ?>" size="2" maxlength="2" onKeyPress="return solonumeros(this.form, event)"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Banco:</label></td>
                <td><select name="idbancos" class="lista">
                  <?php 
do {  
?>
                  <option value="<?php echo $row_bancos['idbancos']?>" ><?php echo $row_bancos['banco']?></option>
                  <?php
} while ($row_bancos = mysql_fetch_assoc($bancos));
?>
                </select></td>
              <tr>
            </table>
            </td>
              <tr valign="baseline">
                <td nowrap><input class="boton" type="button" name="regresar" id="regresar" value="REGRESAR" onClick="location.replace('empresas.php');">
                <input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();"></td>
              </tr>            
            </table>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="idempresa" value="<?php echo $row_empresa['idempresa']; ?>">
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Dependencia</div>    
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($bancos);

mysql_free_result($empresa);
?>
