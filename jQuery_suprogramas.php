<?
$con = mysql_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw" ) or die ( "Could not connect: " . mysql_error () );
mysql_select_db ( "movimientos" );

$query = "SELECT 
    idsubprograma, clave, descripcion, idprograma
FROM
    cat_subprograma";
$subprogramas = array();

$r_prog = mysql_query ( $query, $con );
$i=0;
while ( $row = mysql_fetch_array ( $r_prog ) ) {
	
	$subprogramas[$i] = $row;
	$i++;
}
echo json_encode($subprogramas,JSON_FORCE_OBJECT);

?>
