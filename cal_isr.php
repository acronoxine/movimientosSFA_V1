<?
session_start();
if($_SESSION["m_sesion"] != 1)
{
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit();
}
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);
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
function solonumeros(objeto, e)
{
    if(e.keyCode != 0)
      letra = e.keyCode;
    else
      letra = e.which;
	  
	if(letra == 13)
		objeto.blur();
    
    if((letra < 48 || letra > 57) && letra != 37 && letra != 38 && letra != 39 && letra != 40 && letra != 8 && letra != 46)
       return false;
    else
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


function guardaEdicion(campo, id, valor)
{
	Resultado = document.getElementById('ajax_guardaISR');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_guardaISR.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("campo="+campo+"&id="+id+"&valor="+valor);
}


function editar(objeto)
{
	objeto.style.border = "1px solid";
	objeto.style.outline = "1px";
	objeto.select();
	objeto.readOnly = false;
}

function guardarEdicion(objeto, valor)
{
	objeto.style.border = "0";
	objeto.style.outline = "0";
	objeto.readOnly = true;
	
	campo = objeto.name.substr(0, 1);
	id = objeto.name.substr(1);
	
	guardaEdicion(campo, id, valor);
}

</script>

</head>
<body topmargin="0" leftmargin="0">
<div id="todo">
	<div id="cabeza_prin">
    </div>
    <div id="cuerpo">
    <div id="tituloarriba">
   		<div id="titulosup">Tabla ISR</div>    
    </div>
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
        	<form>
            <table class="tablagrid" width="550" border="0" cellspacing="0" cellpadding="3" align="center">
              <tr class="tablahead">
                <td>LIMITE INFERIOR</td>
                <td>LIMITE SUPERIOR</td>
                <td>CUOTA FIJA</td>
                <td>PORCIENTO</td>
              </tr>
              <?
                    $sql = "select * from isr";
                    
                    $res = mysql_query($sql, $conexion);
            
                    while($ren = mysql_fetch_array($res))
                    {
                        echo "\n<tr class='tablaregistros'>";
                        echo "\n<td align=right><input class='campoblanco' readOnly onClick='editar(this);' onBlur='guardarEdicion(this, this.value);' onkeypress='return solonumeros(this, event);' type='text' name='a$ren[id]' id='a$ren[id]' value='", number_format($ren["limiteinferior"], 2, ".", ""), "'></td>";
                        echo "\n<td align=right><input class='campoblanco' readOnly onClick='editar(this);' onBlur='guardarEdicion(this, this.value);' onkeypress='return solonumeros(this, event);' type='text' name='b$ren[id]' id='b$ren[id]' value='", number_format($ren["limitesuperior"], 2, ".", ""), "'></td>";
                        echo "\n<td align=right><input class='campoblanco' readOnly onClick='editar(this);' onBlur='guardarEdicion(this, this.value);' onkeypress='return solonumeros(this, event);' type='text' name='c$ren[id]' id='c$ren[id]' value='", number_format($ren["cuotafija"], 2, ".", ""), "'></td>";
                        echo "\n<td align=right><input class='campoblanco' readOnly onClick='editar(this);' onBlur='guardarEdicion(this, this.value);' onkeypress='return solonumeros(this, event);' type='text' name='d$ren[id]' id='d$ren[id]' value='", number_format($ren["porciento"], 2, ".", ""), "'></td>";
                        echo "\n</tr>";
                    }
              ?>
            </table>
            </form>
            <div id="ajax_guardaEdicion"></div>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Tabla ISR</div>    
    </div>
</div>
</body>
</html>