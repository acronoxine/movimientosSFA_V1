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

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

if ((isset($_GET['idnominaemp'])) && ($_GET['idnominaemp'] != "")) {
  $deleteSQL = sprintf("DELETE FROM nominaemp WHERE idnominaemp=%s",
                       GetSQLValueString($_GET['idnominaemp'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	$sql = "Select curp,rfc_iniciales,rfc_fechanac,rfc_fechanac,rfc_homoclave from nominaemp where rfc_iniciales = '$_POST[rfc_iniciales]' and rfc_fechanac = '$_POST[rfc_fechanac]' and rfc_homoclave = '$_POST[rfc_homoclave]'";
	$res = mysql_query($sql, $conexion);
	print_r($res);
	if(mysql_num_rows($res) == false)
	{
		@list($dia, $mes, $year) = split('[-./]', $_POST["fechainicio"]);
		$fechainicio = "$year-$mes-$dia";
		
		@list($dia, $mes, $year) = split('[-./]', $_POST["fechaingr"]);
		$fechaingr = "$year-$mes-$dia";
		
		@list($dia, $mes, $year) = split('[-./]', $_POST["fechanacimiento"]);
		$fechanacimiento = "$year-$mes-$dia";
		
		
		$insertSQL = sprintf("INSERT INTO nominaemp (
				rfc_iniciales, 
				rfc_fechanac, 
				rfc_homoclave, 
				curp, 
				folio, 
				sueldobase, 
				fechainicio, 
				fechaingr, 
				paterno, 
				materno, 
				nombres, 
				calle, 
				numint, 
				numext, 
				colonia, 
				cp, 
				ciudad, 
				estado, 
				fechanacimiento, 
				sexo, 
				ecivil, 
				nacionalidad, 
				nafiliacion, 
				salariofv, 
				contrato, 
				nomina, 
				jornada, 
				de_hrs, 
				a_hrs, 
				formapago, 
				ncuenta, 
				estatus, 
				clabe, 
				escolaridad, 
				nafiliacionissste, 
				oficinadepago, 
				cartillaSMN, 
				idbancos, 
				activo) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['rfc_iniciales'], "text"),
						   GetSQLValueString($_POST['rfc_fechanac'], "text"),
						   GetSQLValueString($_POST['rfc_homoclave'], "text"),
						   GetSQLValueString($_POST['curp'], "text"),
						   GetSQLValueString($_POST['folio'], "text"),
						   GetSQLValueString($_POST['sueldobase'], "double"),
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
						   GetSQLValueString(1, "text"),
						   GetSQLValueString($_POST['clabe'], "text"),
						   GetSQLValueString($_POST['escolaridad'], "text"),
						   GetSQLValueString($_POST['nafiliacionissste'], "text"),
						   GetSQLValueString($_POST['oficinadepago'], "text"),
						   GetSQLValueString($_POST['cartillaSMN'], "text"),
						   GetSQLValueString($_SESSION['m_banco'], "text"),
						   GetSQLValueString($_POST['trecurso'], "text"),
						   GetSQLValueString(1, "int"));
		
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
		 ;
		if($Result1)
		{
		  
		$idempleado = mysql_insert_id();
		$insert_plazaEmp=sprintf("INSERT INTO empleado_plaza (idnominaemp,plaza_id,fecha_inicial,fecha_final,estado) 		 									VALUES(%s,%s,%s,%s,%s)",
		GetSQLValueString($idempleado, "int"),
		GetSQLValueString($_POST['plaza'], "int"),
		GetSQLValueString($_POST['fechaingr'], "text"),
		GetSQLValueString($_POST['fechafin'], "text"),
		GetSQLValueString('OCUPADO', "text"));
		  include("conceptosbasicos.php");
			
		  echo "<script>";
		  echo "parent.document.form1.reset();";
		  echo "</script>";
		}else{
		  echo "<script>";
		  echo "alet('No fue posible ingresar al empleado, consulte al administrador del sistema');";
		  echo "</script>";
		}
	}else{
		echo "<script>";
		echo "alert('El empleado que desea registrar ya existe!');";
		echo "</script>";
	}
}

mysql_select_db($database_conexion, $conexion);
$query_empleado = "SELECT pz.plaza_clave,n.idnominaemp, case when n.activo = '1' then 'SI' else 'NO' end as activo, concat(ifnull(rfc_iniciales, ''), ifnull(rfc_fechanac, ''), ifnull(rfc_homoclave, '')) as rfc, curp, folio, paterno, materno, nombres, calle, numint, numext, colonia, cp, mun.municipio as ciudad, est.estado";
$query_empleado .= " , curp, a.descripcion as area, c.descripcion as categoria, n.sueldobase, concat(lpad(day(fechanacimiento), 2, '0'), '/', lpad(month(fechanacimiento), 2, '0'), '/', year(fechanacimiento)) as fechanacimiento, case when sexo = 'M' then 'MASCULINO' when sexo = 'F' then 'FEMENINO' else '' end as sexo,";
$query_empleado .= " case when ecivil = '1' then 'SOLTERO' when ecivil = '2' then 'CASADO' when ecivil = '3' then 'DIVORCIADO' when ecivil = '4' then 'VIUDO' when ecivil = '5' then 'UNION LIBRE' else '' end as ecivil, p.descripcion as programa, s.descripcion as subprograma,";
$query_empleado .= " concat(lpad(day(fechaingr), 2, '0'), '/', lpad(month(fechaingr), 2, '0'), '/', year(fechaingr)) as fechaingr,";
$query_empleado .= "  case when escolaridad = 'P' then 'PRIMARIA' when escolaridad = 'S' then 'SECUNDARIA' when escolaridad = 'B' then 'BACHILLERATO' when escolaridad = 'L' then 'LICENCIATURA' when escolaridad = 'I' then 'INGENIER�A' when escolaridad = 'M' then 'MAESTR�A' when escolaridad = 'D' then 'DOCTORADO' else '' end as escolaridad";
$query_empleado .= " , case when nacionalidad = '1' then 'NACIONAL' when nacionalidad = '2' then 'EXTRANJERA' else '' end as nacionalidad, nafiliacionissste, clabe, oficinadepago, cartillaSMN";
$query_empleado .= " , nafiliacion, ";
$query_empleado .= " case when salariofv = 'F' then 'FIJO' else 'VARIABLE' end as salariofv, ";
$query_empleado .= " case when contrato = 'P' then 'PERMANENTE' when contrato = 'A' then 'ASIMILADO' when contrato = '11' then 'EVENTUAL' end as contrato";
$query_empleado .= " , case when nomina = 'Q' then 'QUINCENAL' else 'SEMANAL' end as nomina, case when jornada = '1' then 'DIURNA' when jornada = '2' then 'NOCTURNA' when jornada = '3' then 'MIXTA' when jornada = '4' then 'ESPECIAL' else '' end as jornada, ";
$query_empleado .= " case when formapago = 'DP' then 'DEPOSITO' else 'CHEQUE' end as formapago , ncuenta, ";
$query_empleado .= " mov.descripcion as estatus";
$query_empleado .= " , concat(lpad(day(fechainicio), 2, '0'), '/', lpad(month(fechainicio), 2, '0'), '/', year(fechainicio)) as fechainicio, fechabaja, clabe";

$query_empleado .= " , b.banco FROM nominaemp n"; 
$query_empleado .= " LEFT JOIN empleado_plaza epz ON n.idnominaemp = epz.idnominaemp ";
$query_empleado .= " LEFT JOIN cat_plazas pz ON epz.plaza_id=pz.plaza_id";
$query_empleado .= " left join cat_programa p ON pz.programa = p.idprograma"; 
$query_empleado .= " left join cat_area a ON p.idarea = a.idarea "; 
$query_empleado .= " LEFT JOIN cat_subprograma s ON pz.subprograma = s.idsubprograma "; 
$query_empleado .= " LEFT JOIN cat_categoria c ON pz.categoria=c.clave";
$query_empleado .= " left join bancos b on n.idbancos = b.idbancos"; 
$query_empleado .= " left join cat_estados est on n.estado = est.idestados"; 
$query_empleado .= " left join cat_municipios mun on n.ciudad = mun.idmunicipios"; 
$query_empleado .= " left join cat_movimientos mov on n.estatus = mov.idmovimiento"; 
$query_empleado .= " where n.idnominaemp!=0"; 

if(isset($_GET["consulta"]))
{
	$query_empleado .= " and concat(n.paterno, ' ', n.materno, ' ', n.nombres) like '%$_GET[consulta]%'";
}

$query_empleado .= " order by n.idnominaemp";
$empleados = mysql_query($query_empleado, $conexion) or die(mysql_error());
$row_empleados = mysql_fetch_assoc($empleados);
$totalRows_empleados = mysql_num_rows($empleados);
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/estilos.css">

<style>
#encabezado {
	position: fixed;
	top: 0px;
}
</style>

