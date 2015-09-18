<?php
/**
 * @abstract Liberar plaza.
 * @author Zirangua Mejia Jose Luis.
 * @category update cat_plaza.
 * @param plaza_id
 * @param etc.
* Update a plaza_clave from cat_plazas.
* Return a JSON struct
*/
$con = mysqli_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw","movimientos" ) or die ( "Could not connect: " . mysql_error () );
//mysql_select_db ( "movimientos" );

$plaza_id = $_POST['plaza_id'];
$fecha_inicial = $_POST['fecha_inicial'];
$fecha_final = $_POST['fecha_final'];
$estado = $_POST['estado'];
$update_emp_plaza = "UPDATE empleado_plaza "
		."SET "
		."idnominaemp = '0',"
		."fecha_inicial = '$fecha_inicial' ,"
		."fecha_final = '$fecha_final',"
		."estado = 'VACANTE'"
		." WHERE plaza_id= '$plaza_id'";

$r_cat_plazas = mysqli_query ( $con,$update_emp_plaza );

$array_result = array();
$array_result[0]=$r_cat_plazas;

echo json_encode($array_result,JSON_FORCE_OBJECT);

?>