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
//mysql_select_db($database_conexion, $conexion);
$query_area = "SELECT * FROM cat_area";
$res_area=mysqli_query($conexion,$query_area);
$row_area=mysqli_fetch_array($res_area);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link rel="shortcut icon" type="image/x-icon" href="http://www.michoacan.gob.mx/wp-content/themes/mich2015/img/favicon.ico">

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

function busca(dato)
{
	parent.lista.document.location.replace('cat_programas_lista.php?consulta='+form1.consulta.value);
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
          <form method="post" name="form1" action="cat_programas_lista.php" target="lista">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Clave:</label></td>
                <td><input class="campo" type="text" name="clave" value="" size="4" maxlength="4"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Descripci�n:</label></td>
                <td><input class="campo" type="text" name="descripcion" value="" size="32" maxlength="150"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Unidad Responsable:</label></td>
                <td>
					<select name="ur" class="lista" style="width:180px;">
                	<option value="">Seleccione</option>
                  <?php 
do {  
?>
                  <option value="<?php echo $row_area['idarea']?>" ><?php echo $row_area['descripcion']?></option>
                  <?php
} while ($row_area = mysqli_fetch_array($res_area));
?>
                </select>
                </td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">&nbsp;</td>
                <td>
                	<input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();">
                    <label class="label">Consulta por programa:</label>
                    <input class="campo" type="text" name="consulta" id="consulta" value="" onKeyup="busca(this.value);">
                </td>
              </tr>
              <tr>
              	<td colspan="2">
                	<iframe name="lista" id="lista" src="cat_programas_lista.php" style="width:700px; height:400px;"></iframe>
                </td>
              </tr>
            </table>
            <input type="hidden" name="MM_insert" value="form1">
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