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
          <form method="post" name="form1" action="cat_isranual_lista.php" target="lista">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Limite inferior:</label></td>
                <td><input class="campo" type="text" name="limiteinferior" value="0" onClick="select();" onKeyPress="return solonumeros(this.form, event);" size="15" maxlength="15" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Limite superior:</label></td>
                <td><input class="campo" type="text" name="limitesuperior" value="0" onClick="select();" onKeyPress="return solonumeros(this.form, event);" size="15" maxlength="15" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Cuota fija:</label></td>
                <td><input class="campo" type="text" name="cuotafija" value="0" onClick="select();" onKeyPress="return solonumeros(this.form, event);" size="15" maxlength="15" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Porcentaje:</label></td>
                <td><input class="campo" type="text" name="porciento" value="0" onClick="select();" onKeyPress="return solonumeros(this.form, event);" size="15" maxlength="15" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Mes:</label></td>
                <td>
                	<select name="mes" id="mes" class="lista">
                    <?
                    	for($i = 1; $i <= 12; $i++)
						{
							if(str_pad($i, 2, "0", STR_PAD_LEFT) == date("m"))
								echo "\n<option selected value = '$i'>", str_pad($i, 2, "0", STR_PAD_LEFT), "</option>";
							else
								echo "\n<option value = '$i'>", str_pad($i, 2, "0", STR_PAD_LEFT), "</option>";
						}
					?>
                    </select>
                </td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Año:</label></td>
                <td><input class="campo" type="text" name="anio" value="<? echo date("Y"); ?>" onClick="select();" onKeyPress="return solonumeros(this.form, event);" size="3" maxlength="4" style="text-align:right;"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">&nbsp;</td>
                <td>
                	<input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();">
                    <label class="label">Buscar mes:</label>
                    <select name="bmes" class="lista" onChange="lista.location.replace('cat_isranual_lista.php?bmes='+this.value+'&banio='+form1.banio.value)">
                    <option value="">Seleccione</option>
                    <?
                    	for($i = 1; $i <= 12; $i++)
						{
							if(str_pad($i, 2, "0", STR_PAD_LEFT) == date("m"))
								echo "\n<option selected value = '$i'>", str_pad($i, 2, "0", STR_PAD_LEFT), "</option>";
							else
								echo "\n<option value = '$i'>", str_pad($i, 2, "0", STR_PAD_LEFT), "</option>";
						}
					?>
                    </select>
                    <label class="label">Año:</label>
                    <input class="campo" type="text" name="banio" value="<? echo date("Y"); ?>" onClick="select();" onKeyPress="return solonumeros(this.form, event);" size="3" maxlength="4" style="text-align:right;">
                </td>
              </tr>
              <tr>
              	<td colspan="2">
                	<iframe name="lista" id="lista" src="cat_isranual_lista.php" style="width:850px; height:250px;"></iframe>
                </td>
              </tr>
            </table>
            <input type="hidden" name="MM_insert" value="form1">
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