<link rel="stylesheet" type="text/css" href="css/estilos.css">
<link rel="stylesheet" type="text/css"
	href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<link rel="stylesheet" type="text/css"
	href="controles_jquery/js/jquery.fancybox.css">
<script src="controles_jquery/js/jquery-1.9.1.js"></script>
<script src="controles_jquery/js/jquery-ui-1.10.3.custom.js"></script>
<script src="controles_jquery/js/jquery.fancybox.js"></script>

<script text="text/javascript">
	function loadDemo(){
			
		  // nice one David Walsh	
		  var demoTwo = new ScrollSpy({
			 min: 0, // acts as position-x: absolute; left: 50px;
			 mode: 'horizontal',
			 onEnter: function(position,enters) {
			
			 },
			 onLeave: function(position,leaves) {
			
			 },
			 onTick: function(position,state,enters,leaves) {
				$("encabezado").style.left = -position.x+"px";
			 },
			 container: window
			}); 

	}
</script>

<script>
x = jQuery.noConflict();
x(document).ready(function(){
	
/*<!--	function last_msg_funtion() 
	{ 
		var ID=x(".message_box:last").attr("id");
		x('#enespera').html('<img src="imagenes/loading.gif">');
		x.post("empleados_lista_sig.php?idnominaemp="+ID+"&consulta="+document.getElementById('consulta').value,
		
		function(data){
			if (data != "") {
			x(".message_box:last").after(data);			
			}
			x('#enespera').empty();
		});

	}; --> */
	
	x(window).scroll(function(){
		if  (x(window).scrollTop() == x(document).height() - x(window).height()){
		   last_msg_funtion();
		}
	}); 
	
});
</script>

