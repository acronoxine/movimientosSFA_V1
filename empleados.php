<?
session_start ();
if ($_SESSION ["m_sesion"] != 1) {
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit ();
}
?>

<?php require_once('Connections/conexion.php'); ?>
<?php

if (! function_exists ( "GetSQLValueString" )) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
		if (PHP_VERSION < 6) {
			$theValue = get_magic_quotes_gpc () ? stripslashes ( $theValue ) : $theValue;
		}
		
		$theValue = function_exists ( "mysqli_real_escape_string" ) ? mysqli_real_escape_string ( $theValue ) : mysql_escape_string ( $theValue );
		
		switch ($theType) {
			case "text" :
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "long" :
			case "int" :
				$theValue = ($theValue != "") ? intval ( $theValue ) : "NULL";
				break;
			case "double" :
				$theValue = ($theValue != "") ? doubleval ( $theValue ) : "NULL";
				break;
			case "date" :
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "defined" :
				$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
				break;
		}
		return $theValue;
	}
}

//mysql_select_db ( $database_conexion, $conexion );
$query_areas = "SELECT * FROM cat_area order by descripcion";
$areas = mysqli_query ( $conexion, $query_areas ) or die ( mysqli_error () );
$row_areas = mysqli_fetch_assoc ( $areas );
$totalRows_areas = mysqli_num_rows ( $areas );

//mysql_select_db ( $database_conexion, $conexion );
$query_programas = "SELECT * FROM cat_programa order by descripcion";
$programas = mysqli_query ( $conexion, $query_programas ) or die ( mysqli_error () );
$row_programas = mysqli_fetch_assoc ( $programas );
$totalRows_programas = mysqli_num_rows ( $programas );

//mysql_select_db ( $database_conexion, $conexion );
$query_categorias = "SELECT distinct
    idcategoria,
    nivel,
    clave,
    descripcion,
    (sueldobase + hom) AS sueldobase,
    empleado_plaza.estado
FROM
    cat_categoria,
    empleado_plaza
WHERE
   estado = 'VACANTE'
ORDER BY descripcion";
$categorias = mysqli_query ( $conexion, $query_categorias ) or die ( mysqli_error () );
$row_categorias = mysqli_fetch_assoc ( $categorias );
$totalRows_categorias = mysqli_num_rows ( $categorias );

//mysql_select_db ( $database_conexion, $conexion );
$query_subprograma = "SELECT * FROM cat_subprograma order by descripcion";
$subprograma = mysqli_query ( $conexion, $query_subprograma ) or die ( mysqli_error () );
$row_subprograma = mysqli_fetch_assoc ( $subprograma );
$totalRows_subprograma = mysqli_num_rows ( $subprograma );

//mysql_select_db ( $database_conexion, $conexion );
$query_proyecto = "SELECT * FROM cat_proyecto order by descripcion";
$proyecto = mysqli_query ( $conexion, $query_proyecto ) or die ( mysqli_error () );
$row_proyecto = mysqli_fetch_assoc ( $proyecto );
$totalRows_proyecto = mysqli_num_rows ( $proyecto );

//mysql_select_db ( $database_conexion, $conexion );
$query_estados = "SELECT * FROM cat_estados ORDER BY estado ASC";
$estados = mysqli_query ( $conexion, $query_estados ) or die ( mysqli_error () );
$row_estados = mysqli_fetch_assoc ( $estados );
$totalRows_estados = mysqli_num_rows ( $estados );
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<title>Sistema de N&oacute;mina de Empleados</title>

<link rel="shortcut icon" type="image/x-icon" href="http://www.michoacan.gob.mx/wp-content/themes/estaenti/img/favicon.ico">


<link rel="stylesheet" type="text/css" href="css/estilos.css">
<link rel="stylesheet" type="text/css"
	href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<link rel="stylesheet" type="text/css"
	href="controles_jquery/js/jquery.fancybox.css">
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="controles_jquery/js/jquery-ui-1.10.3.custom.js"></script>
<script src="controles_jquery/js/jquery.fancybox.js"></script>

<script>
/**
 * This Script will be removed after check all modules
 * If all works!.
 */
