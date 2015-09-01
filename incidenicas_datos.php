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
	include 'funcionesJL.php';
?>
<?php

if(isset($_GET[empleado]))
	$empleado="AND a.idnominaemp like '%$_GET[empleado]%'";
else
	$empleado="";
	
if($_GET[fechai] && $_GET[fechaf]){
	$fiobtenida=explode("/",$_GET[fechai]);
	$ffobtenida=explode("/",$_GET[fechaf]);
	$fecha_inicial=$fiobtenida[2]."-".$fiobtenida[1]."-".$fiobtenida[0];
	$fecha_final=$ffobtenida[2]."-".$ffobtenida[1]."-".$ffobtenida[0];
	$fechas=" and fecha BETWEEN '$fecha_inicial' AND '$fecha_final'";
}
else{
	$ultimoDia=date("d",(mktime(0,0,0,date(m)+1,1,date(Y))-1));
	$fecha_inicial=date(Y)."-".date(m)."-01";
	$fecha_final=date(Y)."-".date(m)."-".$ultimoDia;
	$fechas=" and fecha BETWEEN '$fecha_inicial' AND '$fecha_final'";	
}
//------------ query para obtener los registros del ultimo mes ----------------------
$sql="SELECT CONCAT(e.paterno,' ',e.materno,' ',e.nombres) AS nombre,a.idnominaemp,a.fecha,a.hora,a.tipo 
FROM asistencias a
INNER JOIN nominaemp e ON a.idnominaemp=e.idnominaemp";
$sql.= " WHERE (a.tipo='F' $empleado $fechas ) OR (a.tipo='EX' $empleado $fechas) OR (a.tipo='R' $empleado $fechas)  ";
$sql.=" OR (a.tipo='EP' $empleado $fechas) ";
$sql.=" GROUP BY idnominaemp  ORDER BY nombre ";
//echo $sql;
mysql_select_db($database_conexion, $conexion);
$res=mysql_query($sql,$conexion);
$ren=mysql_num_rows($res);

?>
<!doctype html>
<html>
<head>
<meta charset="iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/estilos.css">

<style>
	.retardo{
		background:#fff;	
	}
	.falta{
		background:#FBF5EF;
	}
	.retardos3{
		background:#E0F2F7;
	}
	.ex{
		background:#F2F5A9;
	}
	
	.cap{
		position:fixed;
		top:0px;
		background:#CCC; 
		width:100%;
	}
	.tabla_interna{
		font-size:26px;
		width:70%;
		text-align:center;
		vertical-align:middle;
	}
	.tabla_interna td{
		text-align:center;
	}
</style>
<script src="encabezadofijo/mootools-yui-compressed.js"></script>
<script src="encabezadofijo/funciones.js" type="text/javascript"></script>
<script src="controles_jquery/js/jquery-1.9.1.js"></script>

</head>
<body topmargin="0" leftmargin="0">

<div id="encabezado">
<table class="tablagrid" border="0" cellpadding="0" cellspacing="0" width="100%">
<caption align="center" class="cap"><?php echo $fecha_inicial." a ".$fecha_final?></caption>
  <tr class="tablahead" style="position:fixed; top:20px">
  	<td width="10%" align="center">NUM. EMPLEADO</td>
    <td width="30%" align="center">NOMBRE</td>
    <td width="15%" align="center">RETARDOS</td>
    <td width="15%" align="center">FALTAS</td>
	<td width="15%" align="center">EXTRADA EX</td>
    <td width="15%" align="center">VER</td>
  </tr>
</table>
</div>
<br><br><br>
<table class="tablagrid" border="0" cellpadding="0" cellspacing="0" width="100%">
  <?php
  	while($ren=mysql_fetch_array($res)){
		$ver="";
		//-------------- Numero de Retardos ----------------
		$sql_retardos="SELECT * FROM asistencias WHERE idnominaemp='$ren[idnominaemp]' AND"; 
		$sql_retardos.=" fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND tipo='R' GROUP BY fecha";
		$res_retardos=mysql_query($sql_retardos,$conexion);
		$num_retardos=mysql_num_rows($res_retardos);
		//--------------  Numero de Faltas ---------------------
		$sql_faltas="SELECT * FROM asistencias WHERE idnominaemp='$ren[idnominaemp]' AND"; 
		$sql_faltas.=" fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND tipo='F' GROUP BY fecha";
		$res_faltas=mysql_query($sql_faltas,$conexion);
		$num_faltas=mysql_num_rows($res_faltas);
		//--------------  Numero de Entradas estraordinarias ---------------------
		$sql_ex="SELECT * FROM asistencias WHERE idnominaemp='$ren[idnominaemp]' AND"; 
		$sql_ex.=" fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND tipo='EX'";
		$res_ex=mysql_query($sql_ex,$conexion);
		$num_ex=mysql_num_rows($res_ex);
		
		if($num_faltas>0){$clase="falta";}
		elseif($num_retardos>=3){$clase="retardos3";}
		elseif($num_retardos>0 && $num_retardos<3){$clase="retardo";}
		elseif($num_ex>=1){$clase="ex";}
		else{$clase="";}
						
  ?>  
  <tr class="message_box tablaregistros">
  	<td width="10%" align="center" class="<? echo $clase?>"><?PHP echo $ren['idnominaemp']?></td>
    <td width="30%" align="center" class="<? echo $clase?>" ><?PHP echo $ren['nombre']?></td>
    <td width="15%" align="center" class="<? echo $clase?>"><?PHP echo $num_retardos?></td>
    <td width="15%" align="center" class="<? echo $clase?>"><?PHP echo $num_faltas?></td>
    <td width="15%" align="center" class="<? echo $clase?>"><?PHP echo $num_ex?></td>
    <td width="15%" align="center" class="<? echo $clase?>">
    	<img src="imagenes/ver.png" width="30" height="30" onClick="window.parent.incidencias(<?php echo $ren['idnominaemp']?>,'<?php echo $fecha_inicial?>','<?php echo $fecha_final?>')">
    </td>
  </tr>
  <?php
  }
  ?>
  <tr>
</table>

<div id="enespera"></div>
<input type="hidden" name="consulta" id="consulta" value="<? if(isset($_GET["consulta"])) echo $_GET["consulta"]; ?>">
</form>
</body>
</html>
<?php
mysql_free_result($empleados);
?>
