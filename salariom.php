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
  $updateSQL = sprintf("UPDATE salmin SET importe=%s WHERE idsalmin=%s",
                       GetSQLValueString($_POST['importe'], "double"),
                       GetSQLValueString($_POST['idsalmin'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
}

mysql_select_db($database_conexion, $conexion);
$query_sm = "SELECT idsalmin, importe FROM salmin limit 1";
$sm = mysql_query($query_sm, $conexion) or die(mysql_error());
$row_sm = mysql_fetch_assoc($sm);
$totalRows_sm = mysql_num_rows($sm);
?>
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
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link type="image/x-icon" href="imagenes/logomich200.ico" rel="icon" />
<link type="image/x-icon" href="imagenes/logomich200.ico" rel="shortcut icon" />
<title>Sistema de N&oacute;mina de Empleados</title>

<link rel="stylesheet" type="text/css" href="css/estilos.css">

<script>

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

function valida(form)
{
	if(form.importe.value == "")
	{
		alert("Indique el importe del salario mínimo!");
		form.importe.focus();
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
    <div id="tituloarriba">
   		<div id="titulosup">Salario Mínimo</div>    
    </div>
    <div id="cuerpo">
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
          <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
            <table align="center" border="0" cellpadding="1" cellspacing="1">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Salario mínimo actual:</label></td>
                <td><input class="campo" type="text" name="importe" value="<?php echo htmlentities($row_sm['importe'], ENT_COMPAT, 'iso-8859-1'); ?>" onKeyPress="return solonumeros(this.form, event);" size="10" maxlength="10" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td colspan="2" align="center"><input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();"></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="idsalmin" value="<?php echo $row_sm['idsalmin']; ?>">
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Salario Mínimo</div>    
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($sm);
?>
