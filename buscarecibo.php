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
<?
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
<script>
function valida(form)
{
	if(form.ur.value == "")
	{
		alert("Seleccione una UR");
		form.ur.focus();
		return false;
	}
	
	if(form.quincena.value == "")
	{
		alert("Seleccione el número de quincena");
		form.quincena.focus();
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

function separa(dato)
{
	var quincena = dato.substr(0, dato.indexOf("|", dato));
	dato = dato.substr(dato.indexOf("|", dato) + 1);
	
	var fdesde = dato.substr(0, dato.indexOf("|", dato));
	dato = dato.substr(dato.indexOf("|", dato) + 1);
	
	var fhasta = dato.substr(0);
	
	document.getElementById('fdesde').value = fdesde;
	document.getElementById('fhasta').value = fhasta;
	document.getElementById('_quincena').value = quincena;
}


function emitenomina()
{
	if(document.getElementById('boton').value == "R")
	{
		form1.action = "pdfbuscarecibo.php";
		form1.submit();
	}
	
	form1.action = "";
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
                <div id="titulosup">Movimientos</div>    
            </div>
            <div id="cuerpo">
                <div id="panelizq">
                    <?php include("menu.php"); ?>
                </div>
<div id="centro_prin">
			 
        	<form name="form1" id="form2" method="post" target="nominapdf">
            <table width="700" border="0" align="center" cellpadding="1" cellspacing="5" style="border:1px solid #E6E4E4;">
              <tr>
                <td align="center" colspan="2" bgcolor="#CCCCCC">
                	<label class="label">Consulta de recibo</label>
                </td>
              </tr>
              <tr>
                <td colspan="2">
					<table border="0" align="center">
                    <tr>
                    	<td>
                        	<label class="label">UR:</label>
                            <select name="ur" id="ur" class="lista" style="width:200px;">
                            <option value="">Seleccione</option>
                            <?
                            	$sql = "Select * from cat_area order by descripcion";
								$res = mysql_query($sql, $conexion);
								
								while($ren = mysql_fetch_array($res))
								{
									echo "\n<option value='$ren[idarea]'>$ren[descripcion]</option>";
								}
							?>
                            </select>
                        </td>
                    	<td>
                        	<label class="label">Quincena:</label>
                            <select name="quincena" id="quincena" class="lista" onChange="separa(this.value);">
                            <option value="">Seleccione</option>
                            <?
								include("fechas.php");
								
								$sql = "Select quincena, concat(lpad(day(fdesde), 2, '0'), '/', lpad(month(fdesde), 2, '0'), '/', year(fdesde)) as fdesde, concat(lpad(day(fhasta), 2, '0'), '/', lpad(month(fhasta), 2, '0'), '/', year(fhasta)) as fhasta from cat_quincenas where year(fdesde) = '$anio' order by quincena";
								$res = mysql_query($sql, $conexion);
								
								while($ren = mysql_fetch_array($res))
								{
									echo "\n<option value='$ren[quincena]|$ren[fdesde]|$ren[fhasta]'>$ren[quincena]</option>";
								}
                            
							?>
                            </select>
                        </td>
                        <td>
                        	<label class="label">Año:</label>
                            <input class="campo" type="text" name="anio" id="anio" value="<? echo date("Y"); ?>" size="4" style="text-align:center;">
                        </td>
                    </tr>
                    </table>
                </td>
              </tr>
              <tr>
              	<td align="right">
                	<label class="label">De:</label>
                    <input class="campo" type="text"  name="fdesde" id="fdesde" value="" size="10">
                </td>
              	<td>
                	<label class="label">A:</label>
                    <input class="campo" type="text"  name="fhasta" id="fhasta" value="" size="10">
                </td>
              </tr>
              <tr>
              	<td colspan="2">
                	<table border="0" align="center">
                    <tr>
                        <td nowrap align="right"></td>
                        <td>
                      	<div id="otro" style="margin-left:20px">
                        <br>

                        	<input type="hidden" name="pornombre" value="si"/>
                        	<label class="label">NOMBRE:</label><BR>
                            <input type="text" id="nombreUS" name="nombreUS" size="30" autocomplete="off" onclick="this.value=''" />
                            <input type="text" id="oculto" name="oculto" style="display:none;" />
                        
                    </div>
                        </td>
                    </tr>
                    </table>
                </td>
              </tr>
              <tr>
              	<td colspan="2" align="center">
                  <table border="0" width="100%">
                  <tr>
                    <td>
                        <input class="boton2" type="button" name="limpiar" id="limpiar" value="LIMPIAR" onClick="location.replace('emisionpre.php');">
                    </td>
                    <td align="right">
                        <input class="boton" type="button" name="misrecibos" id="misrecibos" value="RECIBO" onClick="document.getElementById('boton').value = 'R'; if(valida(this.form)) emitenomina();">
                    </td>
                  </tr>
                  </table>
              	</td>
              </tr>
            </table>
            <input type="hidden" name="_quincena" id="_quincena" value="">
            <input type="hidden" name="boton" id="boton" value="">
            </form>
            <br>
            <table border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
            	<td>
                	<iframe name="nominapdf" id="nominapdf" src="" style="width:971px; height:488px;"></iframe>
                </td>
            </tr>
            </table>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Consulta de recibos</div>    
    </div>
</div>
</body>
</html>