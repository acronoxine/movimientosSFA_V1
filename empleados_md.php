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

<?php require_once('Connections/conexion.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string( $theValue) : mysql_real_escape_string( $theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	
  @list($dia, $mes, $year) = split('[-./]', $_POST["fechainicio"]);
  $fechainicio = "$year-$mes-$dia";
  
  @list($dia, $mes, $year) = split('[-./]', $_POST["fechaingr"]);
  $fechaingr = "$year-$mes-$dia";
  
  @list($dia, $mes, $year) = split('[-./]', $_POST["fechanacimiento"]);
  $fechanacimiento = "$year-$mes-$dia";
	
  $updateSQL = sprintf("UPDATE nominaemp SET rfc_iniciales=%s, rfc_fechanac=%s, rfc_homoclave=%s, curp=%s, folio=%s, fechainicio=%s, fechaingr=%s, paterno=%s, materno=%s, nombres=%s, calle=%s, numint=%s, numext=%s, colonia=%s, cp=%s, ciudad=%s, estado=%s, fechanacimiento=%s, sexo=%s, ecivil=%s, nacionalidad=%s, nafiliacion=%s, salariofv=%s, contrato=%s, nomina=%s, jornada=%s, de_hrs=%s, a_hrs=%s, formapago=%s, ncuenta=%s, estatus=%s, clabe=%s, escolaridad=%s, nafiliacionissste=%s, oficinadepago=%s, cartillaSMN=%s WHERE idnominaemp=%s",
                       GetSQLValueString($_POST['rfc_iniciales'], "text"),
                       GetSQLValueString($_POST['rfc_fechanac'], "text"),
                       GetSQLValueString($_POST['rfc_homoclave'], "text"),
                       GetSQLValueString($_POST['curp'], "text"),
                       GetSQLValueString($_POST['folio'], "text"),
                       GetSQLValueString($fechainicio, "date"),
                       GetSQLValueString($fechaingr, "date"),
                       GetSQLValueString($_POST['paterno'], "text"),
                       GetSQLValueString($_POST['materno'], "text"),
                       GetSQLValueString($_POST['nombres'], "text"),
                       GetSQLValueString($_POST['calle'], "text"),
                       GetSQLValueString($_POST['numint'], "text"),
                       GetSQLValueString($_POST['numext'], "text"),
                       GetSQLValueString($_POST['colonia'], "text"),
                       GetSQLValueString($_POST['cp'], "text"),
                       GetSQLValueString($_POST['ciudad'], "text"),
                       GetSQLValueString($_POST['estado'], "text"),
                       GetSQLValueString($fechanacimiento, "date"),
                       GetSQLValueString($_POST['sexo'], "text"),
                       GetSQLValueString($_POST['ecivil'], "text"),
                       GetSQLValueString($_POST['nacionalidad'], "text"),
                       GetSQLValueString($_POST['nafiliacion'], "text"),
                       GetSQLValueString($_POST['salariofv'], "text"),
                       GetSQLValueString($_POST['contrato'], "text"),
                       GetSQLValueString($_POST['nomina'], "text"),
                       GetSQLValueString($_POST['jornada'], "text"),
					   GetSQLValueString($_POST['de_hrs'], "text"),
					   GetSQLValueString($_POST['a_hrs'], "text"),
                       GetSQLValueString($_POST['formapago'], "text"),
                       GetSQLValueString($_POST['ncuenta'], "text"),
                       GetSQLValueString($_POST['idmovimiento'], "text"),
                       GetSQLValueString($_POST['clabe'], "text"),
                       GetSQLValueString($_POST['escolaridad'], "text"),
                       GetSQLValueString($_POST['nafiliacionissste'], "text"),
                       GetSQLValueString($_POST['oficinadepago'], "text"),
                       GetSQLValueString($_POST['cartillaSMN'], "text"),
                       GetSQLValueString($_POST['idnominaemp'], "int"));
  //mysql_select_db($database_conexion, $conexion);
  $Result1 = mysqli_query( $conexion, $updateSQL) or die(mysqli_error());

  if($Result1)
  {  
		$idempleado = $_POST['idnominaemp'];
		
		//include("conceptosbasicos_md.php");
	  
		echo "<script>";
		echo "parent.document.afectacion.submit();";
		echo "</script>";
  }else{
		echo "<script>";
		echo "alet('No fue posible modificar los datos del empleado, consulte al administrador del sistema');";
		echo "</script>";
  }
}

