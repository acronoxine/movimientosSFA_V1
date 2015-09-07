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
// $r_cat_plazas = mysqli_query ( $con, $add_cat_plazas );
mysqli_stmt_close ( $stmt );

$array_result = array ();
$array_result [0] = print_r ( $r_cat_plazas ); // $r_cat_plazas;

echo json_encode ( $array_result, JSON_FORCE_OBJECT );

?>
