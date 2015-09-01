<?php
	session_start();
if($_SESSION["m_sesion"] != 1)
{
	echo "<script>";
	echo "location.replace('index.php');";
	echo "</script>";
	exit();
}
include 'funcionesJL.php';

if(isset($_GET[g])){
	modificaRegistro($_GET[o],$_GET[g],$_GET[f],$_GET[h],$_GET[id],$conexion);
}
?>
<html>
<head>
<meta charset="iso-8859-1">
<link type="image/x-icon" href="imagenes/logomich200.ico" rel="icon" />
<link type="image/x-icon" href="imagenes/logomich200.ico" rel="shortcut icon" />
<link rel="stylesheet" type="text/css" href="css/estilos.css">
<script language="javascript">

function imprimir(total,empleado,fechai,fechaf){
	window.open("incidenciasReporte.php?total="+total+"&empleado="+empleado+"&fechai="+fechai+"&fechaf="+fechaf, "Reporte de Incidencias", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=800, height=900")
}
</script>
<style>
#incidencias{
	width:45%;
	float:left;
	text-align:center;
	font-family:"Times New Roman", Times, serif;
	font-size:16px;
}
#pases{
	width:55%;
	float:right;
	text-align:center;
	font-family:"Times New Roman", Times, serif;
	font-size:16px;
}
.tablaI{
	width:97%;
	border-style:groove;
	font-size:14px;
}
.tablaI td{
	text-align:center;
	
}
.trt{
	background:#CCC;
}
.linea{
	height:2px;
	color:#999;
	background:#CCC;	
}
.boton_b{
width:180px;
	height:40px;
	font-family:Tahoma, Geneva, sans-serif;
	font-size:12px;
	border:1px solid #6198F4;
	background:#6198F4;
	color:#FFFFFF;
	-webkit-box-shadow: 1px 3px 12px -1px #CCC;
	box-shadow: 1px 3px 12px -1px #CCC;
}
</style>
</head>
<?php
	

	$empleado=$_GET['id'];
	$fecha_inicial=$_GET['fechai'];
	$fecha_final=$_GET['fechaf'];
	
	$empleado_q="SELECT CONCAT(paterno,' ',materno,' ',nombres) AS nombre FROM nominaemp WHERE idnominaemp='$empleado'";
	$empleado_r=mysql_query($empleado_q,$conexion);
	$empleado_d=mysql_fetch_array($empleado_r);
	
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
		//--------------  Numero de Entradas estraordinarias ---------------------
	$sql_ex="SELECT * FROM asistencias WHERE idnominaemp='$empleado' AND"; 
	$sql_ex.=" fecha BETWEEN '$fecha_inicial' AND '$fecha_final' AND tipo='EX'";
	$res_ex=mysql_query($sql_ex,$conexion);
	$num_ex=mysql_num_rows($res_ex);
?>
<h4 style="color:#039; text-align:center">

	<? 
		echo $empleado_d['nombre']."<br>";
		echo $fecha_inicial." - ".$fecha_final;
		
	?>
