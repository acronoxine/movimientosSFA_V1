<?
/**
 * Add a new plaza key into movimientos.cat_plazas
 * with This module
 * 
 */

/**
 * Update a plaza_clave from cat_plazas.
 * Return a JSON struct
 */
$con = mysqli_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw", "movimientos" );
if (mysqli_connect_errno ()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error ();
	exit ();
}

/**
 *
 * @author Zirangua Mejia Jose Luis
 * @var $plaza_clave
 * @var $ur
 * @var $programa
 * @var $suprograma
 * @var @categoria
 * @category mysqli_prepare and return JSON status
 * @version 1.0
 * @copyright 2015-2021
 */

$plaza_clave = $_POST ['plaza_clave'];
$check_query = "SELECT 
    count(plaza_clave)
FROM
    cat_plazas

where plaza_clave=?";

$stmt_check = mysqli_prepare ( $con, $check_query );
mysqli_stmt_bind_param ( $stmt_check, "s", $plaza_clave );
mysqli_stmt_execute( $stmt_check );
//$status=mysqli_stmt_affected_rows($stmt_check);
mysqli_stmt_bind_result($stmt_check,$count);
mysqli_stmt_fetch($stmt_check);
//print_r($count);
mysqli_stmt_close ( $stmt_check );
if ($count == 0) { /*If estatus == -1 Insert new plaza_clave data*/ 
	$ur = $_POST ['ur'];
	$programa = $_POST ['programa'];
	$subprograma = $_POST ['subprograma'];
	$categoria = $_POST ['categoria']; // This value incoming from table cat_categoria -> colum idcategoria
	
	$query = "INSERT INTO movimientos.cat_plazas (plaza_clave,ur,programa,subprograma,categoria) VALUES (?,?,?,?,?)";
	$stmt = mysqli_prepare ( $con, $query );
	/*
	 * set parameters;
	 * sssss type of data send to DB
	 */
	
	mysqli_stmt_bind_param ( $stmt, 'sssss', $plaza_clave, $ur, $programa, $subprograma, $categoria );
	 
	 /*
	 * Execute sentence;
	 */
	$r_cat_plazas = mysqli_stmt_execute ( $stmt );
	mysqli_stmt_close ( $stmt );
	
	$array_result = array ();
	$array_result ['status'] = 'Plaza agregada correctamente';
	
	echo json_encode ( $array_result, JSON_FORCE_OBJECT );
} 
else {
	$array_status = array ();
	$array_status ['status'] = 'Plaza ya se encuentra activa!';
	echo json_encode ( $array_status, JSON_FORCE_OBJECT );
}
?>
