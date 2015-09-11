<?
$con = mysqli_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw","movimientos" ) or die ( "Could not connect: " . mysqli_error () );
//mysql_select_db ( "movimientos" );

$query = "SELECT DISTINCT
    idprograma, clave, descripcion, idarea
FROM
    cat_programa";
$programas = array();

$r_prog = mysqli_query ( $con, $query );
$i=0;
while ( $row = mysqli_fetch_array ( $r_prog , MYSQL_ASSOC) ) {
	$programas[$i]=$row;
	$i++;
}
echo json_encode($programas,JSON_FORCE_OBJECT);

?>
