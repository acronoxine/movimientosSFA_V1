<?
session_start();
if($_SESSION["m_sesion"] != 1)
{
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit();
}
require_once('Connections/conexion.php');
mysql_select_db($database_conexion, $conexion);
$query_programa = "SELECT * FROM cat_subprograma";
$programa = mysql_query($query_programa, $conexion);
$row_programa = mysql_fetch_assoc($programa);
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
          <form method="post" name="form1" action="cat_proyectos_lista.php" target="lista">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Clave:</label></td>
                <td><input class="campo" type="text" name="clave" value="" size="4" maxlength="4"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Descripción:</label></td>
                <td><input class="campo" type="text" name="descripcion" value="" size="32" maxlength="150"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Subprograma:</label></td>
                <td><select name="sprograma" class="lista" style="width:180px;">
                	<option value="">Seleccione</option>
                  <?php 
do {  
?>
                  <option value="<?php echo $row_programa['idsubprograma']?>" ><?php echo $row_programa['descripcion']?></option>
                  <?php
} while ($row_programa = mysql_fetch_assoc($programa));
?>
                </select></td>
              <tr>
              <tr valign="baseline">
                <td nowrap align="right">&nbsp;</td>
                <td><input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();"></td>
              </tr>
              <tr>
              	<td colspan="2">
                	<iframe name="lista" id="lista" src="cat_proyectos_lista.php" style="width:700px; height:200px;"></iframe>
                </td>
              </tr>
            </table>
            <input type="hidden" name="MM_insert" value="form1">
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