/*$(document).ready(function(){
		function calculaimportes(datos)
	{
			$.fancybox({
				'href'				: 'calculaimportes.php' + datos,
				'autoScale'			: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'width'				: 950,
				'height'			: 688,
				'modal'				: false,
				'type'				: 'iframe'
			});
	}
		
		$("#conceptos").click(function(e) {
			calculaimportes("?idnominaemp=" + document.getElementById('_idnominaemp').value);

	function formato(datos)
			{
				$.fancybox({
					'href'				: 'imovimiento.php' + datos,
					'autoScale'			: false,
					'transitionIn'		: 'none',
					'transitionOut'		: 'none',
					'width'				: 970,
					'height'			: 688,
					'modal'				: true,
					'type'				: 'iframe'
				});
			}
	
	$("#movimiento").click(function() {
		formato("?idnominaemp=" + document.getElementById('_idnominaemp').value);
	});
	
});*/
</script>


<script>

function validafecha(fecha){
	var dtCh= "/";
	var minYear=1900;
	var maxYear=2100;
	function isInteger(s){
		var i;
		for (i = 0; i < s.length; i++){
			var c = s.charAt(i);
			if (((c < "0") || (c > "9"))) return false;
		}
		return true;
	}
	function stripCharsInBag(s, bag){
		var i;
		var returnString = "";
		for (i = 0; i < s.length; i++){
			var c = s.charAt(i);
			if (bag.indexOf(c) == -1) returnString += c;
		}
		return returnString;
	}
	function daysInFebruary (year){
		return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
	}
	function DaysArray(n) {
		for (var i = 1; i <= n; i++) {
			this[i] = 31
			if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
			if (i==2) {this[i] = 29}
		}
		return this
	}
	function isDate(dtStr){
		var daysInMonth = DaysArray(12)
		var pos1=dtStr.indexOf(dtCh)
		var pos2=dtStr.indexOf(dtCh,pos1+1)
		var strDay=dtStr.substring(0,pos1)
		var strMonth=dtStr.substring(pos1+1,pos2)
		var strYear=dtStr.substring(pos2+1)
		strYr=strYear
		if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
		if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
		for (var i = 1; i <= 3; i++) {
			if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
		}
		month=parseInt(strMonth)
		day=parseInt(strDay)
		year=parseInt(strYr)
		if (pos1==-1 || pos2==-1){
			return false
		}
		if (strMonth.length<1 || month<1 || month>12){
			return false
		}
		if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
			return false
		}
		if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
			return false
		}
		if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
			return false
		}
		return true
	}
	if(isDate(fecha)){
		return true;
	}else{
		return false;
	}
}


