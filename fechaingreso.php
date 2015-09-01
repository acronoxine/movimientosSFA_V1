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
<?php 
require_once('Connections/conexion.php'); 
mysql_select_db($database_conexion, $conexion);

if(isset($_GET["update"]) && $_GET["update"]==1)
{
	$updatepz="UPDATE empleado_plaza set idnominaemp='$_POST[idnominaemp]',plaza_id='$_POST[plaza]',fecha_inicial='$_POST[fecha]',fecha_final='$_POST[fechaf]',estado='OCUPADO' WHERE plaza_id='$_POST[plaza]'";	
	mysql_query($updatepz,$conexion);
	$updateemp="UPDATE nominaemp set activo=1,estatus='$_POST[movimiento]', fechabaja='0000-00-00' WHERE idnominaemp='$_POST[idnominaemp]'";
	echo $updateemp;
	mysql_query($updateemp,$conexion);
	echo "<script>";
	echo "parent.document.afectacion.submit();";
	echo "</script>";
}

?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/estilos.css">
<style>
	#aviso{
		color:#F00;
		margin-top:100px;
		font-family:"Times New Roman", Times, serif;
		text-align:center;
		font-size:18px;
	}
</style>
<link rel="stylesheet" type="text/css" href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<script src="controles_jquery/js/jquery-1.9.1.js"></script>
<script src="controles_jquery/js/jquery-ui-1.10.3.custom.js"></script>

<script>

var a = jQuery.noConflict();
a(document).ready(function(){
	
	a(function() {
		a("#fecha").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		a("#fecha").datepicker( "option", "showAnim", "show");
		a("#fecha").datepicker( "option", "dateFormat", "yy-mm-dd" );
	});
	a(function() {
		a("#fechaf").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		a("#fechaf").datepicker( "option", "showAnim", "show");
		a("#fechaf").datepicker( "option", "dateFormat", "yy-mm-dd" );
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


function cargasubprogramas(idprograma)
{
	Resultado = document.getElementById('ajax_subprogramas');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_subprogramas.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idprograma="+idprograma);
}


function cargasueldo(idcategoria, idnominaemp)
{
	Resultado = document.getElementById('ajax_sueldos');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_sueldos_md.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idcategoria="+idcategoria+"&idnominaemp="+idnominaemp);
}
/* Carga los subprogramas a partir de un id de programa*/
function programas(id,desc)
{
	Resultado = document.getElementById('ajax_programas');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_programas.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idprograma="+id+"&desc="+desc);
}
/* Carga los proyectos a partir de un id de subprograma*/
function proyecto(id,desc)
{
	Resultado = document.getElementById('ajax_proyecto');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_proyectos.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idsprograma="+id+"&desc="+desc);
}
/* Carga los proyectos a partir de un id de subprograma*/
function plazas(proyecto,subprograma)
{
	Resultado = document.getElementById('ajax_plazas');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_plazas.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idproyecto="+proyecto+"&idsubprograma="+subprograma);
}

function cargasueldo_plaza(idplaza)
{
	Resultado = document.getElementById('ajax_sueldo_plaza');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_sueldo_plaza.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idplaza="+idplaza);
}

function cargasubprogramas(idprograma)
{
	Resultado = document.getElementById('ajax_subprogramas');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_subprogramas.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idprograma="+idprograma);
}
</script>

</head>
<body>
<?php
$query_pz=" SELECT cp.plaza_id FROM cat_plazas cp INNER JOIN empleado_plaza ep ON ep.plaza_id=cp.plaza_id
 WHERE ep.idnominaemp=".$_GET["idnominaemp"];
$resq=mysql_query($query_pz,$conexion);
$renq=mysql_num_rows($resq);

//echo $query_pz."<br>gdf".$renq;
if($renq>0){
	echo '<span id="aviso">El empleado tiene una plaza actualmente<br> es necesario darlo de baja antes de la asignación</span>';
}
else
{
mysql_select_db($database_conexion, $conexion);
$query_programas = "SELECT * FROM cat_programa order by descripcion";
$programas = mysql_query($query_programas, $conexion) or die(mysql_error());
$row_programas = mysql_fetch_assoc($programas);
$totalRows_programas = mysql_num_rows($programas);


?>
<form name="fechaingr" id="fechaingr" method="post" action="fechaingreso.php?update=1">
<table width="350" border="0">
	<tr valign="baseline">
    	<td nowrap align="left"><label class="label">Programa:</label></td>
        <td>
        	<select name="programa" class="lista" style="width:180px;" onChange="programas(this.value)">
            	<option value="">Seleccione</option>
                  <?php do {  ?>
                  <option value="<?php echo $row_programas['idprograma']?>" ><?php echo $row_programas['clave'], " ", $row_programas['descripcion']?></option>
                  <?php
				} while ($row_programas = mysql_fetch_assoc($programas));
?>
        	</select>
		</td>
	</tr>
	<tr valign="baseline">
		<td nowrap align="left"><label class="label">Subprograma:</label></td>
    	<td colspan="2"><div id="ajax_programas"></div></td>
  			    <script>programas('','Seleccione');</script>
    </tr>
	<tr valign="baseline">
    	<td nowrap align="left"><label class="label">Proyecto:</label></td>
		<td><div id="ajax_proyecto"></div></td>
			<script>proyecto('','Seleccione');</script>
    </tr>
    <tr valign="baseline">
    	<td nowrap align="left"><label class="label">Plazas:</label></td>
        <td>
			<div id="ajax_plazas"></div></td>
			<script>plazas('','');</script>
    </tr>
	<tr valign="baseline">
    	<td nowrap align="left"><label class="label">Sueldo Base:</label></td>				
        <td><div id="ajax_sueldo_plaza"></div></td> 
			<script>cargasueldo_plaza('');</script>
    </tr>
    <tr>
    	<td>
        <br>
        	<label class="label">Fecha de ingreso:</label><br>
        	<input class="campo" type="text" name="fecha" id="fecha" value="">
       </td>
       <td>
       <br>
    		<label class="label">Fecha de termino:</label><br>
        	<input class="campo" type="text" name="fechaf" id="fechaf" value="">
       </td>
	</tr>
    	<td valign="bottom" colspan="2">
        	<input class="boton2" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="submit();">
        </td>
	</tr>
</table>
<input type="hidden" name="idnominaemp" id="idnominaemp" value="<? echo $_GET[idnominaemp] ?>">
<input type="hidden" name="movimiento" id="movimiento" value="<? echo $_GET[movimiento] ?> ">
</form>
<?php }?>
</body>
</html>