//mysql_select_db($database_conexion, $conexion);
$query_areas = "SELECT * FROM cat_area order by descripcion";
$areas = mysqli_query( $conexion,$query_areas ) or die(mysqli_error());
$row_areas = mysqli_fetch_assoc($areas);
$totalRows_areas = mysqli_num_rows($areas);

//mysql_select_db($database_conexion, $conexion);
$query_programas = "SELECT * FROM cat_programa order by descripcion";
$programas = mysqli_query( $conexion, $query_programas) or die(mysqli_error());
$row_programas = mysqli_fetch_assoc($programas);
$totalRows_programas = mysqli_num_rows($programas);

//mysql_select_db($database_conexion, $conexion);
$query_categorias = "SELECT idcategoria, nivel, clave, descripcion, (sueldobase+hom) as sueldobase FROM cat_categoria order by descripcion";
$categorias = mysqli_query($conexion,$query_categorias ) or die(mysqli_error());
$row_categorias = mysqli_fetch_assoc($categorias);
$totalRows_categorias = mysqli_num_rows($categorias);

//mysql_select_db($database_conexion, $conexion);
$query_subprograma = "SELECT * FROM cat_subprograma order by descripcion";
$subprograma = mysqli_query($conexion, $query_subprograma ) or die(mysqli_error());
$row_subprograma = mysqli_fetch_assoc($subprograma);
$totalRows_subprograma = mysqli_num_rows($subprograma);

//mysql_select_db($database_conexion, $conexion);
$query_proyecto = "SELECT * FROM cat_proyecto order by descripcion";
$proyecto = mysqli_query( $conexion, $query_proyecto ) or die(mysqli_error());
$row_proyecto = mysqli_fetch_assoc($proyecto);
$totalRows_proyecto = mysqli_num_rows($proyecto);

$colname_empleados = "-1";
if (isset($_GET['idnominaemp'])) {
  $colname_empleados = $_GET['idnominaemp'];
}
//mysql_select_db($database_conexion, $conexion);
$query_empleados = sprintf("SELECT idnominaemp, paterno, materno, nombres, calle, numint, numext, colonia, cp, ciudad, estado, rfc_iniciales, rfc_fechanac, rfc_homoclave, curp, fechaingr, nafiliacion, salariofv, contrato, nomina, jornada, de_hrs, a_hrs, formapago, ncuenta, estatus, fechainicio, fechabaja, fechasistema, usuario, clabe, idbancos, fechanacimiento, sexo, ecivil, escolaridad, nafiliacionissste, oficinadepago, cartillaSMN, nacionalidad, folio, sueldobase FROM nominaemp WHERE idnominaemp = %s", GetSQLValueString($colname_empleados, "int"));
$empleados = mysqli_query( $conexion, $query_empleados ) or die(mysqli_error());
$row_empleados = mysqli_fetch_assoc($empleados);
$totalRows_empleados = mysqli_num_rows($empleados);

//mysql_select_db($database_conexion, $conexion);
$query_estados = "SELECT * FROM cat_estados ORDER BY estado ASC";
$estados = mysqli_query($conexion, $query_estados ) or die(mysqli_error());
$row_estados = mysqli_fetch_assoc($estados);
$totalRows_estados = mysqli_num_rows($estados);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link type="image/x-icon" href="imagenes/logomich200.ico" rel="icon" />
<link type="image/x-icon" href="imagenes/logomich200.ico" rel="shortcut icon" />
<title>Sistema de N&oacute;mina de Empleados</title>
<link rel="stylesheet" type="text/css" href="css/estilos.css">
<link rel="stylesheet" type="text/css" href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<script src="controles_jquery/js/jquery-1.9.1.js"></script>
<script src="controles_jquery/js/jquery-ui-1.10.3.custom.js"></script>


<script>

