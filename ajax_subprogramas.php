<?
session_start ();
include ("Connections/conexion.php");
mysql_select_db ( $database_conexion, $conexion );

$sql = "Select * from cat_subprograma where idprograma = '$_POST[idprograma]'";
$subprogramas = mysql_query ( $sql, $conexion );
?>
<select name="subprograma" style="width: 180px;">
	<option value="">Seleccione</option>
                  <?php
																		while ( $row_subprogramas = mysql_fetch_array ( $subprogramas ) ) {
																			?>
                  <option
		value="<?php echo $row_subprogramas['idsubprograma']?>"><?php echo $row_subprogramas['clave'], " ", $row_subprogramas['descripcion']?></option>
                  <?php
																		}
																		?>
                </select>
<label class="label">*</label>
<?
mysql_free_result ( $subprogramas );
?>