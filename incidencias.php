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

<script type="text/javascript"> 
$(document).ready(function(){
$("#empleado").change(function() {
			lista.document.location.replace("incidenicas_datos.php?empleado="+form1.empleado.value+"&fechai="+form1.fechai.value+"&fechaf="+form1.fechaf.value);
	});
});

function cambio(){
	lista.document.location.replace("incidenicas_datos.php?empleado="+form1.empleado.value+"&fechai="+form1.fechai.value+"&fechaf="+form1.fechaf.value);
}
</script>
<script type="text/javascript">
function incidencias(id,fechai,fechaf)
	{
		$.fancybox({
			'href'				: 'incidencias_mod.php?id='+id+"&fechai="+fechai+"&fechaf="+fechaf,
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'width'				: 950,
			'height'			: 600,
			'modal'				: false,
			'type'				: 'iframe'
		});
}
</script>

<link rel="stylesheet" type="text/css" href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<script src="controles_jquery/js/jquery-1.9.1.js"></script>
<script src="controles_jquery/js/jquery-ui-1.10.3.custom.js"></script>

<script>
var a = jQuery.noConflict();
a(document).ready(function(){
	a(function() {
		a("#fechai").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		a("#fechai").datepicker( "option", "showAnim", "show");
		a("#fechai").datepicker( "option", "dateFormat", "dd/mm/yy" );
		
		a("#fechaf").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		a("#fechaf").datepicker( "option", "showAnim", "show");
		a("#fechaf").datepicker( "option", "dateFormat", "dd/mm/yy" );
	});
});
</script>

</head>
<body topmargin="0" leftmargin="0">
<div id="todo">
	<div id="cabeza_prin">
    </div>
    <div id="cuerpo">
    <div id="tituloarriba">
   		<div id="titulosup">Incidencias</div> 
           
    </div>
    	<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
        <div id="centro_prin">
			<form name="form1" id="form2" method="post" target="incidencias">
            <table width="700" border="0" align="center" cellpadding="1" cellspacing="5" style="border:1px solid #E6E4E4;">
              <tr>
                <td align="center" colspan="2" bgcolor="#CCCCCC">
                	<label class="label">Reporte de Incidencias</label>
                </td>
              </tr>
              <tr>
                <td colspan="2">
					<table border="0" align="center">
                    <tr>
                    	<td>
                        	<label class="label">Fecha Inicial</label>
                            <input type="text" name="fechai" id="fechai" onChange="cambio()"  style="width:200px;"/>
                        </td>
                    	<td>
                        	<label class="label">Fecha Final</label>
                            <input type="text" name="fechaf" id="fechaf" onChange="cambio()" style="width:200px;"/>
                        </td>
                        
                    </tr>
                    </table>
                </td>
              </tr>
              <tr>
              	<td align="center" colspan="2">
                	<label class="label">Empleado:</label>
                     <select name="empleado" id="empleado" class="lista" style="width:200px;">
                            <option value="">Todos</option>
                            <?
                            	$sql = "Select CONCAT(paterno,' ',materno,' ',nombres) as nombre, idnominaemp 
								from  nominaemp where activo=1 order by paterno";
								$res = mysql_query($sql, $conexion);
								
								while($ren = mysql_fetch_array($res))
								{
									echo "\n<option value='$ren[idnominaemp]'>$ren[nombre]</option>";
								}
							?>
                            </select>
                </td>
              </tr>
            </table>
            <input type="hidden" name="_quincena" id="_quincena" value="">
            <input type="hidden" name="boton" id="boton" value="">
            </form>
            <br><br>
            <table border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
            	<td>
                	<iframe name="lista" id="lista" src="incidenicas_datos.php" style="width:900px; height:350px;"></iframe>
                </td>
            </tr>
            </table>
        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Incidencias</div>    
    </div>
</div>
</body>
</html>

</html>