<?php
require_once('Connections/conexion.php'); 
mysql_select_db($database_conexion, $conexion);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte de Incidencias</title>
<style>
#cuerpo{
	width:99%;
	font-family:"Times New Roman", Times, serif;
	text-align:center;	
}
#tencabezado{
	width:98%;
	font-size:16px;
	font-weight:bold;
	height:150px;
	vertical-align:text-top;
}
#tencabezado td{
	text-align:center;
	vertical-align:text-top;
}
.incidencias{
	font-family:"Times New Roman", Times, serif;
	text-align:center;
	width:100%;
	border-style:solid;
}
.incidenciasp{
	font-family:"Times New Roman", Times, serif;
	font-size:14px;
	text-align:center;
	width:100%;
	border-style:solid;
	
}
.nombretr{
	background:#CEE3F6;
	font-size:16px;
	color:#036;
	font-weight:bold;
}
.regsitrado{
	width:70%;
	font-size:18px;
	text-align:center;
	border-style:groove;
}
.regsitrado th{
	background:#CEE3F6;
}
.registros{
	width:100%;
	border-style:solid;
}
.tablaTOTAL{
	width:80%;
	border-style:solid;
	font-family:"Times New Roman", Times, serif;
	font-size:12px;
	text-align:center
}
</style>
</head>

