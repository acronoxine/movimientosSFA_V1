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


if(isset($_POST["boton"]) && $_POST["boton"] == "cierre")
{
	$quin = explode("|", $_POST["quincena"]); 
	$quincena = $quin[0];
	
	$sql = "insert into nominapagohist";
	$sql .= " select * from nominapago where quincena = '$quincena' and anio = '" . date("Y") . "';";
	$res = mysql_query($sql, $conexion);
	
	if($res)
	{
		$sql = "delete from nominapago where quincena = '$quincena' and anio = '" . date("Y") . "';;";
		$res = mysql_query($sql, $conexion);

		$sql = "update cat_quincenas set cerrada = 1 where quincena = '$quincena' and year(fhasta) = '" . date("Y") . "'";
		$res = mysql_query($sql, $conexion);
		
		$sql = "Delete from nomina where concepto = '258'";
		$res = mysql_query($sql, $conexion);
		
		// se cierra la nomina de pensiones
		
		$sql = "insert into nominapensionhist";
		$sql .= " select * from nominapension where quincena = '$quincena' and anio = '" . date("Y") . "';";
		$res = mysql_query($sql, $conexion);
		
		$sql = "delete from nominapension where quincena = '$quincena' and anio = '" . date("Y") . "';;";
		$res = mysql_query($sql, $conexion);
	}
	
	echo "<script>";
	echo "location.replace('emision.php');";
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

<script src="js_popup/jquery.js"></script>
<script src="js_popup/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="js_popup/fancybox/jquery.fancybox-1.3.4.css">


<script type="text/javascript"> 
$(document).ready(function(){
	
	function emisionExcel(datos)
	{
		$.fancybox({
			'href'				: 'excel.php' + datos,
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'width'				: 500,
			'height'			: 200,
			'modal'				: false,
			'type'				: 'iframe'
		});
	}
	
	$("#excel").click(function() {
		
		if(form1.quincena.value != "" && form1.quincena.value != "")
		{
			if(form1.boton.value == "N" || form1.boton.value == "R")
			{
				var dato = form1.quincena.value;
			
				var quincena = dato.substr(0, dato.indexOf("|", dato));
				dato = dato.substr(dato.indexOf("|", dato) + 1);
				
				var fdesde = dato.substr(0, dato.indexOf("|", dato));
				dato = dato.substr(dato.indexOf("|", dato) + 1);
				
				var fhasta = dato.substr(0);
				
				emisionExcel("?quincena="+quincena+"&ur="+form1.ur.value);
			}else{
				alert("Genere la quincena antes de generar el archivo de dispersi�n, de click en TABULADOR � RECIBOS.");
			}
		}else{
			alert("Seleccione una UR y una quincena!!");
		}
	});
	
	
	function emisionLayout(datos)
	{
		$.fancybox({
			'href'				: 'layout.php' + datos,
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'width'				: 500,
			'height'			: 200,
			'modal'				: false,
			'type'				: 'iframe'
		});
	}
	
	$("#layout").click(function() {
		
		if(form1.quincena.value != "" && form1.quincena.value != "")
		{
			if(form1.boton.value == "N" || form1.boton.value == "R")
			{
				var dato = form1.quincena.value;
			
				var quincena = dato.substr(0, dato.indexOf("|", dato));
				dato = dato.substr(dato.indexOf("|", dato) + 1);
				
				var fdesde = dato.substr(0, dato.indexOf("|", dato));
				dato = dato.substr(dato.indexOf("|", dato) + 1);
				
				var fhasta = dato.substr(0);
				
				emisionLayout("?quincena="+quincena+"&ur="+form1.ur.value);
			}else{
				alert("Genere la quincena antes de generar el archivo de dispersi�n, de click en TABULADOR � RECIBOS.");
			}
		}else{
			alert("Seleccione una UR y una quincena!!");
		}
	});
	
});
</script>

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
		alert("Seleccione el n�mero de quincena");
		form.quincena.focus();
		return false;
	}
	
	if(confirm("�Confirma que desea emitir la nomina de la quincena seleccionada?"))
		return true;
	else
		return false;
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
		form1.action = "pdfnomina.php";
		form1.submit();
	}
	
	if(document.getElementById('boton').value == "R")
	{
		form1.action = "pdfrecibos.php";
		form1.submit();
	}
	
	form1.action = "";
}


