<?
/**
 * Update a plaza_clave from cat_plazas.
 * Return a JSON struct 
 */
$con = mysqli_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw","movimientos" ) or die ( "Could not connect: " . mysqli_error () );

$plaza_id = $_POST['plaza_id'];
$plaza_clave = $_POST['plaza_clave'];
$ur = $_POST['ur'];
$programa = $_POST['programa'];
$subprograma = $_POST['subprograma'];
$categoria = $_POST['categoria']; 
$titular = $_POST['titular'];

$update_cat_plazas = "UPDATE cat_plazas "
	."SET "
	."plaza_clave = '$plaza_clave',"
	."ur = '$ur' ,"
	."programa = '$programa',"
	."subprograma = '$subprograma',"
	."categoria = '$categoria' ,"
	."titular =  '$titular' "
	." WHERE plaza_id= '$plaza_id'";

$r_cat_plazas = mysqli_query ( $con, $update_cat_plazas );

$array_result = array();
$array_result[0]=$r_cat_plazas;

echo json_encode($array_result,JSON_FORCE_OBJECT);

?>
