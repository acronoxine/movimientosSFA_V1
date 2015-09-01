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


function buscaemp(consulta)
{
	Resultado = document.getElementById('ajax_buscaemp');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_buscaemp.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("consulta="+consulta);
	document.getElementById("ajax_buscaemp").style.display = "inline";
}

function seleccionaemp(idnominaemp, empleado)
{
	form1.idnominaemp.value = idnominaemp;
	form1.empleado.value = empleado;
	document.getElementById("ajax_buscaemp").style.display = "none";
	imp2por('0');
	por2imp('0');
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

</script>

</head>
<body topmargin="0" leftmargin="0">
<div id="todo">
	<div id="cabeza_prin">
    </div>
    <div id="tituloarriba">
   		<div id="titulosup">Pensionados</div>    
    </div>
    <div id="cuerpo">
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
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
                <td nowrap align="right"><label class="label">Empleado:</label></td>
                <td>
                	<input class="campo" type="text" name="empleado" value="" size="45" onKeyup="buscaemp(this.value);"><br>
                    <div id="ajax_buscaemp"></div>
                </td>
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
                	<input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();">
                </td>
              </tr>
              <tr>
              	<td colspan="2">
                	<iframe name="lista" id="lista" src="cat_beneficiarios_lista.php" style="width:800px; height:200px;"></iframe>
                </td>
              </tr>
            </table>
            <input type="hidden" name="MM_insert" value="form1">
            <input type="hidden" name="idnominaemp" value="">
            <input type="hidden" name="_idbeneficiarios" id="_idbeneficiarios" value="">
          </form>
          <p>&nbsp;</p>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Pensionados</div>    
    </div>
</div>
</body>
</html>