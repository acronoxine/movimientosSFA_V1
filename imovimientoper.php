<?php
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

//---------------------- ASIGNAR LA PLAZA -----------------
if(isset($_GET['idnominaemp']) &&isset($_GET['tipoContrato'])&& $_GET['estado']=="A"){
	$update_sql="update empleado_plaza set idnominaemp=$_GET[idnominaemp],fecha_inicial='$_GET[fecha]', 
	 			fecha_final='$_GET[fecha2]',asignacion='$_GET[asignacion]', estado='OCUPADO' where plaza_id=$_GET[plaza]";
	mysql_query($update_sql,$conexion);	
	$update_empleado="update nominaemp set activo=1, estatus=$_GET[tipoContrato] where idnominaemp=$_GET[idnominaemp]";
	mysql_query($update_empleado,$conexion);
}
//--------------------- LIBERA LA PLAZA --------------------------
if(isset($_GET['estado']) && $_GET['estado']!="A"){
	$update_sql="update empleado_plaza set fecha_inicial='$_GET[fecha]', 
	 			fecha_final=NULL where plaza_id=$_GET[plaza]";
	mysql_query($update_sql,$conexion);	
	$update_empleado="update nominaemp set activo=0, estatus=$_GET[tipoContrato] where idnominaemp=$_GET[idnominaemp]";
	mysql_query($update_empleado,$conexion);
	$queryVacante="update empleado_plaza set idnominaemp=0, estado='VACANTE' where idnominaemp=$_GET[idnominaemp] AND plaza_id=$_GET[plaza]";
}
if(isset($_POST["movimiento"]))
{
	/*$sql = "Select * from cat_movimientos where idmovimiento = '$_POST[movimiento]'";
	$res_mov = mysql_query($sql, $conexion);
	if(mysql_num_rows($res_mov) > 0)
	{
		$ren_mov = mysql_fetch_array($res_mov);
		
		$borrafecha = "";
		if($ren_mov["activo"] == 1)
		{
			$borrafecha = ", fechabaja = null";
		}
		
		$sql = "Update nominaemp set estatus = '$_POST[movimiento]', activo = '$ren_mov[activo]'$borrafecha where idnominaemp = '$_POST[idnominaemp]'";
		$res = mysql_query($sql, $conexion);
		
		if($res)
		{
			$sql = "insert into movimientoshist(idnominaemp, paterno, materno, nombres, calle, numint, numext, colonia, cp, ciudad, estado, rfc_iniciales, rfc_fechanac, rfc_homoclave, curp, area, programa, subprograma, proyecto, categoria, plaza, fechaingr, nafiliacion, salariofv, estatus, contrato, nomina, jornada, de_hrs, a_hrs, formapago, ncuenta, fechainicio, fechabaja, fechasistema, usuario, clabe, idbancos, fechanacimiento, sexo, ecivil, escolaridad, nafiliacionissste, oficinadepago, cartillaSMN, nacionalidad, folio, sueldobase, activo)";
			$sql .= " select * from nominaemp where idnominaemp = '$_POST[idnominaemp]';";
			mysql_query($sql, $conexion);
		}
	}*/
	
	$_GET["idnominaemp"] = $_POST["idnominaemp"];
}
?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">

<link rel="stylesheet" type="text/css" href="css/estilos.css">
<link rel="stylesheet" type="text/css" href="controles_jquery/css/overcast/jquery-ui-1.10.3.custom.css">
<link rel="stylesheet" type="text/css" href="controles_jquery/js/jquery.fancybox.css">
<script src="controles_jquery/js/jquery-1.9.1.js"></script>
<script src="controles_jquery/js/jquery-ui-1.10.3.custom.js"></script>
<script src="controles_jquery/js/jquery.fancybox.js"></script>

<script type="text/javascript">
function  cierre(){
	parent.location.reload();
	parent.$.fancybox.close();
}
</script>

<script type="text/javascript"> 
$(document).ready(function(){
	
	function formato(datos)
	{
		$.fancybox({
			'href'				: 'empleados_md.php' + datos,
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'width'				: 700,
			'height'			: 500,
			'modal'				: false,
			'type'				: 'iframe'
		});
	}
	
	function fechabaja(datos)
	{
		$.fancybox({
			'href'				: 'fechabaja.php' + datos,
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'width'				: 400,
			'height'			: 336,
			'modal'				: false,
			'type'				: 'iframe'
		});
	}
	
	function fechaingreso(datos)
	{
		$.fancybox({
			'href'				: 'fechaingreso.php' + datos,
			'autoScale'			: false,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'width'				: 430,
			'height'			: 250,
			'modal'				: false,
			'type'				: 'iframe'
		});
	}
	
	$("#movimiento").change(function() {
		if(document.getElementById('movimiento').value >= 14)
		{
			formato("?idnominaemp=" + document.getElementById('idnominaemp').value+ "&movimiento="+document.getElementById('movimiento').value);
		}
		
		if(document.getElementById('movimiento').value >= 7 && document.getElementById('movimiento').value <= 13)
		{
			fechabaja("?idnominaemp=" + document.getElementById('idnominaemp').value + "&movimiento="+document.getElementById('movimiento').value);
		}
		
		if(document.getElementById('movimiento').value <= 6)
		{
			fechaingreso("?idnominaemp=" + document.getElementById('idnominaemp').value + "&movimiento="+document.getElementById('movimiento').value);
		}
	});
		
});
</script>

</head>
<body>
<iframe name="formato" id="formato" src="pdfmovimiento.php?qvacante=<? echo $queryVacante?>&idnominaemp=<? echo $_GET["idnominaemp"]; ?> &ver=<? echo $_GET[ver]?>" style="width:950px; height:600px;"></iframe>
<form name="afectacion" method="post" action="imovimientoper.php">
<table width="100%" border="0">
  <tr>
    <td>
        <label class="label">Movimiento:</label><br>
        <select name="movimiento" id="movimiento" class="lista">
        <option value="">Seleccione</option>
        <?
            $sql = "Select * from cat_movimientos order by tipo,descripcion";
            $res = mysql_query($sql, $conexion);
            $tipo="Alta";
			echo '\n<option value=" " disabled style="text-align:center">'.$tipo.'</option>';
            while($ren = mysql_fetch_array($res))
            {
				if($ren[tipo]==$tipo){
                	echo "\n<option value='$ren[idmovimiento]'>$ren[clave] $ren[descripcion]</option>";
				}
				else{
					echo '\n<option value=" " disabled style="text-align:center">'.$ren[tipo].'</option>';
					echo "\n<option value='$ren[idmovimiento]'>$ren[clave] $ren[descripcion]</option>";
					$tipo=$ren[tipo];
				}
			}
        ?>
        </select>
        <input class="boton2" type="button" name="afectar" id="afectar" value="Recargar PDF" onClick="(submit())">
    </td>
    <td>&nbsp;</td>
    <td align="right">
		<input class="boton" type="button" name="cerrar" id="cerrar" value="CERRAR" onClick="cierre()">
    </td>
  </tr>
</table>
<input type="hidden" name="idnominaemp" id="idnominaemp" value="<? echo $_GET["idnominaemp"]; ?>">
</form>
</body>
</html>
