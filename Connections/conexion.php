<?php
/**
 * @author zirangua mejia jose luis
 * @category conectio to mysql database
 * @copyright 2015 -
 * @param $hostname_conexion "hostname database server"
 * @param $database_conexion "Database name"
 * @param $username_conexion "Database username id"
 * @param $password_conexion "Database password"
 * */
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conexion = "localhost";
$database_conexion = "movimientos";
$username_conexion = "movimientos";
$password_conexion = "sWTX/.9LQA2Jw";
$conexion = mysqli_connect($hostname_conexion, $username_conexion, $password_conexion, $database_conexion);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}


/* Used only when required a link to MYSQL database;*/

class database{
	public function connect(){
		$conexion = mysqli_connect($hostname_conexion, $username_conexion, $password_conexion, $database_conexion);
		return $conexion;
	}
}
?>
