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

<script src="js_popup/jquery.js"></script>
<script src="js_popup/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="js_popup/fancybox/jquery.fancybox-1.3.4.css">


<script type="text/javascript"> 
$(document).ready(function(){
	
	function formato(datos)
	{
		$.fancybox({
			'href'				: 'pdfliquidaciones.php' + datos,
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'width'				: 970,
			'height'			: 700,
			'modal'				: false,
			'type'				: 'iframe'
		});
	}
	
	$("#procesar").click(function() {
		if(document.getElementById('idnominaemp').value != "")
		{
			formato("?idnominaemp=" + document.getElementById('idnominaemp').value);
		}else{
			alert("Consulte un empleado antes de continuar.");
		}		
	});
});
</script>

<script>

function valida(form)
{
	if(form.rfc_iniciales.value == "")
	{
		alert("Indique las iniciales del rfc");
		form.rfc_iniciales.focus();
		return false;
	}
	
	if(form.rfc_fechanac.value == "")
	{
		alert("Indique la fecha de nacimiento en el rfc");
		form.rfc_fechanac.focus();
		return false;
	}
	

	return true;
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
     <script type="text/javascript">
            var peticion = null;
            var elementoSeleccionado = -1;
            var sugerencias = null;
            var cacheSugerencias = {};

            function inicializa_xhr() {
                if (window.XMLHttpRequest) {
                    return new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    return new ActiveXObject("Microsoft.XMLHTTP");
                }
            }

            Array.prototype.formateaLista = function() {
                var codigoHtml = "";

                codigoHtml = "<ul>";
                for (var i = 0; i < this.length; i++) {
                    if (i == elementoSeleccionado) {
                        codigoHtml += "<li class=\"seleccionado\">" + this[i] + "</li>";
                    }
                    else {
                        codigoHtml += "<li>" + this[i] + "</li>";
                    }
                }
                codigoHtml += "</ul>";

                return codigoHtml;
            };

            function autocompleta() {
                var elEvento = arguments[0] || window.event;
                var tecla = elEvento.keyCode;

                if (tecla == 40) { // Flecha Abajo
                    if (elementoSeleccionado + 1 < sugerencias.length) {
                        elementoSeleccionado++;
                    }
                    muestraSugerencias();
                }
                else if (tecla == 38) { // Flecha Arriba
                    if (elementoSeleccionado > 0) {
                        elementoSeleccionado--;
                    }
                    muestraSugerencias();
                }
                else if (tecla == 13) { // ENTER o Intro
                    seleccionaElemento();
                }
                else {
                    var texto = document.getElementById("nombreUS").value;

                    // Si es la tecla de borrado y el texto es vacío, ocultar la lista
                    if (tecla == 8 && texto == "") {
                        borraLista();
                        return;
                    }

                    if (cacheSugerencias[texto] == null) {
                        peticion = inicializa_xhr();

                        peticion.onreadystatechange = function() {
                            if (peticion.readyState == 4) {
                                if (peticion.status == 200) {
                                    sugerencias = eval('(' + peticion.responseText + ')');
                                    if (sugerencias.length == 0) {
                                        sinResultados();
                                    }
                                    else {
                                        cacheSugerencias[texto] = sugerencias;
                                        actualizaSugerencias();
                                    }
                                }
                            }
                        };

                        peticion.open('POST', 'nombresAutocompletar.php?nocache=' + Math.random(), true);
                        peticion.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        peticion.send('nombreUS=' + encodeURIComponent(texto));
                    }
                    else {
                        sugerencias = cacheSugerencias[texto];
                        actualizaSugerencias();
                    }
                }
             }

            function sinResultados() {
                document.getElementById("sugerencias").innerHTML = "No existen nombres que empiecen con esas letras";
                document.getElementById("sugerencias").style.display = "block";
            }

            function actualizaSugerencias() {
                elementoSeleccionado = -1;
                muestraSugerencias();
            }

            function seleccionaElemento() {
                if (sugerencias[elementoSeleccionado]) {
                    document.getElementById("nombreUS").value = sugerencias[elementoSeleccionado];
                    borraLista();
                }
            }

            function muestraSugerencias() {
                var zonaSugerencias = document.getElementById("sugerencias");

                zonaSugerencias.innerHTML = sugerencias.formateaLista();
                zonaSugerencias.style.display = 'block';
            }

            function borraLista() {
                document.getElementById("sugerencias").innerHTML = "";
                document.getElementById("sugerencias").style.display = "none";
            }

            window.onload = function() {
                // Crear elemento de tipo <div> para mostrar las sugerencias del servidor
                var elDiv = document.createElement("div");
                elDiv.id = "sugerencias";
                //document.body.appendChild(elDiv);
                otro.appendChild(elDiv);

                document.getElementById("nombreUS").onkeyup = autocompleta;
                document.getElementById("nombreUS").focus();
            }

        </script>

        <style type="text/css">
            body {font-family: Arial, Helvetica, sans-serif;}
            #sugerencias {width:250px; border:1px solid black; display:none; margin-left: 83px;}
            #sugerencias ul {list-style: none; margin: 0; padding: 0; font-size:.85em;}
            #sugerencias ul li {padding: .2em; border-bottom: 1px solid silver;}
            .seleccionado {font-weight:bold; background-color: #FFFF00;}
        </style>
</head>
<body topmargin="0" leftmargin="0">
<div id="todo">
	<div id="cabeza_prin">
    </div>
    <div id="tituloarriba">
   		<div id="titulosup">Liquidaciones</div>    
    </div>
    <div id="cuerpo">
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
        <form name="mismovimientos" method="post" action="liquidaciones_datos.php" target="lista">
        <table width="900" border="0" cellpadding="2" cellspacing="1" align="center">
          <tr>
            <td valign="bottom">
            	<div id="otro" style="margin-left:20px">
                        <br>
                        <form name="mismovimientos" method="post" action="movimientos_datos.php" target="lista">
                        	<input type="hidden" name="pornombre" value="si"/>
                        	<label class="label">APELLIDOS:</label><BR>
                            <input type="text" id="nombreUS" name="nombreUS" size="30" autocomplete="off" onclick="this.value=''" />
                            <input type="text" id="oculto" name="oculto" style="display:none;" />
                        	<input class="boton2" type="button" name="buscar" id="buscar" value="BUSCAR" onClick="submit();">
                        
                    </div>
            </td>
          </tr>
          <tr>
            <td>
                <iframe name="lista" id="lista" src="" style="width:950px; height:400px;"></iframe>
            </td>
          </tr>
          <tr>
          	<td>
            	<input class="boton2" type="button" name="procesar" id="procesar" value="PROCESAR" style="width:140px;">
            </td>
          </tr>
        </table>
        <input type="hidden" name="idnominaemp" id="idnominaemp" value="">
        </form>

        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Liquidaciones</div>    
    </div>
</div>
</body>
</html>