function valida(form)
{
	if(form.rfc_iniciales.value == "")
	{
		alert("Indique las iniciales del RFC del empleado");
		form.rfc_iniciales.focus();
		return false;
	}
	
	if(form.rfc_fechanac.value == "")
	{
		alert("Indique la fecha de nacimiento del RFC del empleado");
		form.rfc_fechanac.focus();
		return false;
	}
	
	if(form.curp.value == "")
	{
		alert("Indique la CURP del empleado");
		form.curp.focus();
		return false;
	}
	
	if(form.paterno.value == "")
	{
		alert("Indique el apellido paterno del empleado");
		form.paterno.focus();
		return false;
	}
	
	if(form.materno.value == "")
	{
		alert("Indique el apellido materno del empleado");
		form.materno.focus();
		return false;
	}
	
	if(form.nombres.value == "")
	{
		alert("Indique el nombre de pila del empleado");
		form.nombres.focus();
		return false;
	}
	
	if(form.ncuenta.value == "")
	{
		alert("Indique la cuenta bancaria donde se le depositará al empleado");
		form.ncuenta.focus();
		return false;
	}
	
	if(form.calle.value == "")
	{
		alert("Indique la calle del domicilio del empleado");
		form.calle.focus();
		return false;
	}
	
	if(form.numint.value == "" && form.numext.value == "")
	{
		alert("Indique el número del domicilio del empleado");
		form.numext.focus();
		return false;
	}
	
	if(form.colonia.value == "")
	{
		alert("Indique la colonia del domicilio del empleado");
		form.colonia.focus();
		return false;
	}
	
	if(form.ciudad.value == "")
	{
		alert("Indique la ciudad del domicilio del empleado");
		form.ciudad.focus();
		return false;
	}
	
	if(form.estado.value == "")
	{
		alert("Indique la entidad federativa del domicilio del empleado");
		form.estado.focus();
		return false;
	}
	
	if(form.fechanacimiento.value == "")
	{
		alert("Indique la fecha de nacimiento del empleado");
		form.fechanacimiento.focus();
		return false;
	}

	/*if(form.programa.value == "")
	{
		alert("Indique el programa que corresponde al empleado");
		form.programa.focus();
		return false;
	}*/
	
/*	if(form.subprograma.value == "")
	{
		alert("Indique un subprograma");
		form.subprograma.focus();
		return false;
	}*/
	
	if(form.fechainicio.value == "")
	{
		if(!validafecha(form.fechainicio.value))
		{
			alert("El formato de la fecha de inicio no es correcto debe ser (DD/MM/YYYY).");
			form.fechainicio.focus();
			return false;
		}
		
		alert("Indique la fecha de inicio del empleado");
		form.fechainicio.focus();
		return false;
	}
	
	if(form.fechaingr.value == "")
	{
		if(!validafecha(form.fechaingr.value))
		{
			alert("El formato de la fecha de ingreso no es correcto debe ser (DD/MM/YYYY).");
			form.fechaingr.focus();
			return false;
		}
		
		alert("Indique la fecha de ingreso del empleado");
		form.fechaingr.focus();
		return false;
	}
	
	if(form.colonia.value == "")
	{
		alert("Indique la colonia del domicilio del empleado");
		form.colonia.focus();
		return false;
	}
	
	/*if(form.area.value == "")
	{
		alert("Indique el área o unidad responsable(UR) que corresponde al empleado");
		form.area.focus();
		return false;
	}
	
	if(form.categoria.value == "")
	{
		alert("Indique la categoría que corresponde al empleado");
		form.categoria.focus();
		return false;
	}*/
	
	if(!validafecha(form.fechanacimiento.value))
	{
		alert("El formato de la fecha de nacimiento no es correcto debe ser (DD/MM/YYYY).");
		form.fechanacimiento.focus();
		return false;
	}
	
	if(form.de_hrs.value == "")
	{
		alert("Indique el inicio de la jornada laboral en horas.");
		form.de_hrs.focus();
		return false;
	}
	
	if(form.a_hrs.value == "")
	{
		alert("Indique el final de la jornada laboral en horas.");
		form.a_hrs.focus();
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
   
}

</script>
<script>
var a = jQuery.noConflict();
a(document).ready(function(){
	a(function() {
		a("#fechainicio").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		a("#fechainicio").datepicker( "option", "showAnim", "show");
		a("#fechainicio").datepicker( "option", "dateFormat", "dd/mm/yy" );
	});
	
	a(function() {
		a("#fechaingr").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		a("#fechaingr").datepicker( "option", "showAnim", "show");
		a("#fechaingr").datepicker( "option", "dateFormat", "dd/mm/yy" );
	});
	
	a(function() {
		a("#fechafin").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		a("#fechafin").datepicker( "option", "showAnim", "show");
		a("#fechafin").datepicker( "option", "dateFormat", "dd/mm/yy" );
	});
	
	a(function() {
		a("#fechanacimiento").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		a("#fechanacimiento").datepicker( "option", "showAnim", "show");
		a("#fechanacimiento").datepicker( "option", "dateFormat", "dd/mm/yy" );
	});
});
</script>

<script>

function sueldo(dato)
{
	if(dato != "")
	{
		var id = dato.substr(0, dato.indexOf("|", dato));
		dato = dato.substr(dato.indexOf("|", dato) + 1);
		
		var sueldob = dato.substr(0, dato.indexOf("|", dato));
		dato = dato.substr(dato.indexOf("|", dato) + 1);
		
		var nivel = dato.substr(0);

		form1.sueldobase.value = sueldob;
		form1.plaza.value = nivel;
	}
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


/* Carga los subprogramas a partir de un id de programa*/
function programas(id,desc)
{
	Resultado = document.getElementById('ajax_programas');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_programas.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idprograma="+id+"&desc="+desc);
}
/* Carga los proyectos a partir de un id de subprograma*/
function proyecto(id,desc)
{
	Resultado = document.getElementById('ajax_proyecto');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_proyectos.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idsprograma="+id+"&desc="+desc);
}
/* Carga los proyectos a partir de un id de subprograma*/
function plazas(proyecto,subprograma)
{
	Resultado = document.getElementById('ajax_plazas');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_plazas.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idproyecto="+proyecto+"&idsubprograma="+subprograma);
}

function cargasueldo_plaza(idplaza)
{
	Resultado = document.getElementById('ajax_sueldo_plaza');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_sueldo_plaza.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idplaza="+idplaza);
}

function cargasubprogramas(idprograma)
{
	Resultado = document.getElementById('ajax_subprogramas');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_subprogramas.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idprograma="+idprograma);
}



function cargaciudad(idestado)
{
	Resultado = document.getElementById('ajax_ciudad');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_municipios.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idestado="+idestado);
}

function cargasueldo(idcategoria)
{
	Resultado = document.getElementById('ajax_sueldos');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_sueldos.php",true);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idcategoria="+idcategoria);
}

function buscaemp(dato)
{
	lista.document.location.replace("empleados_lista.php?consulta="+dato);
}

function buscacatego(cve)
{
	if(cve.length == 5)
	{
		form1.categoria.value = cve;
	}
}

</script>
<script>
/**
 * Load values into comboBox on cascade
 * Made by Jose Luis Zirangua Mejia
 */
	function get_programas(){
			jQuery.ajax({
			      type		: "POST",
			      dataType	: "json",
			      url		: "ajax_programas.php", //Relative or absolute path to response.php file
			      success	: function(data) {
						jQuery.each(data,function(i,val){
							jQuery( "#programas" ).append("<option value="+val['idprograma']+">"+ val['clave'] +"</option>");
						});
				        
			    }
			});
	}
	function get_subprogramas(){
			var programa = jQuery( "#programas" ).val();
			jQuery.ajax({
				type		: "POST",
				dataType	: "json",
				url 		: "jQuery_suprogramas.php",
				data		: {id_programa: programa},
				success		: function (data) {
						jQuery.each(data,function(i,val){
							jQuery( "#subprogramas" ).append("<option value="+val['idsubprograma']+">"+ val['clave'] +"</option>");
						});
				}
			});		
	}
</script>

</head>
<body topmargin="0" leftmargin="0">
	<div id="todo">
		<div id="cabeza_prin"></div>
		<div id="cuerpo">
			<div id="tituloarriba">
				<div id="titulosup">Alta</div>
			</div>
			<div id="panelizq">
			<? include("menu.php"); ?>
        </div>
			<div id="centro_prin">
				<form method="post" name="form1" action="empleados_lista.php"
					target="lista">
					<table align="center" border="0">
						<tr>
							<td valign="top">
								<table align="center" border="0">
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">RFC:</label></td>
										<td><input class="campo" type="text" name="rfc_iniciales"
											id="rfc_iniciales" value="" size="3" maxlength="4"
											style="width: 36px;"
											onKeyPress="return sololetras(this.form, event);"
											onKeyUp="if(this.value.length == 4) this.form.rfc_fechanac.focus();">
											<input class="campo" type="text" name="rfc_fechanac"
											id="rfc_fechanac" value="" size="5" maxlength="6"
											style="width: 45px;"
											onKeyPress="return solonumeros(this.form, event);"
											onKeyUp="if(this.value.length == 6) this.form.rfc_homoclave.focus();">
											<input class="campo" type="text" name="rfc_homoclave"
											id="rfc_homoclave" value="" size="2" maxlength="3"
											style="width: 23px;"
											onKeyUp="if(this.value.length == 3) this.form.curp.focus();">
											<label class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">CURP:</label></td>
										<td><input class="campo" type="text" name="curp" value=""
											size="18" maxlength="22"><label class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Folio:</label></td>
										<td><input class="campo" type="text" name="folio" value=""
											size="10" maxlength="10"
											onKeypress="return solonumeros(this.form, event);"></td>
									</tr>
									<tr valign="baseline">
										<!-- Start UR -->
										<td nowrap align="right"><label class="label">UR:</label></td>
										<td><select class="lista" id="getUR" style="width: 180px;"
											onchange="get_programas()">
												<option value="0">Seleccione</option>
												<option value="01">01</option>
												<option value="02">02</option>
												<option value="03">03</option>
												<option value="04">04</option>
												<option value="05">05</option>
												<option value="06">06</option>
												<option value="07">07</option>
												<option value="08">08</option>
												<option value="09">09</option>
												<option value="10">10</option>
												<option value="11">11</option>
												<option value="12">12</option>
												<option value="13">13</option>
												<option value="14">14</option>
												<option value="15">15</option>
												<option value="16">16</option>
												<option value="17">17</option>
												<option value="18">18</option>
												<option value="19">19</option>
										</select> <label class="label">*</label></td>
									</tr>


									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Programa:</label></td>
										<td><select class="lista" id="programas" style="width: 180px;"
											onchange="get_subprogramas()">
												<option value="0">Seleccione</option>
										</select> <label class="label">*</label></td>

									</tr>

									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Subprograma:</label></td>
										<td><select class="lista" id="subprogramas" style="width: 180px;">
												<option value="0">Seleccione</option>
										</select> <label class="label">*</label></td>
									</tr>

									<!-- <tr valign="baseline">
										<td nowrap align="right"><label class="label">Proyecto:</label></td>
										<td>
											<div id="ajax_proyecto"></div>
										</td>
									</tr>-->
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Plazas:</label></td>
										<td><select class="lista" name="categoria" style="width: 180px;"
											onChange="cargasueldo(this.value);">
												<option value="">Seleccione</option>
                  					<?php
																							do {
																								?>
                  						<option
													value="<?php echo $row_categorias['idcategoria']; ?>"><?php echo $row_categorias['clave'], " ", $row_categorias['descripcion']?></option>
                  					<?php
																							} while ( $row_categorias = mysqli_fetch_assoc ( $categorias ) );
																							?>
                						</select><label class="label">*</label></td>

									</tr>
									<tr valign="baseline">
										<td nowrap align="left"></td>
										<td><div id="ajax_sueldos"
												style="position: relative; left: -87px;">
												<script>
													cargasueldo('');
							 					</script>
											</div></td>
									</tr>

									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Tipo de
												contratación:</label></td>
										<td><select name="contrato" class="lista">
												<option value="EVENTUAL">EVENTUAL</option>
												<option value="CONFIANZA">CONFIANZA</option>
												<option value="BASE">BASE</option>
										</select></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Fecha de inicio:</label></td>
										<td><input class="campo" type="text" name="fechainicio"
											id="fechainicio" value="" size="10" maxlength="10"><label
											class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Fecha de Final:</label></td>
										<td><input class="campo" type="text" name="fechafin"
											id="fechafin" value="" size="10" maxlength="10"><label
											class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Fecha de
												ingreso:</label></td>
										<td><input class="campo" type="text" name="fechaingr"
											id="fechaingr" value="" size="10" maxlength="10"><label
											class="label">*</label></td>
									</tr>
								</table>
							</td>
							<td valign="top">
								<table align="center" border="0">
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Apellido
												Paterno:</label></td>
										<td><input class="campo" type="text" name="paterno" value=""
											size="20" maxlength="55"
											onKeyPress="return sololetras(this.form, event);"><label
											class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Apellido
												Materno:</label></td>
										<td><input class="campo" type="text" name="materno" value=""
											size="20" maxlength="55"
											onKeyPress="return sololetras(this.form, event);"><label
											class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Nombre(s):</label></td>
										<td><input class="campo" type="text" name="nombres" value=""
											size="20" maxlength="55"
											onKeyPress="return sololetras(this.form, event);"><label
											class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Calle:</label></td>
										<td><input class="campo" type="text" name="calle" value=""
											size="20" maxlength="55"><label class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Num. int:</label></td>
										<td><input class="campo" type="text" name="numint" value=""
											size="7" maxlength="7"
											onKeyPress="return solonumeros(this.form, event);"><label
											class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Num. ext:</label></td>
										<td><input class="campo" type="text" name="numext" value=""
											size="7" maxlength="7"
											onKeyPress="return solonumeros(this.form, event);"><label
											class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Colonia:</label></td>
										<td><input class="campo" type="text" name="colonia" value=""
											size="20" maxlength="60"><label class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Cp:</label></td>
										<td><input class="campo" type="text" name="cp" value=""
											size="5" maxlength="5"
											onKeyPress="return solonumeros(this.form, event);"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Estado:</label></td>
										<td><select name="estado" id="estado" class="lista"
											onChange="cargaciudad(this.value);" style="width: 200px;">
												<option value="">Seleccione</option>
                  <?php
																		do {
																			?>
                  <option value="<?php echo $row_estados['idestados']?>"><?php echo $row_estados['estado'];?></option>
                  <?php
																		} while ( $row_estados = mysqli_fetch_assoc ( $estados ) );
																		?>
                    </select><label class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Ciudad:</label></td>
										<td>
											<div id="ajax_ciudad"></div> <script>

					cargaciudad('-1');
					
					</script>
										</td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Fecha de
												nacimiento:</label></td>
										<td><input class="campo" type="text" name="fechanacimiento"
											id="fechanacimiento" value="" size="10" maxlength="10"><label
											class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Sexo:</label></td>
										<td><select name="sexo" class="lista">
												<option value="M"
													<?php if (!(strcmp("M", ""))) {echo "SELECTED";} ?>>Masculino</option>
												<option value="F"
													<?php if (!(strcmp("F", ""))) {echo "SELECTED";} ?>>Femenino</option>
										</select></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Estado civil:</label></td>
										<td><select name="ecivil" class="lista">
												<option value="1"
													<?php if (!(strcmp("S", ""))) {echo "SELECTED";} ?>>Soltero(a)</option>
												<option value="2"
													<?php if (!(strcmp("C", ""))) {echo "SELECTED";} ?>>Casado(a)</option>
												<option value="3"
													<?php if (!(strcmp("D", ""))) {echo "SELECTED";} ?>>Divorciado(a)</option>
												<option value="4"
													<?php if (!(strcmp("O", ""))) {echo "SELECTED";} ?>>Viudo(a)</option>
												<option value="5"
													<?php if (!(strcmp("V", ""))) {echo "SELECTED";} ?>>Union
													libre(a)</option>
										</select></td>
									</tr>
								</table>
							</td>
							<td valign="top">
								<table align="center" border="0">
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Nacionalidad:</label></td>
										<td><select name="nacionalidad" class="lista">
												<option value="1"
													<?php if (!(strcmp("N", ""))) {echo "SELECTED";} ?>>Nacional</option>
												<option value="2"
													<?php if (!(strcmp("E", ""))) {echo "SELECTED";} ?>>Extranjera</option>
										</select></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Afiliación IMSS:</label></td>
										<td><input class="campo" type="text" name="nafiliacion"
											value="" size="10" maxlength="20"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Salario:</label></td>
										<td><select name="salariofv" class="lista">
												<option value="F"
													<?php if (!(strcmp("F", ""))) {echo "SELECTED";} ?>>Fijo</option>
												<option value="V"
													<?php if (!(strcmp("V", ""))) {echo "SELECTED";} ?>>Variable</option>
										</select></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Nomina:</label></td>
										<td><select name="nomina" class="lista">
												<option value="Q"
													<?php if (!(strcmp("Q", ""))) {echo "SELECTED";} ?>>Quincenal</option>
												<option value="S"
													<?php if (!(strcmp("S", ""))) {echo "SELECTED";} ?>>Semanal</option>
										</select></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Jornada:</label></td>
										<td><select name="jornada" class="lista">
												<option value="1"
													<?php if (!(strcmp("D", ""))) {echo "SELECTED";} ?>>Diurna</option>
												<option value="2"
													<?php if (!(strcmp("N", ""))) {echo "SELECTED";} ?>>Nocturna</option>
												<option value="3"
													<?php if (!(strcmp("M", ""))) {echo "SELECTED";} ?>>Mixta</option>
												<option value="4"
													<?php if (!(strcmp("E", ""))) {echo "SELECTED";} ?>>Especial</option>
										</select></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">De:</label></td>
										<td><input class="campo" type="text" name="de_hrs" value="8"
											size="2" maxlength="2"
											onKeyPress="return solonumeros(this.form, event);"
											style="width: 15px;"> <label class="label">a:</label> <input
											class="campo" type="text" name="a_hrs" value="17" size="2"
											maxlength="2"
											onKeyPress="return solonumeros(this.form, event);"
											style="width: 15px;"> <label class="label">hrs.</label> <label
											class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Forma de pago:</label></td>
										<td><select name="formapago" class="lista">
												<option value="DE"
													<?php if (!(strcmp("DE", ""))) {echo "SELECTED";} ?>>Deposito</option>
												<option value="CH"
													<?php if (!(strcmp("CH", ""))) {echo "SELECTED";} ?>>Cheque</option>
										</select></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Núm. de cuenta:</label></td>
										<td><input class="campo" type="text" name="ncuenta" value=""
											size="13" maxlength="16"
											onKeyPress="return solonumeros(this.form, event);"><label
											class="label">*</label></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">CLABE bancaria:</label></td>
										<td><input class="campo" type="text" name="clabe" value=""
											size="13" maxlength="25"
											onKeyPress="return solonumeros(this.form, event);"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Escolaridad:</label></td>
										<td><select name="escolaridad" class="lista">
												<option value="P"
													<?php if (!(strcmp("P", ""))) {echo "SELECTED";} ?>>Primaria</option>
												<option value="S"
													<?php if (!(strcmp("S", ""))) {echo "SELECTED";} ?>>Secundaria</option>
												<option value="B"
													<?php if (!(strcmp("B", ""))) {echo "SELECTED";} ?>>Bachillerato</option>
												<option value="L"
													<?php if (!(strcmp("L", ""))) {echo "SELECTED";} ?>>Licenciatura</option>
												<option value="O"
													<?php if (!(strcmp("O", ""))) {echo "SELECTED";} ?>>Posgrado</option>
										</select></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Afiliación
												ISSSTE:</label></td>
										<td><input class="campo" type="text" name="nafiliacionissste"
											value="" size="13" maxlength="15"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Oficina de pago:</label></td>
										<td><input class="campo" type="text" name="oficinadepago"
											value="" size="5" maxlength="5"
											onKeyPress="return solonumeros(this.form, event);"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Cartilla SMN:</label></td>
										<td><input class="campo" type="text" name="cartillaSMN"
											value="" size="5" maxlength="5"
											onKeyPress="return solonumeros(this.form, event);"></td>
									</tr>
									<tr valign="baseline">
										<td nowrap align="right"><label class="label">Tipo de Recurso:</label></td>
										<td><select name="trecurso" class="lista">
												<option value="FEDERAL">Federal</option>
												<option value="ESTATAL">Propio</option>
												<option value="MIXTO">Mixto</option>
										</select> <label class="label">*</label></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<script>
						/*
							2015 Version, a new way to sent data and get result using fancyBox 2.x
							and JQUERY 1.10.x
						*/
							function calculaimportes(){
								
										jQuery.fancybox({
											'href'				: 'calculaimportes.php?idnominaemp=' + document.getElementById('_idnominaemp').value,
											'autoScale'			: false,
											'transitionIn'		: 'none',
											'transitionOut'		: 'none',
											'width'				: 950,
											'height'			: 688,
											'modal'				: false,
											'type'				: 'iframe'
										});
								
							}
						</script>
							<td colspan="2"><input class="boton" type="button" name="guardar"
								id="guardar" value="GUARDAR"
								onclick="if(valida(this.form)) submit();"> <!-- Here add event click -->
								<input class="boton" type="button" name="conceptos"
								id="conceptos" value="CONCEPTOS" onclick="calculaimportes();"> <!-- Here end click  -->
								<label class="label">Consulta por nombre de empleado:</label> <input
								class="campo" type="text" name="consulta" id="consulta" value=""
								onKeyUp="buscaemp(this.value);"></td>
							<!--        <td align="right">
        	<input class="boton" type="button" name="movimiento" id="movimiento" value="EMITIR MOVIMIENTO" style="width:140px;">
        </td>-->
						</tr>
						<tr>
							<td colspan="3"><iframe name="lista" id="lista"
									src="empleados_lista.php" style="width: 960px; height: 216px;"></iframe>
							</td>
						</tr>
					</table>
					<input type="hidden" name="MM_insert" value="form1"> <input
						type="hidden" name="_idnominaemp" id="_idnominaemp" value="">
				</form>
				<p>&nbsp;</p>
			</div>
		</div>
		<div id="tituloabajo">
			<div id="tituloinf">Alta</div>
		</div>
	</div>
</body>
</html>
<?php
mysqli_free_result ( $areas );

mysqli_free_result ( $programas );

mysqli_free_result ( $categorias );

mysqli_free_result ( $subprograma );

mysqli_free_result ( $proyecto );

mysqli_free_result ( $estados );
?>
