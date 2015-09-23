<?php
/**
 * @abstract Asignar plaza.
 * @author Zirangua Mejia Jose Luis.
 * @category update cat_plaza.
 * @param 	plaza_id 		: plaza_id,
 * @param	idempleado		: fecha_inicial,
 * @param  	tipoContrato	: fecha_final,
 * @param	tipoAsignacion	: tipoAsignacion,
 * @param	fecha			: estado,
 * @param	fecha2			: idnominaemp
* Update a plaza_clave from cat_plazas and nominaemp
* Return a JSON struct
*/
$plaza_id 		= 	$_POST['jsonf'][0]['plazaid'];
$idempleado 	=	$_POST['jsonf'][1]['idempleado'];
$tipoContrato 	= 	$_POST['jsonf'][2]['tipoContrato'];
$tipoAsignacion	=	$_POST['jsonf'][3]['tipoAsignacion'];
$fecha			=	$_POST['jsonf'][4]['fecha'];
$fecha2			=	$_POST['jsonf'][5]['fecha2'];

$con = mysqli_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw","movimientos" ) or die ( "Could not connect: " . mysqli_error () );

$update_emp_plaza = "UPDATE empleado_plaza "
		."SET "
		."idnominaemp = '$idempleado',"
		."fecha_inicial = '$fecha' ,"
		."fecha_final = '$fecha2',"
		."estado = 'OCUPADO'"
		." WHERE plaza_id= '$plaza_id'";

$r_cat_plazas = mysqli_query ( $con,$update_emp_plaza );

$array_result = array();
$array_result[0]	=	$r_cat_plazas;

echo json_encode($array_result,JSON_FORCE_OBJECT);
?>
