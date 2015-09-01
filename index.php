<?
session_start();
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link rel="shortcut icon" type="image/x-icon" href="http://www.michoacan.gob.mx/wp-content/themes/mich2015/img/favicon.ico">

<title>Sistema de Movimientos de Empleados</title>

<link rel="stylesheet" type="text/css" href="css/estilos.css">

<SCRIPT>
function trim(texto)
{ 
  texto = new String(texto);

  while('' + texto.charAt(0) == ' ')
    texto = texto.substring(1, texto.length);

  while('' + texto.charAt(texto.length - 1) == ' ')
    texto = texto.substring(0, texto.length - 1);

  return texto;
}

function valida(form)
{
	if(trim(form.usuario.value) == "")
	{
		alert("Indique el nombre de usuario");
		form.usuario.focus();
		return false;
	}
	
	if(trim(form.password.value) == "")
	{
		alert("Indique su contrasena");
		form.password.focus();
		return false;
	}
	
	return true;
}
</SCRIPT>

</HEAD>
<BODY topmargin="0" leftmargin="0" onLoad = "accesos.usuario.focus();">
<div id="todo">
	<div id="cabeza">
    </div>
    <div id="cuerpo">
    	<div id="panelizq">

        </div>
        <div id="centro" style="border:0;">
        	<div id="sesion">
        	<form name="acceso" id="acceso" method="post" action="sesion.php">
			<table id="datos" border = "0" cellpadding="1" cellspacing="1">
            <tr><td colspan="2">
            	<label class="label" style="font-size:18px;">Iniciar Sesion</label>
            </td></tr>
            <tr><td align="right">
            	<label class="label">Nip:</label>
            </td><td>
            	<input class="campo" type="text" name="usuario" id="nip" value="">
            </td></tr>
            <tr><td align="right">
            	<label class="label">Constraseña:</label>
            </td><td>
            	<input class="campo" type="password" name="password" id="clave" value="">
            </td></tr>
            <tr><td colspan="2" align="right">
            	<input class="boton2" type="submit" name="entra" id="entra" value="Entrar">
            </td></tr>
            </table>
            </form>
            </div>
        </div>
        <div id="panelder">
        </div>
    </div>
    <div id="pie">
    </div>
</div>
</body>
</html>