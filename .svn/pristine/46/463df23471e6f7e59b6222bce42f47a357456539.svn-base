<?
include("Connections/conexion.php");
mysql_select_db($database_conexion, $conexion);


$query_subp= "SELECT pz.plaza_id,pz.plaza_clave as plaza_clave,c.clave as categoria_clave,c.descripcion as categoria_desc FROM cat_plazas pz inner join cat_categoria c on pz.categoria=c.idcategoria
	WHERE pz.proyecto=$_POST[idproyecto] AND pz.subprograma=$_POST[idsubprograma] ";
$res_subp=mysql_query($query_subp,$conexion);

if(isset($_POST[desc])){
	$proyectop=$_POST[desc];
}
else{
	$proyectop="Seleccione";
}
?>
<!doctype html>
<html>
<head>

<meta charset="iso-8859-1">
</head>
<body>

                <select name="plaza" class="lista" style="width:180px;" onChange="cargasueldo_plaza(this.value)">
                	<option value=""><? echo $proyectop?></option>
                  <?php 
do {  
?>
                  <option value="<?php echo $row_subp['plaza_id']?>" ><?php echo $row_subp['plaza_clave']." - ".$row_subp['categoria_desc']?></option>
				  <?php
} while ($row_subp = mysql_fetch_assoc($res_subp));
?>
                </select>
                
</body>
</html>