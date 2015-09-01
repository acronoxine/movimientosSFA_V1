<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/estilos.css">

<script>

function valida(form)
{
	if(form.paterno.value == "")
	{
		alert("Indique el apellido paterno");
		form.paterno.focus();
		return false;
	}
	
	if(form.materno.value == "")
	{
		alert("Indique el apellido materno");
		form.materno.focus();
		return false;
	}
	
	if(form.nombres.value == "")
	{
		alert("Indique el nombre");
		form.nombres.focus();
		return false;
	}
	
	if(form.porcentaje.value == "")
	{
		alert("Indique el porcentaje de la pensión");
		form.porcentaje.focus();
		return false;
	}
	
	return true;
}

function objetoAjax() 
{
  var xmlHttp=null;
  if (window.ActiveXObject) 
    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
  else 
    if (window.XMLHttpRequest) 
      xmlHttp = new XMLHttpRequest();
  return xmlHttp;
}

function carga(Resultado,ajax)
{
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) 
		{
			Resultado.innerHTML = ajax.responseText.tratarResponseText();
		}
	}
}


String.prototype.tratarResponseText=function(){
	var pat=/<script[^>]*>([\S\s]*?)<\/script[^>]*>/ig;
	var pat2=/\bsrc=[^>\s]+\b/g;
	var elementos = this.match(pat) || [];
	for(i=0;i<elementos.length;i++) {
		var nuevoScript = document.createElement('script');
		nuevoScript.type = 'text/javascript';
		var tienesrc=elementos[i].match(pat2) || [];
		if(tienesrc.length){
			nuevoScript.src=tienesrc[0].split("'").join('').split('"').join('').split('src=').join('').split(' ').join('');
		}else{
			var elemento = elementos[i].replace(pat,'$1','');
			nuevoScript.text = elemento;
		}
		document.getElementsByTagName('body')[0].appendChild(nuevoScript);
	}
	return this.replace(pat,'');
}


function por2imp(porcentaje)
{
	Resultado = document.getElementById('ajax_imp2por');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_imp2por.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("porcentaje="+porcentaje+"&idnominaemp="+form1.idnominaemp.value);
}

function imp2por(importe)
{
	Resultado = document.getElementById('ajax_por2imp');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_por2imp.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("importe="+importe+"&idnominaemp="+form1.idnominaemp.value);
}


function continuar(form)
{
	window.parent.importeded_pension(form.paterno.value, form.materno.value, form.nombres.value, form.idnominaemp.value, form.porcentaje.value, form.importe.value);
	parent.$.fancybox.close();
}

</script>

</head>
<body>

<form method="post" name="form1" action="cat_beneficiarios_lista.php" target="lista">
<table align="center">
  <tr valign="baseline">
    <td nowrap align="right"><label class="label">Paterno:</label></td>
    <td><input class="campo" type="text" name="paterno" value="" size="25" maxlength="30"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><label class="label">Materno:</label></td>
    <td><input class="campo" type="text" name="materno" value="" size="25" maxlength="30"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><label class="label">Nombres:</label></td>
    <td><input class="campo" type="text" name="nombres" value="" size="25" maxlength="30"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><label class="label">Porcentaje:</label></td>
    <td><div id="ajax_por2imp"></div></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><label class="label">Importe:</label></td>
    <td><div id="ajax_imp2por"></div></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right">&nbsp;</td>
    <td>
	<input class="boton" type="button" name="guarda" value="CONTINUAR" onClick="if(valida(this.form)) continuar(this.form);">
    <input class="boton" type="button" name="cancela" value="CANCELAR" onClick="parent.$.fancybox.close();">
    </td>
  </tr>
</table>
<input type="hidden" name="MM_insert" value="form1">
<input type="hidden" name="idnominaemp" value="<? echo $_GET["idnominaemp"]; ?>">
</form>
</body>
</html>
<script>
imp2por('0');
por2imp('0');
</script>