<body>
<?php
$empleado=$_GET['empleado'];
	$fecha_inicial=$_GET['fechai'];
	$fecha_final=$_GET['fechaf'];
	$rTotal=$_GET['total'];
	$empleado_q="SELECT CONCAT(paterno,' ',materno,' ',nombres) AS nombre FROM nominaemp WHERE idnominaemp='$empleado'";
	$empleado_r=mysql_query($empleado_q,$conexion);
	$empleado_d=mysql_fetch_array($empleado_r);
	
			//--------------  Numero de asistencias ---------------------
		$sql_asistencias="SELECT * FROM asistencias WHERE idnominaemp='$empleado' AND"; 
		$sql_asistencias.=" fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND tipo!='R' AND tipo!='EX' AND tipo!='F'";
		$res_asistencias=mysql_query($sql_asistencias,$conexion);
		$num_asistencias=mysql_num_rows($res_asistencias);
			//--------------  Numero de Retardos ---------------------
		$sql_retardos="SELECT * FROM asistencias WHERE idnominaemp='$empleado' AND"; 
		$sql_retardos.=" fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND tipo='R' GROUP BY fecha";
		$res_retardos=mysql_query($sql_retardos,$conexion);
		$num_retardos=mysql_num_rows($res_retardos);
			//--------------  Numero de Faltas ---------------------
		$sql_faltas="SELECT * FROM asistencias WHERE idnominaemp='$empleado' AND"; 
		$sql_faltas.=" fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND tipo='F' GROUP BY fecha";
		$res_faltas=mysql_query($sql_faltas,$conexion);
		$num_faltas=mysql_num_rows($res_faltas);
		
		$sql_pases="select * from pases where id_solicitante='$empleado' and fecha BETWEEN '$fecha_inicial' and '$fecha_final' order by fecha";
		$res_pases=mysql_query($sql_pases,$conexion);
			//--------------  Numero de Entradas estraordinarias ---------------------
			$sql_ex="SELECT * FROM asistencias WHERE idnominaemp='$empleado' AND"; 
		$sql_ex.=" fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND tipo='EX'";
		$res_ex=mysql_query($sql_ex,$conexion);
		$num_ex=mysql_num_rows($res_ex);
	if($rTotal!=1){	
	?>
	<div id="cuerpo">
		<table align="center" id="tencabezado" cellspacing="2">
			<tr>
				<td width="30%"><img src="imagenes/logo.jpg" width="150" height="52" /></td>
				<td width="40%"></td>
				<td width="30%">Registro de Incidencias</td>
			<tr>
				<td colspan="3">
					<h4><?php echo $empleado_d['nombre'];?><br><?php echo "PERIODO:<br>".$fecha_inicial. " - ".$fecha_final?></h4>
				</td>
			</tr>
		</table>
		<br><br>
		<div style="width:35%;float:left">
			<h3>REGISTROS:</h3>
			<table class="incidencias" align="center">
				<tr class="nombretr">
					<td colspan="3">Entradas Extraordinarias</td>
				</tr>
				<tr>
					<th>Fecha</th><th>Hora</th><th>Tipo</th>
				</tr>
				<?php
				while($ren=mysql_fetch_array($res_ex)){
				?>
				<tr>
					<td><? echo $ren['fecha']?></td>
					<td><? echo $ren['hora']?></td>
					<td><? echo $ren['tipo']?></td>
				</tr>
				<?php 
				}?>
				<tr class="nombretr">
					<td colspan="3">Faltas</td>
				</tr>
				<?php
				while($ren=mysql_fetch_array($res_faltas)){
				?>
				<tr>
					<td><? echo $ren['fecha']?></td>
					<td><? echo $ren['hora']?></td>
					<td><? echo $ren['tipo']?></td>
				</tr>
				<?php 
				}?>
				<tr class="nombretr">
					<td colspan="3">Retardos</td>
				</tr>
				<?php
				while($ren=mysql_fetch_array($res_retardos)){
				?>
				<tr>
					<td><? echo $ren['fecha']?></td>
					<td><? echo $ren['hora']?></td>
					<td><? echo $ren['tipo']?></td>
				</tr>
				<?php 
				}?>
			</table>
		</div>
		<div style="width:65%; float:right;">                                          
			<h3>PASES DE SALIDA</h3>
			<table class="incidenciasp" >
			<tr class="nombretr">
					<td colspan="7">Registro de Pases</td>
				</tr>
			<tr>
				<th width="2%">#</th>
				<th width="20%">Fecha</th>
				<th width="15%">H.Salida</th>
				<th width="15%">H.Llegada</th>
				<th width="15%">Tipo</th>
				<th width="30%">Observaciones</th>
				<th width="13%">Aut.</th>
			</tr>
			<?php
				$cont=0;
				while($ren_pases=mysql_fetch_array($res_pases)){
				$cont++;
			?>
			<tr>
				<td width="5%" height="20"><?php echo $cont?></td>
				<td width="20%"><?php echo $ren_pases['fecha']?></td>
				<td width="15%"><?php echo $ren_pases['hr_salida']?></td>
				<td width="15%"><?php echo $ren_pases['hr_llegada']?></td>
				<td width="15%"><?php echo $ren_pases['tipo']?></td>
				<td width="30%"><?php echo $ren_pases['observaciones']?></td>
				<td width="13%"><?php 
						if($ren_pases['autorizado']==1){echo "SI";}
						else{echo "NO";}
					?>
				</td>
		  </tr>
			<?php
				}
			?>
		</table>
		</div>
	</div>
	
	<?php
	$sql_reg="SELECT * FROM asistencias WHERE idnominaemp='$empleado' AND"; 
		$sql_reg.=" fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND registrado=1";
		$res_reg=mysql_query($sql_reg,$conexion);
	
	?>
	<div style="width:98%; float:right; margin-top:30px">
		<h3>INCIDENCIAS REGISTRADAS:</h3>
			<table class="regsitrado" align="center">
				<tr>
					<th>#</th><th>Fecha</th><th>Hora</th><th>Tipo</th>
				</tr>
				<?php
				$cont=0;
				while($ren=mysql_fetch_array($res_reg)){
					$cont++;
				?>
				<tr>
					<td><? echo $cont?></td>
					<td><? echo $ren['fecha']?></td>
					<td><? echo $ren['hora']?></td>
					<td><? echo $ren['tipo']?></td>
				</tr>
				<?php 
				}?>
			</table>
	</div>
	</div>
    <!-------------   REPORTE DE ASISTENCIAS -------------->
<?php
	}
	else{
		$sql="SELECT * FROM asistencias where fecha BETWEEN '$fecha_inicial' AND '$fecha_final' and idnominaemp='$empleado'";
		$res_t=mysql_query($sql,$conexion);
	
?>
<div>
	<table align="center" id="tencabezado" cellspacing="2">
			<tr>
				<td width="30%"><img src="imagenes/logo.jpg" width="150" height="52" /></td>
				<td width="40%"></td>
				<td width="30%">Registro de Asistencias</td>
			<tr>
				<td colspan="3">
					<h5><?php echo $empleado_d['nombre'];?><br><?php echo "PERIODO:<br>".$fecha_inicial. " - ".$fecha_final?></h5>
				</td>
			</tr>
		</table>
        
	<table class="tablaTOTAL" align="center">
    	<tr style="background:#CCC">
        	<th>#</th><th>Fecha</th><th>Hora</th><th>E/S Puntal</th>
            <th>Salida E.</th><th>Retardo</th><th>Falta</th>
        </tr>
        <?php
		$cont=0;
        while($ren=mysql_fetch_array($res_t)){
		$cont++;
		?>
        	<tr>
            	<td><?php echo $cont?></td>
                <td><?php echo $ren[fecha]?></td>
                <td><?php echo $ren[hora]?></td>
                <td><?php 
					if($ren[tipo]=="EP" || $ren[tipo]=="SP"){
                		echo '<img src="imagenes/check.png" width="15" height="15" />';
					}else{echo "-";}?>
                </td>
                <td><?php 
					if($ren[tipo]=="EX"){
                		echo '<img src="imagenes/check.png" width="15" height="15" />';
					}else{echo "-";}?>
                </td>
                <td><?php 
					if($ren[tipo]=="R"){
                		echo '<img src="imagenes/check.png" width="15" height="15" />';
					}else{echo "-";}?>
                </td>
                <td><?php 
					if($ren[tipo]=="F"){
                		echo '<img src="imagenes/check.png" width="15" height="15" />';
					}else{echo "-";}?>
                </td>
            </tr>
        <?PHP
		}
		?>
        <tr>
        	<td colspan="3" class="nombretr">TOTALES</td>
            <td style="background:#F2FBEF"><?php echo $num_asistencias?></td>
            <td style="background:#F7F8E0"><?php echo $num_ex?></td>
            <td style="background:#F8ECE0"><?php echo $num_retardos?></td>
            <td style="background:#F8E0E6"><?php echo $num_faltas?></td>
        </tr>
    </table>
</div>

<div style="margin-top:60px;text-align:center">
<p>
	___________________________________<br>
    	DELEGACI&Oacute;N ADMINISTRATIVA
</p>
</div>
 <?PHP
		}
		?>
<SCRIPT>
	window.print() ;
</SCRIPT>
</body>
</html>