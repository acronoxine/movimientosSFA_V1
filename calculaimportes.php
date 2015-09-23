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
//mysql_select_db($database_conexion, $conexion);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<!--<link type="image/x-icon" href="imagenes/ena.ico" rel="icon" />
<link type="image/x-icon" href="imagenes/ena.ico" rel="shortcut icon" />-->
<title>Sistema de N&oacute;mina de Empleados</title>

<link rel="stylesheet" type="text/css" href="css/estilos.css">
<link rel="stylesheet" type="text/css" href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<link rel="stylesheet" type="text/css" href="controles_jquery/js/jquery.fancybox.css">
<script src="controles_jquery/js/jquery-1.9.1.js"></script>
<script src="controles_jquery/js/jquery-ui-1.10.3.custom.js"></script>
<script src="controles_jquery/js/jquery.fancybox.js"></script>

<script type="text/javascript"> 
$(document).ready(function(){
	
	function faltas()
	{
		$.fancybox({
			'href'				: 'faltas.php',
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'width'				: 300,
			'height'			: 140,
			'modal'				: false,
			'type'				: 'iframe'
		});
	}
	
	function pension(datos)
	{
		$.fancybox({
			'href'				: 'pensionali.php'+datos,
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'width'				: 330,
			'height'			: 228,
			'modal'				: false,
			'type'				: 'iframe'
		});
	}
	function diasPrima()
	{
		$.fancybox({
			'href'				: 'diasPrima.php',
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'width'				: 300,
			'height'			: 140,
			'modal'				: false,
			'type'				: 'iframe'
		});
	}
	
	$("#deducciones").change(function() {
		if(form1.deducciones.value == "258")
		{
			faltas();
		}
		
		if(form1.deducciones.value == "252")
		{
			pension("?idnominaemp="+form1.idnominaemp.value);
		}
	});
	$("#percepciones").change(function() {
		if(form1.percepciones.value == "119")
		{
			diasPrima();
		}
		
	});
	
});
</script>


<script>

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


function importeper(idnominaemp, concepto)
{
	Resultado = document.getElementById('ajax_importeper');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_importeper.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idnominaemp="+idnominaemp+"&concepto="+concepto);
}

function importeded(idnominaemp, concepto)
{
	form1.pen_paterno.value = "";
	form1.pen_materno.value = "";
	form1.pen_nombres.value = "";
	form1.pen_porcentaje.value = "";
	form1.pen_importe.value = "";
	
	Resultado = document.getElementById('ajax_importeded');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_importeded.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idnominaemp="+idnominaemp+"&concepto="+concepto);
}

function importeded_dias(dias)
{
	Resultado = document.getElementById('ajax_importeded');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_importeded.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idnominaemp="+document.getElementById('idnominaemp').value+"&concepto="+form1.deducciones.value+"&dias="+dias);
}

function importeper_dias(dias)
{
	Resultado = document.getElementById('ajax_importeper');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_importeper.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idnominaemp="+document.getElementById('idnominaemp').value+"&concepto="+form1.percepciones.value+"&dias="+dias);
}


function importeded_pension(paterno, materno, nombres, idnominaemp, porcentaje, importe)
{
	Resultado = document.getElementById('ajax_importeded');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_importeded.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("importepension="+importe+"&concepto="+form1.deducciones.value);
	
	form1.pen_paterno.value = paterno;
	form1.pen_materno.value = materno;
	form1.pen_nombres.value = nombres;
	form1.pen_porcentaje.value = porcentaje;
	form1.pen_importe.value = importe;
}

function agrega(tipo)
{
	if(tipo == 'P')
	{
		form1.target = "listap";
		form1.action = "calculaimportes_lista_p.php";
		form1.submit();
	}
	
	if(tipo == 'D')
	{
		form1.target = "listad";
		form1.action = "calculaimportes_lista_d.php";
		form1.submit();
	}
	
	form1.target = "_top";
	form1.action = "";
}