function cierre(form)
{
	if(form.quincena.value == '') 
	{
		alert('Seleccione una quincena'); 
		form.quincena.focus();
	}else{
		var dato = form.quincena.value;
		
		var quincena = dato.substr(0, dato.indexOf("|", dato));
		dato = dato.substr(dato.indexOf("|", dato) + 1);
		
		var fdesde = dato.substr(0, dato.indexOf("|", dato));
		dato = dato.substr(dato.indexOf("|", dato) + 1);
		
		var fhasta = dato.substr(0);
		
		if(confirm('�Confirma que desea cerrar la quincena # ' + quincena + '?')) 
		{
			form.action = "emision.php";
			form.target = "_top";
			form.submit();
		}
	}
}

</script>


</head>
<body topmargin="0" leftmargin="0">
<div id="todo">
	<div id="cabeza_prin">
    </div>
    <div id="cuerpo">
    <div id="tituloarriba">
   		<div id="titulosup">Emisi�n de la nomina</div>    
    </div>
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
        	<form name="form1" id="form2" method="post" target="nominapdf">
            <table width="700" border="0" align="center" cellpadding="1" cellspacing="5" style="border:1px solid #E6E4E4;">
              <tr>
                <td align="center" colspan="2" bgcolor="#CCCCCC">
                	<label class="label">Emisi�n de N�mina de Empleados</label>
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
                            <option value="-1">Todos</option>
                            </select>
                        </td>
                    	<td>
                        	<label class="label">Quincena:</label>
                            <select name="quincena" id="quincena" class="lista" onChange="separa(this.value);">
                            <option value="">Seleccione</option>
                            <?
								include("fechas.php");
								
								$sql = "Select quincena, concat(lpad(day(fdesde), 2, '0'), '/', lpad(month(fdesde), 2, '0'), '/', year(fdesde)) as fdesde, concat(lpad(day(fhasta), 2, '0'), '/', lpad(month(fhasta), 2, '0'), '/', year(fhasta)) as fhasta from cat_quincenas where year(fdesde) = '$anio' and cerrada <> 1 order by quincena";
								$res = mysql_query($sql, $conexion);
								
								while($ren = mysql_fetch_array($res))
								{
									echo "\n<option value='$ren[quincena]|$ren[fdesde]|$ren[fhasta]'>$ren[quincena]</option>";
								}
                            
							?>
                            <option value='25||'>25</option>
                            </select>
                        </td>
                        <td>
                        	<label class="label"><? echo date("Y"); ?></label>
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
                        	<input type="hidden" name="aguinaldo" id="completo" value="C">

                        </td>
                    	<td>
                        	<input type="hidden" name="aguinaldo" id="primera" value="1P">

                        </td>
                    	<td>
                        	<input type="hidden" name="prima" id="prima" value="P">

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
                        <input class="boton" type="button" name="minomina" id="minomina" value="TABULADOR" onClick="document.getElementById('boton').value = 'N'; if(valida(this.form)) emitenomina();">
                    </td>
                    <td align="center">
                    	<input class="boton2" type="button" name="excel" id="excel" value="EXCEL">
                        <input class="boton2" type="button" name="limpiar" id="limpiar" value="LIMPIAR" onClick="location.replace('emision.php');">
                        <input class="boton2" type="button" name="cerrar" id="cerrar" value="CERRAR QUINCENA" onClick="document.getElementById('boton').value = 'cierre'; cierre(this.form);" style="width:119px;">
                    </td>
                    <td align="right">
                        <input class="boton" type="button" name="misrecibos" id="misrecibos" value="RECIBOS" onClick="document.getElementById('boton').value = 'R'; if(valida(this.form)) emitenomina();">
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
                	<iframe name="nominapdf" id="nominapdf" src="" style="width:971px; height:504px;"></iframe>
                </td>
            </tr>
            </table>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Emisi�n de la n�mina</div>    
    </div>
</div>
</body>
</html>