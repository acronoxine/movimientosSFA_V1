<?
/**
 * Get a plaza_clave from cat_plazas.
 * Return a JSON struct 
 */
$con = mysql_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw" ) or die ( "Could not connect: " . mysql_error () );
mysql_select_db ( "movimientos" );

$query = "SELECT DISTINCT
    plaza_clave
FROM
    cat_plazas";
$plaza_clave = array();

$r_plaza_clave = mysql_query ( $query, $con );
$i=0;
while ( $row = mysql_fetch_array ( $r_plaza_clave ) ) {
	
	$plaza_clave[$i]=$row['plaza_clave'];
	$i++;
}
echo json_encode($plaza_clave,JSON_FORCE_OBJECT);

?>
