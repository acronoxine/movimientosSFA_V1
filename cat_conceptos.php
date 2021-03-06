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
<link rel="shortcut icon" type="image/x-icon" href="http://www.michoacan.gob.mx/wp-content/themes/estaenti/img/favicon.ico">

<title>Sistema de N&oacute;mina de Empleados</title>

<link rel="stylesheet" type="text/css" href="css/estilos.css">

<script>

function valida(form)
{
	if(form.clave.value == "")
	{
		alert("Indique la clave del concepto");
		form.clave.focus();
		return false;
	}
	
	if(form.descripcion.value == "")
	{
		alert("Indique la descripci&iacute;n del concepto");
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

function busca(dato)
{
	parent.lista.document.location.replace('cat_conceptos_lista.php?consulta='+form1.consulta.value);
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
   		<div id="titulosup">Conceptos</div>    
    </div>
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
            <form method="post" name="form1" action="cat_conceptos_lista.php" target="lista">
              <table align="center" border="0">
                <tr valign="baseline">
                  <td nowrap align="right"><label class="label">Clave:</label></td>
                  <td colspan="2"><input class="campo" type="text" name="clave" value="" size="7" maxlength="7"></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right"><label class="label">Descripci&oacute;n:</label></td>
                  <td colspan="2"><input class="campo" type="text" name="descripcion" value="" size="32" maxlength="150"></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right"><label class="label">Afectacion:</label></td>
                  <td valign="baseline" colspan="2"><table>
                    <tr>
                      <td><input checked type="radio" name="afectacion" id="general" value="G" onClick="document.getElementById('_afectacion').value=this.value;">
                        <label class="label" for="general">General</label></td>
                    </tr>
                    <tr>
                      <td><input type="radio" name="afectacion" id="individual" value="I" onClick="document.getElementById('_afectacion').value=this.value;">
                        <label class="label" for="individual">Individual</label></td>
                    </tr>
                  </table></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right"><label class="label">Importe:</label></td>
                  <td><input class="campo" type="text" name="importe" id="importe" value="0" size="12" maxlength="12" style="text-align:right;" onKeyPress="return solonumeros(this.form, event)"></td>
                  <td>
                  	<input checked type="radio" name="uso" id="porimporte" value="IMP" onClick="document.getElementById('_activa').value = this.value; activa(this.form);">
                    <label class="label" for="porimporte">Se usuar&aacute; importe</label>
                  </td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right"><label class="label">D&iacute;as:</label></td>
                  <td><input disabled class="campo" type="text" name="dias" id="dias" value="0" size="3" maxlength="3" style="text-align:right;" onKeyPress="return solonumeros(this.form, event)"></td>
                  <td>
                  	<input type="radio" name="uso" id="pordias" value="DIA" onClick="document.getElementById('_activa').value = this.value; activa(this.form);">
                    <label class="label" for="pordias">Se usuar&aacute; d&iacute;as</label>
                  </td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right"><label class="label">Porcentaje:</label></td>
                  <td><input disabled class="campo" type="text" name="porcentaje" id="porcentaje" value="0" size="3" maxlength="3" style="text-align:right;" onKeyPress="return solonumeros(this.form, event)"></td>
                  <td>
                  	<input type="radio" name="uso" id="porporcentaje" value="POR" onClick="document.getElementById('_activa').value = this.value; activa(this.form);">
                    <label class="label" for="porporcentaje">Se usuar&aacute; porcentaje</label>
                  </td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right"><label class="label">Tipo:</label></td>
                  <td colspan="2"><select name="tipo" class="lista">
                    <option value="P" <?php if (!(strcmp("P", ""))) {echo "SELECTED";} ?>>Percepci&oacute;n</option>
                    <option value="D" <?php if (!(strcmp("D", ""))) {echo "SELECTED";} ?>>Deducci&oacute;n</option>
                  </select></td>
                </tr>
                <tr valign="baseline">
                  <td nowrap align="right">&nbsp;</td>
                  <td colspan="2">
                  	<input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();">
                    <label class="label">Consulta por concepto:</label>
                    <input class="campo" type="text" name="consulta" id="consulta" value="" onKeyup="busca(this.value);">
                  </td>
                </tr>
                <tr>
                	<td colspan="3"><iframe name="lista" id="lista" src="cat_conceptos_lista.php" style="width:950px; height:400px;"></iframe></td>
                </tr>
              </table>
              <input type="hidden" name="MM_insert" value="form1">
              <input type="hidden" name="_afectacion" id="_afectacion" value="G">
              <input type="hidden" name="_activa" id="_activa" value="IMP">
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