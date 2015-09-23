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
<link rel="shortcut icon" type="image/x-icon" href="http://www.michoacan.gob.mx/wp-content/themes/mich2015/img/favicon.ico">

<title>Sistema de N&oacute;mina de Empleados</title>

<link rel="stylesheet" type="text/css" href="css/estilos.css">

<script>

function valida(form)
{
	if(form.nivel.value == "")
	{
		alert("Indique el nivel de la categor&iacute;a");
		form.nivel.focus();
		return false;
	}
	
	if(form.clave.value == "")
	{
		alert("Indique la clave de la categor&iacute;a");
		form.clave.focus();
		return false;
	}
	
	if(form.descripcion.value == "")
	{
		alert("Indique el nombre de la categor&iacute;a");
		form.decripcion.focus();
		return false;
	}
	

	if(form.sueldobase.value == "" || form.sueldobase.value == "0")
	{
		alert("Indique el sueldo base de la categor&iacute;a");
		form.sueldobase.focus();
		return false;
	}
	
	return true;
}

function busca(dato)
{
	parent.lista.document.location.replace('cat_categorias_lista.php?consulta='+form1.consulta.value);
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
   		<div id="titulosup">Categor&iacute;as</div>    
    </div>
    <div id="cuerpo">
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
          <form method="post" name="form1" action="cat_categorias_lista.php" target="lista">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Nivel:</label></td>
                <td><input class="campo" type="text" name="nivel" value="" size="25" maxlength="12" onKeyPress="return solonumeros(this.form, event)"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Clave:</label></td>
                <td><input class="campo" type="text" name="clave" value="" size="5" maxlength="5" onKeyPress="return solonumeros(this.form, event)"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Descripci&oacute;n:</label></td>
                <td><input class="campo" type="text" name="descripcion" value="" size="40" maxlength="150"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Sueldo base:</label></td>
                <td><input class="campo" type="text" name="sueldobase" value="0" size="25" maxlength="12" onKeyPress="return solonumeros(this.form, event);" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">&nbsp;</td>
                <td>
                	<input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();">
                    <label class="label">Consulta por categoria:</label>
                    <input class="campo" type="text" name="consulta" id="consulta" value="" onKeyup="busca(this.value);">
                </td>
              </tr>
              <tr>
              	<td colspan="2">
                	<iframe name="lista" id="lista" src="cat_categorias_lista.php" style="width:700px; height:500px;"></iframe>
                </td>
              </tr>
            </table>
            <input type="hidden" name="MM_insert" value="form1">
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Categor&iacute;as</div>    
    </div>
</div>
</body>
</html>