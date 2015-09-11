<?
$con = mysql_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw" ) or die ( "Could not connect: " . mysql_error () );
mysql_select_db ( "movimientos" );

$query = "SELECT DISTINCT
    idprograma, clave, descripcion, idarea
FROM
    cat_programa";
$programas = array();

$r_prog = mysql_query ( $query, $con );
$i=0;
while ( $row = mysql_fetch_array ( $r_prog , MYSQL_ASSOC) ) {
	$programas[$i]=$row;
	$i++;
}
echo json_encode($programas,JSON_FORCE_OBJECT);

?>
