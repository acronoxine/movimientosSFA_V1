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

  //$theValue = function_exists("mysql_real_escape_string") ? mysqli_real_escape_string($theValue) : mysql_escape_string($theValue);

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
  $updateSQL = sprintf("UPDATE cat_conceptos SET descripcion=%s, afectacion=%s, importe=%s, dias=%s, porcentaje=%s, uso=%s, tipo=%s WHERE idconceptos=%s",
                       GetSQLValueString(strtoupper($_POST['descripcion']), "text"),
                       GetSQLValueString($_POST['afectacion'], "text"),
                       GetSQLValueString($_POST['importe'], "double"),
                       GetSQLValueString($_POST['dias'], "double"),
                       GetSQLValueString($_POST['porcentaje'], "double"),
					   GetSQLValueString($_POST['uso'], "text"),
                       GetSQLValueString($_POST['tipo'], "text"),
                       GetSQLValueString($_POST['idconceptos'], "int"));

  //mysql_select_db($database_conexion, $conexion);
  $Result1 = mysqli_query($conexion,$updateSQL) or die(mysql_error());

  $updateGoTo = "cat_conceptos.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_conceptos = "-1";
if (isset($_GET['idconceptos'])) {
  $colname_conceptos = $_GET['idconceptos'];
}
//mysql_select_db($database_conexion, $conexion);
$query_conceptos = sprintf("SELECT * FROM cat_conceptos WHERE idconceptos = %s", GetSQLValueString($colname_conceptos, "int"));
$conceptos = mysqli_query($conexion,$query_conceptos) or die(mysqli_error());
$row_conceptos = mysqli_fetch_assoc($conceptos);
$totalRows_conceptos = mysqli_num_rows($conceptos);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link rel="shortcut icon" type="image/x-icon" href="http://www.michoacan.gob.mx/wp-content/themes/estaenti/img/favicon.ico">
<title>Sistema de N&oacute;mina de Empleados</title>

<link rel="stylesheet" type="text/css" href="css/estilos.css">

<script>

function valida(form)
{

	if(form.descripcion.value == "")
	{
		alert("Indique la descripción del concepto");
		form.decripcion.focus();
		return false;
	}
	
	if(form._afectacion.value == "")
	{
		alert("Indique si afecta a todos los empleados en general o solo en casos particulares");
		form.afectacion.focus();
		return false;
	}
	

	return true;
}

