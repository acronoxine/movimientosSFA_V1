<?
$con = mysql_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw" ) or die ( "Could not connect: " . mysql_error () );
mysql_select_db ( "movimientos" );

$id_programa = $_POST ['id_programa'];
$query = "SELECT 
    programa,subprograma
FROM
    cat_plazas
WHERE
    programa = ".$id_programa;
$programas = array();

$r_prog = mysql_query ( $query, $con );
$i=0;
while ( $row = mysql_fetch_array ( $r_prog ) ) {
	
	$programas[$i]=$row['programa'];
	$i++;
}
echo json_encode($programas,JSON_FORCE_OBJECT);

?>