$(document).ready(function(){
	$(function() {
		$("#fechainicio").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		$("#fechainicio").datepicker( "option", "showAnim", "show");
		$("#fechainicio").datepicker( "option", "dateFormat", "dd/mm/yy" );
	});
	
	$(function() {
		$("#fechaingr").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		$("#fechaingr").datepicker( "option", "showAnim", "show");
		$("#fechaingr").datepicker( "option", "dateFormat", "dd/mm/yy" );
	});
	
	$(function() {
		$("#fechanacimiento").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "-90:+0"
		});
		$("#fechanacimiento").datepicker( "option", "showAnim", "show");
		$("#fechanacimiento").datepicker( "option", "dateFormat", "dd/mm/yy" );
	});
	
});

</script>
	

<script>

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
	
	if(form.estado.value == "")
	{
		alert("Indique la entidad federativa del domicilio del empleado");
		form.estado.focus();
		return false;
	}
	
	if(form.ciudad.value == "")
	{
		alert("Indique la ciudad del domicilio del empleado");
		form.ciudad.focus();
		return false;
	}
	
	if(form.fechanacimiento.value == "")
	{
		alert("Indique la fecha de nacimiento del empleado");
		form.fechanacimiento.focus();
		return false;
	}

	
	if(form.fechainicio.value == "")
	{
		alert("Indique la fecha de inicio del empleado");
		form.fechainicio.focus();
		return false;
	}
	
	if(form.fechaingr.value == "")
	{
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


function cargasubprogramas(idprograma)
{
	Resultado = document.getElementById('ajax_subprogramas');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_subprogramas.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idprograma="+idprograma);
}

function cargaciudad(idestado)
{
	Resultado = document.getElementById('ajax_ciudad');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_municipios.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idestado="+idestado);
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
<body topmargin="0" leftmargin="0">
<div id="todo">
        <div id="centro_prin" style="width:620px; border:0;">
          <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
	<table align="left" border="0">
    <tr>
    	<td>
        	<input class="boton" type="button" name="guardar1" id="guardar1" value="GUARDAR" onClick="if(valida(this.form)) submit();">
        </td>
    </tr>
    <tr>
    	<td>
            <table align="center">
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">RFC:</label><br>
           
                	<input class="campo" type="text" name="rfc_iniciales" id="rfc_iniciales" value="<?php echo htmlentities($row_empleados['rfc_iniciales'], ENT_COMPAT, 'iso-8859-1'); ?>" size="3" maxlength="4" style="width:36px;" onKeyPress="return sololetras(this.form, event);" onKeyUp="if(this.value.length == 4) this.form.rfc_fechanac.focus();">
                    <input class="campo" type="text" name="rfc_fechanac" id="rfc_fechanac" value="<?php echo htmlentities($row_empleados['rfc_fechanac'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" maxlength="6" style="width:45px;" onKeyPress="return solonumeros(this.form, event);" onKeyUp="if(this.value.length == 6) this.form.rfc_homoclave.focus();">
                    <input class="campo" type="text" name="rfc_homoclave" id="rfc_homoclave" value="<?php echo htmlentities($row_empleados['rfc_homoclave'], ENT_COMPAT, 'iso-8859-1'); ?>" size="2" maxlength="3" style="width:23px;" onKeyUp="if(this.value.length == 3) this.form.curp.focus();">
                    <label class="label">*</label>
                </td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">CURP:</label><br>
                <input class="campo" type="text" name="curp" value="<?php echo htmlentities($row_empleados['curp'], ENT_COMPAT, 'iso-8859-1'); ?>" size="18" maxlength="22"><label class="label">*</label></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Folio:</label><br>
                <input class="campo" type="text" name="folio" value="<?php echo htmlentities($row_empleados['folio'], ENT_COMPAT, 'iso-8859-1'); ?>" size="10" maxlength="10"></td>
              </tr>
              
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Tipo de contratacion:</label><br>
                <select name="contrato" class="lista">
                   <option value="<? echo $row_empleados['contrato']?>">
                   	<? echo $row_empleados['contrato']?>
                   </option>
                  <option value="EVENTUAL">EVENTUAL</option>
                  <option value="CONFIANZA">CONFIANZA</option>
                   <option value="BASE">BASE</option>
                </select></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Fecha de inicio:</label><br>
                <input class="campo" type="text" name="fechainicio" id="fechainicio" value="" size="10" maxlength="10"><label class="label">*</label></td>
              </tr>
              <? @list($year, $mes, $dia) = split('[-]', $row_empleados["fechainicio"]); $fecha = "$dia/$mes/$year"; ?>
              <script>
				$(document).ready(function(){
					$(function() {
						$("#fechainicio").datepicker( "setDate", "<?php echo htmlentities($fecha, ENT_COMPAT, 'iso-8859-1'); ?>" );
					});
				});
			  </script>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Fecha de ingreso:</label><br>
                <input class="campo" type="text" name="fechaingr" disabled id="fechaingr" value="" size="10" maxlength="10"><label class="label">*</label></td>
              </tr>
              <? @list($year, $mes, $dia) = split('[-]', $row_empleados["fechaingr"]); $fecha = "$dia/$mes/$year"; ?>
              <script>
				$(document).ready(function(){
					$(function() {
						$("#fechaingr").datepicker( "setDate", "<?php echo htmlentities($fecha, ENT_COMPAT, 'iso-8859-1'); ?>" );
					});
				});
			  </script>
              </table>
    	</td>
    	<td>
              <table align="center" border="0">
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Apellido Paterno:</label><br>
                <input class="campo" type="text" name="paterno" value="<?php echo htmlentities($row_empleados['paterno'], ENT_COMPAT, 'iso-8859-1'); ?>" size="20" maxlength="55"><label class="label">*</label></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Apellido Materno:</label><br>
                <input class="campo" type="text" name="materno" value="<?php echo htmlentities($row_empleados['materno'], ENT_COMPAT, 'iso-8859-1'); ?>" size="20" maxlength="55"><label class="label">*</label></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Nombre(s):</label><br>
                <input class="campo" type="text" name="nombres" value="<?php echo htmlentities($row_empleados['nombres'], ENT_COMPAT, 'iso-8859-1'); ?>" size="20" maxlength="55"><label class="label">*</label></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Calle:</label><br>
                <input class="campo" type="text" name="calle" value="<?php echo htmlentities($row_empleados['calle'], ENT_COMPAT, 'iso-8859-1'); ?>" size="20" maxlength="55"><label class="label">*</label></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Num. int:</label><br>
                <input class="campo" type="text" name="numint" value="<?php echo htmlentities($row_empleados['numint'], ENT_COMPAT, 'iso-8859-1'); ?>" size="7" maxlength="7"><label class="label">*</label></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Num. ext:</label><br>
                <input class="campo" type="text" name="numext" value="<?php echo htmlentities($row_empleados['numext'], ENT_COMPAT, 'iso-8859-1'); ?>" size="7" maxlength="7"><label class="label">*</label></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Colonia:</label><br>
                <input class="campo" type="text" name="colonia" value="<?php echo htmlentities($row_empleados['colonia'], ENT_COMPAT, 'iso-8859-1'); ?>" size="20" maxlength="60"><label class="label">*</label></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Cp:</label><br>
                <input class="campo" type="text" name="cp" value="<?php echo htmlentities($row_empleados['cp'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" maxlength="5"></td>
              </tr>
              <tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Estado:</label><br>
                <select name="estado" class="lista" onChange="cargaciudad(this.value);" style="width:200px;">
                <option value="">Seleccione</option>
                  <?php 
do {  
?>
                  <option value="<?php echo $row_estados['idestados']?>" <?php if (!(strcmp($row_estados['idestados'], htmlentities($row_empleados['estado'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>><?php echo $row_estados['estado']?></option>
                  <?php
} while ($row_estados = mysqli_fetch_assoc($estados));
?>
                </select><label class="label">*</label></td>
              <tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Ciudad:</label><br>
               
				  <div id="ajax_ciudad"></div>
				</td>
              <tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Fecha de nacimiento:</label><br>
                <input class="campo" type="text" name="fechanacimiento" id="fechanacimiento" value="" size="10" maxlength="10"><label class="label">*</label></td>
              </tr>
              <? @list($year, $mes, $dia) = split('[-]', $row_empleados["fechanacimiento"]); $fecha = "$dia/$mes/$year"; ?>
              <script>
				$(document).ready(function(){
					$(function() {
						$("#fechanacimiento").datepicker( "setDate", "<?php echo htmlentities($fecha, ENT_COMPAT, 'iso-8859-1'); ?>" );
					});
				});
			  </script>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Sexo:</label><br>
                <select name="sexo" class="lista">
                  <option value="M" <?php if (!(strcmp("M", htmlentities($row_empleados['sexo'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Masculino</option>
                  <option value="F" <?php if (!(strcmp("F", htmlentities($row_empleados['sexo'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Femenino</option>
                </select></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Estado civil:</label><br>
                <select name="ecivil" class="lista">
                  <option value="1" <?php if (!(strcmp("1", htmlentities($row_empleados['ecivil'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Soltero(a)</option>
                  <option value="2" <?php if (!(strcmp("2", htmlentities($row_empleados['ecivil'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Casado(a)</option>
                  <option value="3" <?php if (!(strcmp("3", htmlentities($row_empleados['ecivil'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Divorciado(a)</option>
                  <option value="4" <?php if (!(strcmp("4", htmlentities($row_empleados['ecivil'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Viudo(a)</option>
                  <option value="5" <?php if (!(strcmp("5", htmlentities($row_empleados['ecivil'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Union libre(a)</option>
                </select></td>
              </tr>
              </table>
    	</td>
    	<td valign="top">
              <table align="center" border="0">
              <tr valign="baseline">
                <td nowrap align="left" colspan="2"><label class="label">Nacionalidad:</label><br>
                <select name="nacionalidad" class="lista">
                  <option value="1" <?php if (!(strcmp("1", htmlentities($row_empleados['nacionalidad'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Nacional</option>
                  <option value="2" <?php if (!(strcmp("2", htmlentities($row_empleados['nacionalidad'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Extranjera</option>
                </select></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left" colspan="2"><label class="label">Afiliación IMSS:</label><br>
                <input class="campo" type="text" name="nafiliacion" value="<?php echo htmlentities($row_empleados['nafiliacion'], ENT_COMPAT, 'iso-8859-1'); ?>" size="10" maxlength="20"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left" colspan="2"><label class="label">Salario:</label><br>
                <select name="salariofv" class="lista">
                  <option value="F" <?php if (!(strcmp("F", htmlentities($row_empleados['salariofv'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Fijo</option>
                  <option value="V" <?php if (!(strcmp("V", htmlentities($row_empleados['salariofv'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Variable</option>
                </select></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left" colspan="2"><label class="label">Nómina:</label><br>
                <select name="nomina" class="lista">
                  <option value="Q" <?php if (!(strcmp("Q", htmlentities($row_empleados['nomina'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Quincenal</option>
                  <option value="S" <?php if (!(strcmp("S", htmlentities($row_empleados['nomina'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Semanal</option>
                </select></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left" colspan="2"><label class="label">Jornada:</label><br>
                <select name="jornada" class="lista">
                  <option value="1" <?php if (!(strcmp("D", htmlentities($row_empleados['jornada'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Diurna</option>
                  <option value="2" <?php if (!(strcmp("N", htmlentities($row_empleados['jornada'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Nocturna</option>
                  <option value="3" <?php if (!(strcmp("M", htmlentities($row_empleados['jornada'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Mixta</option>
                  <option value="4" <?php if (!(strcmp("E", htmlentities($row_empleados['jornada'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Especial</option>
                </select></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">De:</label>
                <td>
                	<input class="campo" type="text" name="de_hrs" value="<?php echo htmlentities($row_empleados['de_hrs'], ENT_COMPAT, 'iso-8859-1'); ?>" size="2" maxlength="2" onKeyPress="return solonumeros(this.form, event);" style="width:15px;">
                    <label class="label">a</label>
                    <input class="campo" type="text" name="a_hrs" value="<?php echo htmlentities($row_empleados['a_hrs'], ENT_COMPAT, 'iso-8859-1'); ?>" size="2" maxlength="2" onKeyPress="return solonumeros(this.form, event);" style="width:15px;">
                	<label class="label">hrs.</label>
                    <label class="label">*</label>
                 </td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left" colspan="2"><label class="label">Forma de pago:</label><br>
                <select name="formapago" class="lista">
                  <option value="DE" <?php if (!(strcmp("DE", htmlentities($row_empleados['formapago'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Depósito</option>
                  <option value="CH" <?php if (!(strcmp("CH", htmlentities($row_empleados['formapago'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Cheque</option>
                </select></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left" colspan="2"><label class="label">Núm. de cuenta:</label><br>
                <input class="campo" type="text" name="ncuenta" value="<?php echo htmlentities($row_empleados['ncuenta'], ENT_COMPAT, 'iso-8859-1'); ?>" size="13" maxlength="16"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left" colspan="2"><label class="label">CLABE:</label><br>
                <input class="campo" type="text" name="clabe" value="<?php echo htmlentities($row_empleados['clabe'], ENT_COMPAT, 'iso-8859-1'); ?>" size="13" maxlength="25"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left" colspan="2"><label class="label">Escolaridad:</label><br>
                <select name="escolaridad" class="lista">
                  <option value="P" <?php if (!(strcmp("P", htmlentities($row_empleados['escolaridad'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Primaria</option>
                  <option value="S" <?php if (!(strcmp("S", htmlentities($row_empleados['escolaridad'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Secundaria</option>
                  <option value="B" <?php if (!(strcmp("B", htmlentities($row_empleados['escolaridad'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Bachillerato</option>
                  <option value="L" <?php if (!(strcmp("L", htmlentities($row_empleados['escolaridad'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Licenciatura</option>
                  <option value="O" <?php if (!(strcmp("O", htmlentities($row_empleados['escolaridad'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>>Posgrado</option>
                </select></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left" colspan="2"><label class="label">Afiliación ISSSTE:</label><br>
                <input class="campo" type="text" name="nafiliacionissste" value="<?php echo htmlentities($row_empleados['nafiliacionissste'], ENT_COMPAT, 'iso-8859-1'); ?>" size="13" maxlength="15"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left" colspan="2"><label class="label">Oficina de pago:</label><br>
                <input class="campo" type="text" name="oficinadepago" value="<?php echo htmlentities($row_empleados['oficinadepago'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" maxlength="5"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left" colspan="2"><label class="label">Cartilla SMN:</label><br>
                <input class="campo" type="text" name="cartillaSMN" value="<?php echo htmlentities($row_empleados['cartillaSMN'], ENT_COMPAT, 'iso-8859-1'); ?>" size="5" maxlength="5"></td>
              </tr>
              <tr valign="baseline">
                <td nowrap align="left"><label class="label">Tipo de Recurso:</label><br></td>
                <td>
                	<select name="trecurso" class="lista">
                    	<option value="<? echo $row_empleados['trecurso']?>"><? echo $row_empleados['trecurso']?></option>
                		<option value="FEDERAL">Federal</option>
                        <option value="PROPIO">Propio</option>
                        <option value="MIXTO">Mixto</option>
                    </select>
                	<label class="label">*</label></td>
              </tr>
            </table>
    	</td>
    </tr>
    <tr>
    	<td colspan="6">
<!--            <input class="boton" type="button" name="regresar" id="regresar" value="REGRESAR" onClick="location.replace('empleados.php');">-->
            <input class="boton" type="button" name="guardar" id="guardar" value="GUARDAR" onClick="if(valida(this.form)) submit();">
        </td>
    </tr>
	</table>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="idnominaemp" value="<?php echo $row_empleados['idnominaemp']; ?>">
            <input type="hidden" name="idmovimiento" value="<?php echo $_GET['movimiento']; ?>">
          </form>
          <p>&nbsp;</p>
<!--        </div>
    </div>
    <div id="tituloabajo">
   		<div id="tituloinf">Empleados</div>  -->  
    </div>
</div>
</body>
</html>

<script>

/*cargasubprogramas('<? echo $row_empleados['programa']; ?>');
form1.subprograma.value = '<? echo $row_empleados['subprograma']; ?>';*/

cargaciudad('<? echo $row_empleados['estado']; ?>');
form1.ciudad.value = '<? echo $row_empleados['ciudad']; ?>';

cargasueldo('<? echo $row_empleados['categoria']; ?>', '<? echo $row_empleados['idnominaemp']; ?>');



</script>

<?php
mysqli_free_result($areas);

mysqli_free_result($programas);

mysqli_free_result($categorias);

mysqli_free_result($subprograma);

mysqli_free_result($proyecto);

mysqli_free_result($empleados);

mysqli_free_result($estados);
?>

