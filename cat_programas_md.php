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
  $updateSQL = sprintf("UPDATE cat_programa SET clave=%s, descripcion=%s,idarea=%s WHERE idprograma=%s",
                       GetSQLValueString($_POST['clave'], "text"),
                       GetSQLValueString(strtoupper($_POST['descripcion']), "text"),
                       GetSQLValueString($_POST['ur'], "int"),
					   GetSQLValueString($_POST['idprograma'], "int")
					   );

  //mysql_select_db($database_conexion, $conexion);
  $Result1 = mysqli_query($updateSQL, $conexion) or die(mysqli_error());

  $updateGoTo = "cat_programas.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_programas = "-1";
if (isset($_GET['idprograma'])) {
  $colname_programas = $_GET['idprograma'];
}
//mysql_select_db($database_conexion, $conexion);
$query_programas = sprintf("SELECT p.descripcion,p.clave,p.idprograma,p.idarea,a.clave as clave_area FROM cat_programa p INNER JOIN cat_area a on a.idarea=p.idarea WHERE idprograma = %s", GetSQLValueString($colname_programas, "int"));
$programas = mysqli_query($conexion,$query_programas) or die(mysqli_error());
$row_programas = mysqli_fetch_assoc($programas);
$totalRows_programas = mysqli_num_rows($programas);
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
		alert("Indique la clave del programa");
		form.clave.focus();
		return false;
	}
	
	if(form.descripcion.value == "")
	{
		alert("Indique el nombre del programa");
		form.decripcion.focus();
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
   		<div id="titulosup">Programas</div>    
    </div>
    	<div id="panelizq">
			<? include("menu.php"); ?>
      </div>
        <div id="centro_prin">
          <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Clave:</label></td>
                <td><input class="campo" type="text" name="clave" value="<?php echo htmlentities($row_programas['clave'], ENT_COMPAT, 'iso-8859-1'); ?>" size="2" maxlength="2"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Descripci&oacute;n:</label></td>
                <td><input class="campo" type="text" name="descripcion" value="<?php echo htmlentities($row_programas['descripcion'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" maxlength="150"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Unidad Responsable</label></td>
                <td><input class="campo" type="text" name="ur" value="<?php echo htmlentities($row_programas['clave_area'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32" maxlength="150"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><input class="boton" type="button" name="regresar" id="regresar" value="REGRESAR" onClick="location.replace('cat_programas.php');"></td>
                <td><input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();"></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="idprograma" value="<?php echo $row_programas['idprograma']; ?>">
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Programas</div>    
    </div>
</div>
</body>
</html>
<?php
mysqli_free_result($programas);
?>