</h4>
<hr/>
<div style="width:98%;position:absolute">
<div id="incidencias">
	<h3>Reporte de Incidencias</h3>
    <?
    if($num_ex>0){
	?>
    <br>
    <table class="tablaI">
		<caption>ENTRADAS EXTRAORDINARIAS</caption>
		<tr class="trt">
    		<th>#</th><th>fecha</th><th>hora</th><th>Registrar</th>
    	</tr>
    	<?php
			$cont=0;
    		while($renR=mysql_fetch_array($res_ex)){
				$cont++;
		?>
    			<tr>
    				<td><?php echo $cont?></td>
        			<td><?php echo $renR['fecha']?></td>
       		 		<td><?php echo $renR['hora']?></td>
        <?
					if($cont%2==1){
						echo '<td rowspan="2">';
							echo '<table align="center"><tr>';
							if($renR[registrado]==0){
								$tot_ex=$num_ex/2;
								echo '<td>
									<a href="incidencias_mod.php?o=EX&g=1&f='.$renR[fecha].'&h='.$renR[hora].'&id='.$empleado.'&fechai='.$fecha_inicial.'&fechaf='.$fecha_final.'">
										<img src="imagenes/guardar_bd.png" width="30" height="30">
									</a>
							    </td>';			
								echo '<td><img src="imagenes/on_bdf.png" width="35" height="30"></td>';
							}
							else{
								echo '<td><img src="imagenes/guardar_bdf.png" width="30" height="30"></td>';			
								echo '<td>
									<a href="incidencias_mod.php?o=EX&g=2&f='.$renR[fecha].'&h='.$renR[hora].'&id='.$empleado.'&fechai='.$fecha_inicial.'&fechaf='.$fecha_final.'">
										<img src="imagenes/on_bd.png" width="35" height="30">
									</a>
								</td>';	
							}
							echo '</tr></table>';
						echo '</td>';
					}
					if($cont%2==0){
						echo '<tr><td colspan="4" class="linea"></td></tr>';
					}
            		?>
    			</tr>
    			<?php
			}
		?>
	</table>
    <?
	}
	if($num_faltas>0){
	?>
    <br>
	<table class="tablaI">
		<caption>FALTAS</caption>
		<tr class="trt">
    		<th>#</th><th>fecha</th><th>hora</th><th>Registrar</th>
   		</tr>
    	<?php
			$cont=0;
    		while($renR=mysql_fetch_array($res_faltas)){
			$cont++;
		?>
    	<tr>
    		<td><?php echo $cont?></td>
       		<td><?php echo $renR['fecha']?></td>
        	<td><?php echo $renR['hora']?></td>
        	<td><?php
            	echo '<table align="center"><tr>';
							if($renR[registrado]==0){
								echo '<td>
									<a href="incidencias_mod.php?o=F&g=1&f='.$renR[fecha].'&h='.$renR[hora].'&id='.$empleado.'&fechai='.$fecha_inicial.'&fechaf='.$fecha_final.'">
										<img src="imagenes/guardar_bd.png" width="30" height="30">
									</a>
								</td>';			
								echo '<td><img src="imagenes/on_bdf.png" width="35" height="30"></td>';
							}
							else{
								echo '<td><img src="imagenes/guardar_bdf.png" width="30" height="30"></td>';
											
								echo '<td>
									<a href="incidencias_mod.php?o=F&g=2&f='.$renR[fecha].'&h='.$renR[hora].'&id='.$empleado.'&fechai='.$fecha_inicial.'&fechaf='.$fecha_final.'">	
										<img src="imagenes/on_bd.png" width="35" height="30">
									</a>
								</td>';	
							}
							echo '</tr></table>';
				?>
            </td>
    	</tr>
    	<?php
			}
		?>
	</table>
    <?
	}
	if($num_retardos>0){
	?>
	<br>
	<table class="tablaI">
		<caption>RETARDOS</caption>
		<tr class="trt">
    		<th>#</th><th>fecha</th><th>hora</th><th>Registrar</th>
    	</tr>
    	<?php
			$cont=0;
    		while($renR=mysql_fetch_array($res_retardos)){
				$cont++;
		?>
    	<tr>
    		<td><?php echo $cont?></td>
   		    <td><?php echo $renR['fecha']?></td>
        	<td><?php echo $renR['hora']?></td>
        		<?
				if($cont==1){
					echo '<td rowspan="3">';
					if($num_retardos%3==0){
						echo '<table align="center"><tr>';
							if($renR[registrado]==0){
								echo '<td>
									<a href="incidencias_mod.php?o=R&g=1&f='.$renR[fecha].'&h='.$renR[hora].'&id='.$empleado.'&fechai='.$fecha_inicial.'&fechaf='.$fecha_final.'">
										<img src="imagenes/guardar_bd.png" width="30" height="30">
									</a>	
								</td>';			
								echo '<td><img src="imagenes/on_bdf.png" width="35" height="30"></td>';
							}
							else{
								echo '<td><img src="imagenes/guardar_bdf.png" width="30" height="30"></td>';			
								echo '<td>
									<a href="incidencias_mod.php?o=R&g=2&f='.$renR[fecha].'&h='.$renR[hora].'&id='.$empleado.'&fechai='.$fecha_inicial.'&fechaf='.$fecha_final.'">
										<img src="imagenes/on_bd.png" width="35" height="30">
									</a>
								</td>';	
							}
							echo '</tr></table>';			
					}
				echo '</td>';
				}
            	?> 
    	</tr>
    	<?php
			}
		?>
	</table>
    <?
    }
	
	$sql_pases="select * from pases where id_solicitante='$empleado' and fecha BETWEEN '$fecha_inicial' and '$fecha_final' order by fecha";
	$res_pases=mysql_query($sql_pases,$conexion);
	?>	
</div>
<div id="pases">
	<h3>Pases de salida</h3>
    <br>
    <table class="tablaI">
		<caption>REGISTRO DE PASES</caption>
		<tr class="trt">
    		<th width="2%">#</th>
            <th width="20%">fecha</th>
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
<div align="center" style="width:95%;float:right;margin-top:30px;">
	<table width="70%" align="center">
    	<tr><td>
    		<a href="incidencias_mod.php?o=n&g=3&f=x&h=x&id=<? echo $empleado?>&fechai=<? echo $fecha_inicial;?>&fechaf=<? echo $fecha_final;?>">
    			<input type="button" class="boton_b" value="Borrar Faltas Insertadas">
    		</a>
            </td>
            <td>
            	<a href="#" onClick="imprimir(0,<? echo $empleado?>,'<? echo $fecha_inicial;?>','<? echo $fecha_final;?>')" >
    			<input type="button" class="boton_b" value="Imprimir Reporte">
         </td>
          <td>
            	<a href="#" onClick="imprimir(1,<? echo $empleado?>,'<? echo $fecha_inicial;?>','<? echo $fecha_final;?>')" >
    			<input type="button" class="boton_b" value="Reporte de Asistencias">
         </td>
         
         </tr>
   </table>
</div>
</div>

</body>
</html>