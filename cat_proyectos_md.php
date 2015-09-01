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
$query_programa = "SELECT * FROM cat_subprograma";
$programa = mysql_query($query_programa, $conexion);
$row_programa = mysql_fetch_assoc($programa);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE cat_proyecto SET clave=%s, descripcion=%s, idsubprograma=%s WHERE idproyecto=%s",
                       GetSQLValueString($_POST['clave'], "text"),
                       GetSQLValueString(strtoupper($_POST['descripcion']), "text"),
					   GetSQLValueString($_POST['sprograma'], "int"),
                       GetSQLValueString($_POST['idproyecto'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

  $updateGoTo = "cat_proyectos.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_proyectos = "-1";
if (isset($_GET['idproyecto'])) {
  $colname_proyectos = $_GET['idproyecto'];
}
mysql_select_db($database_conexion, $conexion);
$query_proyectos = sprintf("SELECT py.idproyecto, py.clave, py.descripcion as py_desc, ps.descripcion as ps_desc,ps.idsubprograma as ps_idsubprograma
							FROM cat_proyecto py
							INNER JOIN cat_subprograma ps on py.idsubprograma=ps.idsubprograma
						    WHERE py.idproyecto = %s", GetSQLValueString($colname_proyectos, "int"));
						
$proyectos = mysql_query($query_proyectos, $conexion) or die(mysql_error());
$row_proyectos = mysql_fetch_assoc($proyectos);
$totalRows_proyectos = mysql_num_rows($proyectos);
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
	if(form.clave.value == "")
	{
		alert("Indique una clave");
		form.clave.focus();
		return false;
	}
	
	if(form.descripcion.value == "")
	{
		alert("Indique una descripción");
		form.descripcion.focus();
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
   		<div id="titulosup">Proyectos</div>    
    </div>
    <div id="cuerpo">
    	<div id="panelizq">
			<? include("menu.php"); ?>
      </div>
        <div id="centro_prin">
          <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Clave:</label></td>
                <td><input class="campo" type="text" name="clave" value="<?php echo htmlentities($row_proyectos['clave'], ENT_COMPAT, 'iso-8859-1'); ?>" size="2" maxlength="2"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Descripción:</label></td>
                <td><input class="campo" type="text" name="descripcion" value="<?php echo htmlentities($row_proyectos['py_desc'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32"></td>
              </tr>
              <tr valign="baseline">
              	<td nowrap align="right"><label class="label">Subprograma:</label></td>
                <td><select name="sprograma" class="lista" style="width:180px;">
                	<option value="<? echo $row_proyectos['ps_idsubprograma']?>"><? echo $row_proyectos['ps_desc']?></option>
                  <?php 
do {  
?>
                  <option value="<?php echo $row_programa['idsubprograma']?>" ><?php echo $row_programa['descripcion']?></option>
                  <?php
} while ($row_programa = mysql_fetch_assoc($programa));
?>
                </select></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><input class="boton" type="button" name="regresar" id="regresar" value="REGRESAR" onClick="location.replace('cat_proyectos.php');"></td>
                <td><input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();"></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="idproyecto" value="<?php echo $row_proyectos['idproyecto']; ?>">
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Proyectos</div>    
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($proyectos);
?>
