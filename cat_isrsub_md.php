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
  $updateSQL = sprintf("UPDATE isr_sub SET para=%s, hasta=%s, subsidio=%s WHERE id=%s",
                       GetSQLValueString($_POST['para'], "double"),
                       GetSQLValueString($_POST['hasta'], "double"),
                       GetSQLValueString($_POST['subsidio'], "double"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

  $updateGoTo = "cat_isrsub.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_subsidio = "-1";
if (isset($_GET['id'])) {
  $colname_subsidio = $_GET['id'];
}
mysql_select_db($database_conexion, $conexion);
$query_subsidio = sprintf("SELECT id, para, hasta, subsidio FROM isr_sub WHERE id = %s", GetSQLValueString($colname_subsidio, "int"));
$subsidio = mysql_query($query_subsidio, $conexion) or die(mysql_error());
$row_subsidio = mysql_fetch_assoc($subsidio);
$totalRows_subsidio = mysql_num_rows($subsidio);
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

function valida(form)
{
	if(form.para.value == 0 || form.para.value == "")
	{
		alert("Indique el límite PARA INGRESOS!");
		form.para.focus();
		return false;
	}
	
	if(form.hasta.value == 0 || form.hasta.value == "")
	{
		alert("Indique el límite HASTA INGRESOS!");
		form.hasta.focus();
		return false;
	}
	
	if(form.subsidio.value == 0 || form.subsidio.value == "")
	{
		alert("Indique el subsisdio!");
		form.subsidio.focus();
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
   		<div id="titulosup">Subsidio Para el Empleo</div>    
    </div>
    <div id="cuerpo">
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
          <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Para ingresos:</label></td>
                <td><input class="campo" type="text" name="para" value="<?php echo htmlentities($row_subsidio['para'], ENT_COMPAT, 'iso-8859-1'); ?>" size="15" maxlength="15" style="text-align:right;" onKeyPress="return solonumeros(this.form, event);"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Hasta ingresos:</label></td>
                <td><input class="campo" type="text" name="hasta" value="<?php echo htmlentities($row_subsidio['hasta'], ENT_COMPAT, 'iso-8859-1'); ?>" size="15" maxlength="15" style="text-align:right;" onKeyPress="return solonumeros(this.form, event);"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Subsidio:</label></td>
                <td><input class="campo" type="text" name="subsidio" value="<?php echo htmlentities($row_subsidio['subsidio'], ENT_COMPAT, 'iso-8859-1'); ?>" size="15" maxlength="15" style="text-align:right;" onKeyPress="return solonumeros(this.form, event);"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">
                	<input class="boton" type="button" name="regresar" id="regresar" value="REGRESAR" onClick="location.replace('cat_isrsub.php');">
                </td>
                <td><input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();"></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="id" value="<?php echo $row_subsidio['id']; ?>">
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Subsidio Para el Empleo</div>    
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($subsidio);
?>
