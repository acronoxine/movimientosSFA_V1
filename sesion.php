﻿<?php
	session_start();
?>
<?php
include("Connections/conexion.php");

$usuario = $_POST["usuario"];
$password = $_POST["password"];

$sql = "Select * from usuarios where usuario = '$usuario'";

if(!$res = mysqli_query($conexion,$sql))
{
   echo "<SCRIPT language = 'javascript'>";
   echo "alert('Imposible consultar el usuario indicado');";
   echo "</SCRIPT>";
   exit(1);
}

if(mysqli_num_rows($res) == 0)
{
   echo "<SCRIPT language = 'javascript'>";
   echo "alert('Su nombre de usuario no esta registrado o es incorrecto!');";
   echo "location.replace('index.php')";
   echo "</SCRIPT>";
   exit(1);
}

$ren = mysqli_fetch_array($res);

$_SESSION["m_sesion"] = 0;

if(trim($ren["clave"]) == trim($password))
{
     $_SESSION["m_sesion"] = 1;
     $_SESSION["m_idregistro"] = $ren["idusuario"];
     $_SESSION["m_usuario"] = $ren["usuario"];
     $_SESSION["m_nombre"] = $ren["nombre"];
     $_SESSION["m_permisos"] = $ren["derechos"];
	 
	 $sql = "Select idbancos from empresa where idempresa = '$ren[idempresa]'";
	 $res = mysqli_query($conexion,$sql);
	 $ren = mysqli_fetch_array($res);

	 
	 $_SESSION["m_banco"] = $ren["idbancos"];
	 
	 mysqli_free_result($res);
     

     echo "<SCRIPT language = 'javascript'>";
	 echo "location.replace('menuprin.php');";
     echo "</SCRIPT>";
}else{
    $_SESSION["m_sesion"] = 0;
    echo "<SCRIPT language = 'javascript'>";
    echo "alert('Su contraseña es incorrecta, verifíquela!');";
    echo "location.replace('index.php')";
    echo "</SCRIPT>";
    exit(1);
}

?>
