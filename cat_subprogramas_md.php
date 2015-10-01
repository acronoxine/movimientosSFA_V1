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
<?php require_once('Connections/conexion.php');
header('Content-Type: text/html; charset=UTF-8'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

 // $theValue = function_exists("mysql_real_escape_string") ? mysqli_real_escape_string($theValue) : mysql_escape_string($theValue);

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
  $updateSQL = sprintf("UPDATE cat_subprograma SET clave=%s, descripcion=%s, idprograma=%s WHERE idsubprograma=%s",
                       GetSQLValueString($_POST['clave'], "text"),
                       GetSQLValueString(strtoupper($_POST['descripcion']), "text"),
					   GetSQLValueString($_POST['programa'], "text"),
                       GetSQLValueString($_POST['idsubprograma'], "int"));

  //mysql_select_db($database_conexion, $conexion);
  $Result1 = mysqli_query($conexion,$updateSQL) or die(mysqli_error());

  $updateGoTo = "cat_subprogramas.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_subprogramas = "-1";
if (isset($_GET['idsubprograma'])) {
  $colname_subprogramas = $_GET['idsubprograma'];
}
//mysql_select_db($database_conexion, $conexion);
$query_subprogramas = sprintf("SELECT * FROM cat_subprograma WHERE idsubprograma = %s", GetSQLValueString($colname_subprogramas, "int"));
$subprogramas = mysqli_query($conexion,$query_subprogramas) or die(mysqli_error());
$row_subprogramas = mysqli_fetch_assoc($subprogramas);
$totalRows_subprogramas = mysqli_num_rows($subprogramas);

//mysql_select_db($database_conexion, $conexion);
$query_programa = "SELECT * FROM cat_programa";
$programa = mysqli_query( $conexion, $query_programa ) or die(mysqli_error());
$row_programa = mysqli_fetch_assoc($programa);
$totalRows_programa = mysqli_num_rows($programa);
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
	if(form.clave.value == "")
	{
		alert("Indique la clave del área responsable");
		form.clave.focus();
		return false;
	}
	
	if(form.descripcion.value == "")
	{
		alert("Indique el nombre del área");
		form.decripcion.focus();
		return false;
	}
	
	if(form.programa.value == "")
	{
		alert("Indique un programa");
		form.programa.focus();
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
   		<div id="titulosup">Subprogramas</div>    
    </div>
    	<div id="panelizq">
			<? include("menu.php"); ?>
      </div>
        <div id="centro_prin">
          <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Clave:</label></td>
                <td><input class="campo" type="text" name="clave" value="<?php echo htmlentities($row_subprogramas['clave'], ENT_COMPAT, 'iso-8859-1'); ?>" size="2" maxlength="2"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Descripci&oacute;n:</label></td>
                <td><input class="campo" type="text" name="descripcion" value="<?php echo htmlentities($row_subprogramas['descripcion'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" maxlength="150"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Programa:</label></td>
                <td><select name="programa" class="lista" style="width:180px;">
                	<option value="">Seleccione</option>
                  <?php 
do {  
?>
                  <option value="<?php echo $row_programa['idprograma']?>" <?php if (!(strcmp($row_programa['idprograma'], htmlentities($row_subprogramas['idprograma'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>><?php echo $row_programa['descripcion']?></option>
                  <?php
} while ($row_programa = mysqli_fetch_assoc($programa));
?>
                </select></td>
              <tr>
              <tr valign="baseline">
                <td nowrap align="right"><input class="boton" type="button" name="regresar" class="regresar" value="REGRESAR" onClick="location.replace('cat_subprogramas.php');"></td>
                <td><input class="boton" type="button" name="guardar" class="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();"></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="idsubprograma" value="<?php echo $row_subprogramas['idsubprograma']; ?>">
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Subprogramas</div>    
    </div>
</div>
</body>
</html>
<?php
mysqli_free_result($subprogramas);

mysqli_free_result($programa);
?>
