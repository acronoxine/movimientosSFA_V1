<?
$con = mysqli_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw","movimientos" )
or die ( "Could not connect: " . mysqli_error () );
//mysql_select_db ( "movimientos" );

$query = "SELECT 
    idsubprograma, clave, descripcion, idprograma
FROM
    cat_subprograma";
$subprogramas = array();

$r_prog = mysqli_query ( $con,$query );
$i=0;
while ( $row = mysqli_fetch_array ( $r_prog ) ) {
	
	$subprogramas[$i] = $row;
	$i++;
}
echo json_encode($subprogramas,JSON_FORCE_OBJECT);

?>
