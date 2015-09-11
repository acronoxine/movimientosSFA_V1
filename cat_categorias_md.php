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

  $theValue = function_exists("mysql_real_escape_string") ? mysqli_real_escape_string($theValue) : mysql_escape_string($theValue);

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
  $updateSQL = sprintf("UPDATE cat_categoria SET clave=%s, descripcion=%s, nivel=%s, sueldobase=%s, hom=%s WHERE idcategoria=%s",
                       GetSQLValueString(strtoupper($_POST['clave']), "text"),
                       GetSQLValueString(strtoupper($_POST['descripcion']), "text"),
					   GetSQLValueString(strtoupper($_POST['nivel']), "text"),
					   GetSQLValueString(strtoupper($_POST['sueldobase']), "text"),
					   GetSQLValueString(strtoupper($_POST['hom']), "text"),
                       GetSQLValueString($_POST['idcategoria'], "int"));

  //mysql_select_db($database_conexion, $conexion);
  $Result1 = mysqli_query($conexion,$updateSQL) or die(mysqli_error());

  $updateGoTo = "cat_categorias.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_areas = "-1";
if (isset($_GET['idcategoria'])) {
  $colname_areas = $_GET['idcategoria'];
}
//mysql_select_db($database_conexion, $conexion);
$query_areas = sprintf("SELECT * FROM cat_categoria WHERE idcategoria = %s", GetSQLValueString($colname_areas, "int"));
$areas = mysqli_query($conexion,$query_areas) or die(mysqli_error());
$row_areas = mysqli_fetch_assoc($areas);
$totalRows_areas = mysqli_num_rows($areas);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">

<title>Sistema de N&oacute;mina de Empleados</title>

<link rel="stylesheet" type="text/css" href="css/estilos.css">

<script>

function valida(form)
{
	if(form.nivel.value == "")
	{
		alert("Indique el nivel de la categor�a");
		form.nivel.focus();
		return false;
	}
	
	if(form.clave.value == "")
	{
		alert("Indique la clave de la categor�a");
		form.clave.focus();
		return false;
	}
	
	if(form.descripcion.value == "")
	{
		alert("Indique el nombre de la categor�a");
		form.decripcion.focus();
		return false;
	}
	
	if(form.sueldobase.value == "" || form.sueldobase.value == "0")
	{
		alert("Indique el sueldo base de la categor�a");
		form.sueldobase.focus();
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
    <div id="cuerpo">
    <div id="tituloarriba">
   		<div id="titulosup">Categor�as</div>    
    </div>
    	<div id="panelizq">
			<? include("menu.php"); ?>
      </div>
        <div id="centro_prin">
          <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Nivel:</label></td>
                <td><input class="campo" type="text" name="nivel" value="<?php echo htmlentities($row_areas['nivel'], ENT_COMPAT, 'iso-8859-1'); ?>" size="25" maxlength="12"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Clave:</label></td>
                <td><input class="campo" type="text" name="clave" value="<?php echo htmlentities($row_areas['clave'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" maxlength="5"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Descripci�n:</label></td>
                <td><input class="campo" type="text" name="descripcion" value="<?php echo htmlentities($row_areas['descripcion'], ENT_COMPAT, 'iso-8859-1'); ?>" size="40" maxlength="150"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Sueldo base:</label></td>
                <td><input class="campo" type="text" name="sueldobase" value="<?php echo htmlentities($row_areas['sueldobase'], ENT_COMPAT, 'iso-8859-1'); ?>" size="25" maxlength="12" onKeyPress="return solonumeros(this.form, event);" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Homologaci�n:</label></td>
                <td><input class="campo" type="text" name="hom" value="<?php echo htmlentities($row_areas['hom'], ENT_COMPAT, 'iso-8859-1'); ?>" size="25" maxlength="12" onKeyPress="return solonumeros(this.form, event);" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><input class="boton" type="button" name="regresar" id="regresar" value="REGRESAR" onClick="location.replace('cat_categorias.php');"></td>
                <td><input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();"></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="idcategoria" value="<?php echo $row_areas['idcategoria']; ?>">
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Categor�as</div>    
    </div>
</div>
</body>
</html>
<?php
mysqli_free_result($areas);
?>