function activa(form)
{
	switch(form._activa.value)
	{
		case 'IMP':
			document.getElementById('dias').disabled = true;
			document.getElementById('porcentaje').disabled = true;
			document.getElementById('importe').disabled = false;
		break;
		case 'DIA':
			document.getElementById('importe').disabled = true;
			document.getElementById('porcentaje').disabled = true;
			document.getElementById('dias').disabled = false;
		break;
		case 'POR':
			document.getElementById('importe').disabled = true;
			document.getElementById('dias').disabled = true;
			document.getElementById('porcentaje').disabled = false;
		break;
	}
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

<body>
<div id="todo">
	<div id="cabeza_prin">
    </div>
    <div id="cuerpo">
    <div id="tituloarriba">
   		<div id="titulosup">Conceptos</div>    
    </div>
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
            <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
              <table align="center">
                <tr valign="baseline">
                  <td nowrap align="right"><label class="label">Descripción:</label></td>
                  <td colspan="2"><input class="campo" type="text" name="descripcion" value="<?php echo htmlentities($row_conceptos['descripcion'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" maxlength="150"></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right"><label class="label">Afectacion:</label></td>
                  <td valign="baseline" colspan="2"><table>
                    <tr>
                      <td><input type="radio" name="afectacion" id="general" value="G" <?php if (!(strcmp(htmlentities($row_conceptos['afectacion'], ENT_COMPAT, 'iso-8859-1'),"G"))) {echo "checked=\"checked\"";} ?>>
                        <label class="label" for="general">General</label></td>
                    </tr>
                    <tr>
                      <td><input type="radio" name="afectacion" id="individual" value="I" <?php if (!(strcmp(htmlentities($row_conceptos['afectacion'], ENT_COMPAT, 'iso-8859-1'),"I"))) {echo "checked=\"checked\"";} ?>>
                        <label class="label" for="individual">Individual</label></td>
                    </tr>
                  </table></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right"><label class="label">Importe:</label></td>
                  <td><input <? if($row_conceptos['uso'] != "IMP") echo "disabled"; ?> class="campo" type="text" name="importe" id="importe" value="<?php echo htmlentities($row_conceptos['importe'], ENT_COMPAT, 'iso-8859-1'); ?>" size="12" maxlength="12" style="text-align:right;" onKeyPress="return solonumeros(this.form, event)"></td>
                  <td>
                  	<input <? if($row_conceptos['uso'] == "IMP") echo "checked"; ?> type="radio" name="uso" id="porimporte" value="IMP" onClick="document.getElementById('_activa').value = this.value; activa(this.form);">
                    <label class="label" for="porimporte">Se usuar&aacute; importe</label>
                  </td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right"><label class="label">Dias:</label></td>
                  <td><input <? if($row_conceptos['uso'] != "DIA") echo "disabled"; ?> class="campo" type="text" name="dias" id="dias" value="<?php echo htmlentities($row_conceptos['dias'], ENT_COMPAT, 'iso-8859-1'); ?>" size="3" maxlength="3" style="text-align:right;" onKeyPress="return solonumeros(this.form, event)"></td>
                  <td>
                  	<input <? if($row_conceptos['uso'] == "DIA") echo "checked"; ?> type="radio" name="uso" id="pordias" value="DIA" onClick="document.getElementById('_activa').value = this.value; activa(this.form);">
                    <label class="label" for="pordias">Se usuar&aacute;n d&iacute;as</label>
                  </td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right"><label class="label">Porcentaje:</label></td>
                  <td><input <? if($row_conceptos['uso'] != "POR") echo "disabled"; ?> class="campo" type="text" name="porcentaje" id="porcentaje" value="<?php echo htmlentities($row_conceptos['porcentaje'], ENT_COMPAT, 'iso-8859-1'); ?>" size="3" maxlength="3" style="text-align:right;" onKeyPress="return solonumeros(this.form, event)"></td>
                  <td>
                  	<input <? if($row_conceptos['uso'] == "POR") echo "checked"; ?> type="radio" name="uso" id="porporcentaje" value="POR" onClick="document.getElementById('_activa').value = this.value; activa(this.form);">
                    <label class="label" for="porporcentaje">Se usuar&aacute; porcentaje</label>
                  </td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right"><label class="label">Tipo:</label></td>
                  <td colspan="2"><select name="tipo" class="lista">
                    <option value="P" <?php if (!(strcmp("P", htmlentities($row_conceptos['tipo'], ENT_COMPAT, 'UTF-8')))) {echo "SELECTED";} ?>>Percepción</option>
                    <option value="D" <?php if (!(strcmp("D", htmlentities($row_conceptos['tipo'], ENT_COMPAT, 'UTF-8')))) {echo "SELECTED";} ?>>Deducción</option>
                  </select></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right"><input class="boton" type="button" name="regresar" id="regresar" value="REGRESAR" onClick="location.replace('cat_conceptos.php');"></td>
                  <td colspan="2"><input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="submit();"></td>
                </tr>
              </table>
              <input type="hidden" name="MM_update" value="form1">
              <input type="hidden" name="idconceptos" value="<?php echo $row_conceptos['idconceptos']; ?>">
              <input type="hidden" name="_afectacion" id="_afectacion" value="<? echo $row_conceptos['afectacion']; ?>">
              <input type="hidden" name="_activa" id="_activa" value="<? echo $row_conceptos['uso']; ?>">
            </form>
            <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Conceptos</div>    
    </div>
</div>
</body>
</html>
<?php
mysqli_free_result($conceptos);
?>
