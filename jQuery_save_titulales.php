<?php
/**
 * Module UPP titular
 * @author zirangua mejia jose luis
 * @version 1.0
 * @category controller to DB.
 */
if (isset ( $_POST ['upp'] ) == 1) {
	$con = mysql_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw" ) or die ( "Could not connect: " . mysql_error () );
	mysql_select_db ( "movimientos" );
	
	$upp = $_POST ['upp'];
	$upp_nombre = $_POST ['upp_nombre'];
	$upp_puesto = $_POST ['upp_puesto'];
	
	$upp_check = "SELECT firma FROM cat_titular WHERE firma='1'";
	$r_check_titular = mysql_query ( $upp_check, $con );
	$row = mysql_fetch_array ( $r_check_titular );
	if ($row ['firma'] != 1) {
		/**
		 * If doesn't exist INSERT
		 *
		 * @var unknown
		 */
		$insert_cat_titular = "INSERT INTO cat_titular " . "(nombre,puesto,firma) VALUES (" . "'$upp_nombre'," . "'$upp_puesto'," . "'$upp' )";
		$r_cat_titular = mysql_query ( $insert_cat_titular, $con );
		$array_result = array ();
		$array_result [0] = $r_cat_titular;
		
		echo json_encode ( $array_result, JSON_FORCE_OBJECT );
	} else {
		/**
		 * If exist UPDATE the UPP
		 */
		$update_cat_titular = "UPDATE cat_titular SET " . "nombre =" . "'$upp_nombre'," . "puesto='$upp_puesto'," . "firma= '$upp'" . "where firma=" . $upp;
		$r_cat_titular = mysql_query ( $update_cat_titular, $con );
		$array_result = array ();
		$array_result [0] = $r_cat_titular;
		
		echo json_encode ( $array_result, JSON_FORCE_OBJECT );
	}
}
/**
 * Module UR titulares
 */
if (isset ( $_POST ['ur'] ) == 2) {
	$con = mysql_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw" ) or die ( "Could not connect: " . mysql_error () );
	mysql_select_db ( "movimientos" );
	
	$ur = $_POST ['ur'];
	$ur_nombre = $_POST ['ur_nombre'];
	$ur_puesto = $_POST ['ur_puesto'];
	
	$upp_check = "SELECT firma FROM cat_titular WHERE firma='2'";
	$r_check_titular = mysql_query ( $upp_check, $con );
	$row = mysql_fetch_array ( $r_check_titular );
	if ($row ['firma'] != 2) {
		/**
		 * If doesn't exist INSERT
		 *
		 * @var unknown
		 */
		$insert_cat_titular = "INSERT INTO cat_titular " . "(nombre,puesto,firma) VALUES (" . "'$ur_nombre'," . "'$ur_puesto'," . "'$ur' )";
		$r_cat_titular = mysql_query ( $insert_cat_titular, $con );
		$array_result = array ();
		$array_result [0] = $r_cat_titular;
		
		echo json_encode ( $array_result, JSON_FORCE_OBJECT );
	} else {
		/**
		 * If exist UPDATE the UPP
		 */
		$update_cat_titular = "UPDATE cat_titular SET " . "nombre =" . "'$ur_nombre'," . "puesto='$ur_puesto'," . "firma= '$ur'" . "where firma=" . $ur;
		$r_cat_titular = mysql_query ( $update_cat_titular, $con );
		$array_result = array ();
		$array_result [0] = $r_cat_titular;
		
		echo json_encode ( $array_result, JSON_FORCE_OBJECT );
	}
}
/**
 * Module RH titulares
 */
if (isset ( $_POST ['rh'] ) == 3) {
	$con = mysql_connect ( "localhost", "movimientos", "sWTX/.9LQA2Jw" ) or die ( "Could not connect: " . mysql_error () );
	mysql_select_db ( "movimientos" );
	
	$rh = $_POST ['rh'];
	$rh_nombre = $_POST ['rh_nombre'];
	$rh_puesto = $_POST ['rh_puesto'];
	
	$upp_check = "SELECT firma FROM cat_titular WHERE firma='3'";
	$r_check_titular = mysql_query ( $upp_check, $con );
	$row = mysql_fetch_array ( $r_check_titular );
	if ($row ['firma'] != 3) {
		/**
		 * If doesn't exist INSERT
		 *
		 * @var unknown
		 */
		$insert_cat_titular = "INSERT INTO cat_titular " . "(nombre,puesto,firma) VALUES (" . "'$rh_nombre'," . "'$rh_puesto'," . "'$rh' )";
		$r_cat_titular = mysql_query ( $insert_cat_titular, $con );
		$array_result = array ();
		$array_result [0] = $r_cat_titular;
		
		echo json_encode ( $array_result, JSON_FORCE_OBJECT );
	} else {
		/**
		 * If exist UPDATE the UPP
		 */
		$update_cat_titular = "UPDATE cat_titular SET " . "nombre =" . "'$rh_nombre'," . "puesto='$rh_puesto'," . "firma= '$rh'" . "where firma=" . $rh;
		$r_cat_titular = mysql_query ( $update_cat_titular, $con );
		$array_result = array ();
		$array_result [0] = $r_cat_titular;
		
		echo json_encode ( $array_result, JSON_FORCE_OBJECT );
	}
}
?>