function sololetras(form, e)
{
    if(e.keyCode != 0)
      letra = e.keyCode;
    else
      letra = e.which;
    
    if(letra >= 48 && letra <= 57)
       return false;
    else
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
<body topmargin="0" leftmargin="0" bottommargin="0">
<form method="post" name="form1" action="">
<table align="center" cellpadding="2" cellspacing="2" border="0">
<tr>
    <td colspan="2">
        <iframe name="lista" id="lista" src="calculaimportes_datos.php?idnominaemp=<? echo $_GET["idnominaemp"]; ?>" style="width:936px; height:206px;" frameborder="0"></iframe>
    </td>
</tr>
<tr valign="baseline">
  <td align="right" valign="top">
  <table border="0">
  <tr>
  	<td>
  	<select name="percepciones" id="percepciones" class="lista" style="width:342px;" onChange="importeper(<? echo $_GET["idnominaemp"]; ?>, this.value);">
    <option value="">Seleccione</option>
    <?
    	$sql = "Select clave, descripcion, afectacion from cat_conceptos where tipo = 'P' and ver = 'SI' order by descripcion";
		$res = mysqli_query( $conexion, $sql );
		
		while($ren = mysqli_fetch_array($res))
		{
			if($ren["afectacion"] == "G")
				echo "\n<option value='$ren[clave]' style='background-color:#C4D0E4;'>$ren[clave] $ren[descripcion]";
			else
				echo "\n<option value='$ren[clave]'>$ren[clave] $ren[descripcion]";
		}
		
		mysqli_free_result($res);
	?>
    </select>
    </td>
    <td>
  	<input class="boton2" type="button" name="agregarp" id="agregarp" value="AGR. PERCEPCI�N" onClick="agrega('P')" style="width:113px;">
    </td>
  </tr>
  <tr>
    <td colspan="2">
    <div id="ajax_importeper"></div>
    <script>
		importeper(-1, '');
    </script>
    </td>
  </tr>
  </table>
  </td>
  <td valign="top">
  <table border="0">
  <tr>
  	<td>
  	<select name="deducciones" id="deducciones" class="lista" style="width:342px;" onChange="importeded(<? echo $_GET["idnominaemp"]; ?>, this.value);">
    <option value="">Seleccione</option>
    <?
    	$sql = "Select clave, descripcion, afectacion from cat_conceptos where tipo = 'D' and ver = 'SI' order by descripcion";
		$res = mysqli_query( $conexion, $sql );
		
		while($ren = mysqli_fetch_array($res))
		{
			if($ren["afectacion"] == "G")
				echo "\n<option value='$ren[clave]' style='background-color:#C4D0E4;'>$ren[clave] $ren[descripcion]";
			else
				echo "\n<option value='$ren[clave]'>$ren[clave] $ren[descripcion]";
		}
		
		mysqli_free_result($res);
	?>
    </select>
    </td>
    <td>
  	<input class="boton2" type="button" name="agregard" id="agregard" value="AGR. DEDUCCI�N" onClick="agrega('D');" style="width:113px;">
	</td>
  </tr>
  <tr>
    <td colspan="2">
    <div id="ajax_importeded"></div>
    <script>
		importeded(-1, '');
    </script>
    </td>
  </tr>
  </table>
  </td>
</tr>
<tr>
    <td valign="top" align="right">
        <iframe name="listap" id="listap" src="calculaimportes_lista_p.php?idnominaemp=<? echo $_GET["idnominaemp"]; ?>" style="width:460px; height:200px;"></iframe>
    </td>
    <td valign="top">
    	<iframe name="listad" id="listad" src="calculaimportes_lista_d.php?idnominaemp=<? echo $_GET["idnominaemp"]; ?>" style="width:460px; height:200px;"></iframe>
    </td>
</tr>
<tr>
	<td valign="top" align="right">
    	<label class="label">Sueldo Bruto:</label>
    	<input class="campo" type="text" name="sueldobruto" id="sueldobruto" value="0" style="text-align:right; font-weight:bold;">
    </td>
    <td valign="top" align="right">
    	<label class="label">Total de deducciones:</label>
    	<input class="campo" type="text" name="totaldeducciones" id="totaldeducciones" value="0" style="text-align:right; font-weight:bold;">
    </td>
</tr>
<tr>
	<td colspan="2" align="center" valign="top">
    <hr>
	</td>
</tr>
<tr>
	<td colspan="2" align="center" valign="top">
    	<input class="campo" type="text" name="sueldoneto" id="sueldoneto" value="0" style="text-align:center; font-weight:bold;"><br>
        <label class="label">Sueldo Neto:</label>
    </td>
</tr>
</table>

  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="idnominaemp" id="idnominaemp" value="<?php echo $_GET['idnominaemp']; ?>">
  <input type="hidden" name="boton" id="boton" value="">
  
  <input type="hidden" name="pen_paterno" id="paterno" value="">
  <input type="hidden" name="pen_materno" id="materno" value="">
  <input type="hidden" name="pen_nombres" id="nombres" value="">
  <input type="hidden" name="pen_porcentaje" id="pen_porcentaje" value="">
  <input type="hidden" name="pen_importe" id="pen_importe" value="">
</form>
<p>&nbsp;</p>
</body>
</html>