<script>

function miseleccion(dato, obj)
{
	document.getElementById(obj).checked = true;
	parent.document.getElementById("_idnominaemp").value = dato;
}

</script>

</head>
<body topmargin="0" leftmargin="0">
	<form>
		<div id="encabezado">
			<table class="tablagrid" border="0" cellpadding="0" cellspacing="0"
				width="6471">
				<tr class="tablahead">
					<td width="54" align="center">&nbsp;</td>
					<td width="32" align="center">&nbsp;</td>
					<td width="119" align="center">ACTIVO</td>
					<td width="119" align="center">RFC</td>
					<td width="157" align="center">PATERNO</td>
					<td width="158" align="center">MATERNO</td>
					<td width="164" align="center">NOMBRE(S)</td>
					<td width="135" align="center">CURP</td>
					<td width="129" align="center">FOLIO</td>
					<td width="133" align="center">UR</td>
					<td width="170" align="center">PROGRAMA</td>
					<td width="165" align="center">PROYECTO</td>
					<td width="169" align="center">CATEGOR�A</td>
					<td width="184" align="center">SUELDO BASE</td>
					<td width="90" align="center">PLAZA</td>
					<td width="116" align="center">FECHA DE INCIO</td>
					<td width="112" align="center">FECHA DE INGR.</td>
					<td width="284" align="center">CALLE</td>
					<td width="97" align="center">NUM. INT.</td>
					<td width="101" align="center">NUM. EXT.</td>
					<td width="297" align="center">COLONIA</td>
					<td width="78" align="center">CP</td>
					<td width="290" align="center">CIUDAD</td>
					<td width="305" align="center">ESTADO</td>
					<td width="145" align="center">FECHA DE NAC.</td>
					<td width="90" align="center">SEXO</td>
					<td width="90" align="center">EDO. CIVIL</td>
					<td width="126" align="center">NACIONALIDAD</td>
					<td width="114" align="center">IMSS</td>
					<td width="108" align="center">SALARIO</td>
					<td width="106" align="center">CONTRATO</td>
					<td width="100" align="center">N�MINA</td>
					<td width="102" align="center">JORNADA</td>
					<td width="205" align="center">HORARIO</td>
					<td width="118" align="center">FORMA DE PAGO</td>
					<td width="105" align="center">N�M. DE CUENTA</td>
					<td width="102" align="center">ESTATUS</td>
					<td width="211" align="center">CLABE BANCARIA</td>
					<td width="213" align="center">BANCO</td>
					<td width="123" align="center">ESCOLARIDAD</td>
					<td width="142" align="center">ISSSTE</td>
					<td width="135" align="center">OFICINA DE PAGO</td>
					<td width="119" align="center">CARTILLA SMN</td>
					<td width="119" align="center">RECURSO</td>
					<td align="center">FECHA DE BAJA</td>
				</tr>
			</table>
		</div>
		<table class="tablagrid" border="0" cellpadding="0" cellspacing="0" width="6471" style="padding-top: 32px;">
  		<?php do { ?>
    		<tr id="<? echo $row_empleados["idnominaemp"]; ?>"
				class="message_box tablaregistros"
				onClick="miseleccion('<?php echo $row_empleados['idnominaemp']; ?>', 'id_<?php echo $row_empleados['idnominaemp']; ?>')">
				<td width="54" height="64" align="center"><a
					onClick="if(confirm('�Confirma que desea eliminar el registro?')) location.href='empleados_lista.php?idnominaemp=<?php echo $row_empleados['idnominaemp']; ?>';"
					href="#"><img src="imagenes/borrar.png" width="34" height="34"></a></td>
				<td width="32" align="center"><input type="radio" name="seleccion"
					id="id_<?php echo $row_empleados['idnominaemp']; ?>"
					value="<?php echo $row_empleados['idnominaemp']; ?>"></td>
				<td width="119" align="center"><?php echo $row_empleados['activo']; ?></td>
				<td width="119" align="center"><?php echo $row_empleados['rfc']; ?></td>
				<td width="157" align="center"><?php echo $row_empleados['paterno']; ?></td>
				<td width="158" align="center"><?php echo $row_empleados['materno']; ?></td>
				<td width="164" align="center"><?php echo $row_empleados['nombres']; ?></td>
				<td width="135" align="center"><?php echo $row_empleados['curp']; ?></td>
				<td width="129" align="center"><?php echo $row_empleados['folio']; ?></td>
				<td width="133" align="center"><?php echo $row_empleados['area']; ?></td>
				<td width="170" align="center"><?php echo $row_empleados['programa']; ?></td>
				<td width="165" align="center"><?php echo $row_empleados['proyecto']; ?></td>
				<td width="169" align="center"><?php echo $row_empleados['categoria']; ?></td>
				<td width="184" align="right"><?php echo number_format($row_empleados['sueldobase'], 2, ".", ","); ?></td>
				<td width="90" align="center"><?php echo $row_empleados['plaza_clave']; ?></td>
				<td width="116" align="center"><?php echo $row_empleados['fechainicio']; ?></td>
				<td width="112" align="center"><?php echo $row_empleados['fechaingr']; ?></td>
				<td width="284"><?php echo $row_empleados['calle']; ?></td>
				<td width="97" align="center"><?php echo $row_empleados['numint']; ?></td>
				<td width="101" align="center"><?php echo $row_empleados['numext']; ?></td>
				<td width="297"><?php echo $row_empleados['colonia']; ?></td>
				<td width="78" align="center"><?php echo $row_empleados['cp']; ?></td>
				<td width="290" align="center"><?php echo $row_empleados['ciudad']; ?></td>
				<td width="305" align="center"><?php echo $row_empleados['estado']; ?></td>
				<td width="145" align="center"><?php echo $row_empleados['fechanacimiento']; ?></td>
				<td width="90" align="center"><?php echo $row_empleados['sexo']; ?></td>
				<td width="90" align="center"><?php echo $row_empleados['ecivil']; ?></td>
				<td width="126" align="center"><?php echo $row_empleados['nacionalidad']; ?></td>
				<td width="114" align="center"><?php echo $row_empleados['nafiliacion']; ?></td>
				<td width="108" align="center"><?php echo $row_empleados['salariofv']; ?></td>
				<td width="106" align="center"><?php echo $row_empleados['contrato']; ?></td>
				<td width="100" align="center"><?php echo $row_empleados['nomina']; ?></td>
				<td width="102" align="center"><?php echo $row_empleados['jornada']; ?></td>
				<td width="102" align="center"><?php echo $row_empleados['de_hrs']; ?></td>
				<td width="102" align="center"><?php echo $row_empleados['a_hrs']; ?></td>
				<td width="118" align="center"><?php echo $row_empleados['formapago']; ?></td>
				<td width="105" align="center"><?php echo $row_empleados['ncuenta']; ?></td>
				<td width="102" align="center"><?php echo $row_empleados['estatus']; ?></td>
				<td width="211" align="center"><?php echo $row_empleados['clabe']; ?></td>
				<td width="213" align="center"><?php echo $row_empleados['banco']; ?></td>
				<td width="123" align="center"><?php echo $row_empleados['escolaridad']; ?></td>
				<td width="142" align="center"><?php echo $row_empleados['nafiliacionissste']; ?></td>
				<td width="135" align="center"><?php echo $row_empleados['oficinadepago']; ?></td>
				<td width="119" align="center"><?php echo $row_empleados['cartillaSMN']; ?></td>
				<td width="119" align="center"><?php echo $row_empleados['trecurso']; ?></td>
				<td align="center"><?php echo $row_empleados['fechabaja']; ?></td>
			</tr>
    <?php } while ($row_empleados = mysql_fetch_assoc($empleados)); 

	?>
</table>

		<input type="hidden" name="consulta" id="consulta"
			value="<? if(isset($_GET["consulta"])) echo $_GET["consulta"]; ?>">
	</form>
</body>
</html>
<?php
mysql_free_result($empleados);
?>
