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
	if(form.para.value == 0 || form.para.value == "")
	{
		alert("Indique el límite PARA INGRESOS!");
		form.para.focus();
		return false;
	}
	
	if(form.hasta.value == 0 || form.hasta.value == "")
	{
		alert("Indique el límite HASTA INGRESOS!");
		form.hasta.focus();
		return false;
	}
	
	if(form.subsidio.value == 0 || form.subsidio.value == "")
	{
		alert("Indique el subsisdio!");
		form.subsidio.focus();
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
   		<div id="titulosup">Subsidio Para el Empleo</div>    
    </div>
    <div id="cuerpo">
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
          <form method="post" name="form1" action="cat_isrsub_lista.php" target="lista">
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Para ingresos:</label></td>
                <td><input class="campo" type="text" name="para" value="0" size="15" maxlength="15" style="text-align:right;" onKeyPress="return solonumeros(this.form, event);"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Hasta ingresos:</label></td>
                <td><input class="campo" type="text" name="hasta" value="0" size="15" maxlength="15" style="text-align:right;" onKeyPress="return solonumeros(this.form, event);"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right"><label class="label">Subsidio:</label></td>
                <td><input class="campo" type="text" name="subsidio" value="0" size="15" maxlength="15" style="text-align:right;" onKeyPress="return solonumeros(this.form, event);"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="right">&nbsp;</td>
                <td><input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();"></td>
              </tr>
              <tr>
              	<td colspan="2">
                	<iframe name="lista" id="lista" src="cat_isrsub_lista.php" style="width:900px; height:250px;"></iframe>
                </td>
              </tr>
            </table>
            <input type="hidden" name="MM_insert" value="form1">
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Subsidio Para el Empleo</div>    
    </div>
</div>
</body>
</html>