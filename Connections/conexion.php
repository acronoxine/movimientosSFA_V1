<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conexion = "localhost";
$database_conexion = "movimientos";
$username_conexion = "movimientos";
$password_conexion = "sWTX/.9LQA2Jw";
$conexion = mysqli_connect($hostname_conexion, $username_conexion, $password_conexion, $database_conexion) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
