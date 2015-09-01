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
  $updateSQL = sprintf("UPDATE isranual SET limiteinferior=%s, limitesuperior=%s, cuotafija=%s, porciento=%s, mes=%s, anio=%s WHERE id=%s",
                       GetSQLValueString($_POST['limiteinferior'], "double"),
                       GetSQLValueString($_POST['limitesuperior'], "double"),
                       GetSQLValueString($_POST['cuotafija'], "double"),
                       GetSQLValueString($_POST['porciento'], "double"),
                       GetSQLValueString($_POST['mes'], "text"),
                       GetSQLValueString($_POST['anio'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

  $updateGoTo = "cat_isranual.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_isranual = "-1";
if (isset($_GET['id'])) {
  $colname_isranual = $_GET['id'];
}
mysql_select_db($database_conexion, $conexion);
$query_isranual = sprintf("SELECT id, limiteinferior, limitesuperior, cuotafija, porciento, mes, anio FROM isranual WHERE id = %s", GetSQLValueString($colname_isranual, "int"));
$isranual = mysql_query($query_isranual, $conexion) or die(mysql_error());
$row_isranual = mysql_fetch_assoc($isranual);
$totalRows_isranual = mysql_num_rows($isranual);
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
	if(form.limiteinferior.value == "")
	{
		alert("Indique el límite inferior!");
		form.limiteinferior.focus();
		return false;
	}
	
	if(form.limitesuperior.value == "")
	{
		alert("Indique el límite superior!");
		form.limitesuperior.focus();
		return false;
	}
	
	if(form.cuotafija.value == "")
	{
		alert("Indique la cuota fija!");
		form.cuotafija.focus();
		return false;
	}
	
	if(form.porciento.value == "")
	{
		alert("Indique el porcentaje!");
		form.porciento.focus();
		return false;
	}
	
	if(form.anio.value == "")
	{
		alert("Indique el año de aplicación!");
		form.anio.focus();
		return false;
	}
	
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
    <div id="tituloarriba">
   		<div id="titulosup">Tabla de ISR anual</div>    
    </div>
    <div id="cuerpo">
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
          <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Límite inferior:</label></td>
                <td><input class="campo" type="text" name="limiteinferior" value="<?php echo htmlentities($row_isranual['limiteinferior'], ENT_COMPAT, 'iso-8859-1'); ?>" onClick="select();" onKeyPress="return solonumeros(this.form, event);" size="15" maxlength="15" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Límite superior:</label></td>
                <td><input class="campo" type="text" name="limitesuperior" value="<?php echo htmlentities($row_isranual['limitesuperior'], ENT_COMPAT, 'iso-8859-1'); ?>" onClick="select();" onKeyPress="return solonumeros(this.form, event);" size="15" maxlength="15" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Cuota fija:</label></td>
                <td><input class="campo" type="text" name="cuotafija" value="<?php echo htmlentities($row_isranual['cuotafija'], ENT_COMPAT, 'iso-8859-1'); ?>" onClick="select();" onKeyPress="return solonumeros(this.form, event);" size="15" maxlength="15" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Porcentaje:</label></td>
                <td><input class="campo" type="text" name="porciento" value="<?php echo htmlentities(number_format($row_isranual['porciento'], 2, ".", ""), ENT_COMPAT, 'iso-8859-1'); ?>" onClick="select();" onKeyPress="return solonumeros(this.form, event);" size="15" maxlength="15" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Mes:</label></td>
                <td><select name="mes" class="lista">
                  <option value="1" <?php if (!(strcmp(1, htmlentities($row_isranual['mes'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>01</option>
                  <option value="2" <?php if (!(strcmp(2, htmlentities($row_isranual['mes'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>02</option>
                  <option value="3" <?php if (!(strcmp(3, htmlentities($row_isranual['mes'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>03</option>
                  <option value="4" <?php if (!(strcmp(4, htmlentities($row_isranual['mes'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>04</option>
                  <option value="5" <?php if (!(strcmp(5, htmlentities($row_isranual['mes'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>05</option>
                  <option value="6" <?php if (!(strcmp(6, htmlentities($row_isranual['mes'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>06</option>
                  <option value="7" <?php if (!(strcmp(7, htmlentities($row_isranual['mes'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>07</option>
                  <option value="8" <?php if (!(strcmp(8, htmlentities($row_isranual['mes'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>08</option>
                  <option value="9" <?php if (!(strcmp(9, htmlentities($row_isranual['mes'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>09</option>
                  <option value="10" <?php if (!(strcmp(10, htmlentities($row_isranual['mes'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>10</option>
                  <option value="11" <?php if (!(strcmp(11, htmlentities($row_isranual['mes'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>11</option>
                  <option value="12" <?php if (!(strcmp(12, htmlentities($row_isranual['mes'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>12</option>
                </select></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Año:</label></td>
                <td><input class="campo" type="text" name="anio" value="<?php echo htmlentities($row_isranual['anio'], ENT_COMPAT, 'iso-8859-1'); ?>" onClick="select();" onKeyPress="return solonumeros(this.form, event);" size="15" maxlength="15" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><input class="boton" type="button" name="regresar" id="regresar" value="REGRESAR" onClick="location.replace('cat_isranual.php')"></td>
                <td><input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();"></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="id" value="<?php echo $row_isranual['id']; ?>">
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Tabla de ISR anual
        </div>    
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($isranual);
?>
