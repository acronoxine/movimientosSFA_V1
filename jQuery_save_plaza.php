<?
/**
 * Ask to Ing. Omar. about what?
 * with This module
 * 
 */

/**
 * Update a plaza_clave from cat_plazas.
 * Return a JSON struct 
 */
$con = mysql_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw" ) or die ( "Could not connect: " . mysql_error () );
mysql_select_db ( "movimientos" );

$ur = $_POST['ur'];
$programa = $_POST['programa'];
$subprograma = $_POST['subprograma'];

$update_cat_plazas = "INSERT cat_plazas "
	."SET "
	."plaza_clave = '$plaza_clave',"
	."ur = '$ur' ,"
	."programa = '$programa',"
	."subprograma = '$subprograma',"
	."categoria = '$categoria' ,"
	."titular =  '$titular' "
	." WHERE plaza_id= '$plaza_id'";

$r_cat_plazas = mysql_query ( $update_cat_plazas, $con );

$array_result = array();
$array_result[0]=$r_cat_plazas;

echo json_encode($array_result,JSON_FORCE_OBJECT);

?>
