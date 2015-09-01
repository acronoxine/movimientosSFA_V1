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
	if(form.ur.value == "-1")
	{
		alert("Seleccione una UR");
		form.ur.focus();
		return false;
	}
	
	if(form.quincena.value == "")
	{
		alert("Seleccione el número de quincena actual");
		form.quincena.focus();
		return false;
	}
	
	if(form.quincenacom.value == "")
	{
		alert("Seleccione el número de quincena de comparación");
		form.quincenacom.focus();
		return false;
	}
	
	if(form.aniocom.value == "")
	{
		alert("Indique el año de la quincena de comparación");
		form.aniocom.focus();
		return false;
	}
	
	return true;
}


function separa(dato)
{
	document.getElementById('completo').checked = false;
	document.getElementById('primera').checked = false;
	form1.prima.checked = false;
	
	var quincena = dato.substr(0, dato.indexOf("|", dato));
	dato = dato.substr(dato.indexOf("|", dato) + 1);
	
	var fdesde = dato.substr(0, dato.indexOf("|", dato));
	dato = dato.substr(dato.indexOf("|", dato) + 1);
	
	var fhasta = dato.substr(0);
	
	document.getElementById('fdesde').value = fdesde;
	document.getElementById('fhasta').value = fhasta;
	document.getElementById('_quincena').value = quincena;
	
	if(quincena == 25)
	{
		form1.completo.disabled = true;
		form1.primera.disabled = true;
		form1.prima.disabled = true;
	}else{
		form1.completo.disabled = false;
		form1.primera.disabled = false;
		form1.prima.disabled = false;
	}
}


function emitenomina()
{
	if(document.getElementById('boton').value == "N")
	{
		form1.action = "pdfestadistica.php";
		form1.submit();
	}
	
	form1.action = "";
}


</script>


</head>
<body topmargin="0" leftmargin="0">
<div id="todo">
	<div id="cabeza_prin">
    </div>
    <div id="cuerpo">
    <div id="tituloarriba">
   		<div id="titulosup">Estadística</div>    
    </div>
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
        	<form name="form1" id="form2" method="post" target="nominapdf">
            <table width="700" border="0" align="center" cellpadding="1" cellspacing="5" style="border:1px solid #E6E4E4;">
              <tr>
                <td align="center" colspan="2" bgcolor="#CCCCCC">
                	<label class="label">Estadística</label>
                </td>
              </tr>
              <tr>
                <td colspan="2">
					<table border="0" align="center">
                    <tr>
                    	<td colspan="4" align="center">
                        	<label class="label">UR:</label>
                            <select name="ur" id="ur" class="lista" style="width:200px;">
                            <option value="-1">Seleccione</option>
                            <?
                            	$sql = "Select * from cat_area order by descripcion";
								$res = mysql_query($sql, $conexion);
								
								while($ren = mysql_fetch_array($res))
								{
									echo "\n<option value='$ren[idarea]'>$ren[descripcion]</option>";
								}
							?>
                            <option value="">Todos</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                        	<label class="label">Quincena actual:</label>
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
                            <option value='25||'>25</option>
                            </select>
                        </td>
                        <td width="50">
                        	<label class="label"><? echo date("Y"); ?></label>
                        </td>
                    	<td>
                        	<label class="label">Quincena de comparación:</label>
                            <select name="quincenacom" id="quincenacom" class="lista">
                            <option value="">Seleccione</option>
                            <?
								$sql = "Select quincena, concat(lpad(day(fdesde), 2, '0'), '/', lpad(month(fdesde), 2, '0'), '/', year(fdesde)) as fdesde, concat(lpad(day(fhasta), 2, '0'), '/', lpad(month(fhasta), 2, '0'), '/', year(fhasta)) as fhasta from cat_quincenas where year(fdesde) = '$anio' order by quincena";
								$res = mysql_query($sql, $conexion);
								
								while($ren = mysql_fetch_array($res))
								{
									echo "\n<option value='$ren[quincena]'>$ren[quincena]</option>";
								}
                            
							?>
                            <option value='25||'>25</option>
                            </select>
                        </td>
                        <td>
                        	<input class="campo" type="text" name="aniocom" id="aniocom" value="<? echo date("Y"); ?>" style="width:35px; text-align:center;">
                        </td>
                    </tr>
                    </table>
                </td>
              </tr>
              <tr>
              	<td align="right">
                	<label class="label">De:</label>
                    <input class="campo" type="text" readonly name="fdesde" id="fdesde" value="" size="10">
                </td>
              	<td>
                	<label class="label">A:</label>
                    <input class="campo" type="text" readonly name="fhasta" id="fhasta" value="" size="10">
                </td>
              </tr>
              <tr>
              	<td colspan="2">
                	<table border="0" align="center">
                    <tr>
                    	<td>
                        	<input type="radio" name="aguinaldo" id="completo" value="C">
                            <label class="label" for="completo">Aguinaldo completo</label>
                        </td>
                    	<td>
                        	<input type="radio" name="aguinaldo" id="primera" value="1P">
                            <label class="label" for="primera">Aguinaldo 1ra parte</label>
                        </td>
                    	<td>
                        	<input type="checkbox" name="prima" id="prima" value="P">
                            <label class="label" for="prima">Prima vacacional</label>
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
                        <input class="boton" type="button" name="minomina" id="minomina" value="EMITIR" onClick="document.getElementById('boton').value = 'N'; if(valida(this.form)) emitenomina();">
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
                	<iframe name="nominapdf" id="nominapdf" src="" style="width:971px; height:465px;"></iframe>
                </td>
            </tr>
            </table>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Estadística</div>    
    </div>
</div>
</body>
</html>