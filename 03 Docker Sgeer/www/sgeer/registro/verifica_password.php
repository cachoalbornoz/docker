<?php 
session_start();	
require_once('../accesorios/accesos_bd.php');

$con=conectar();
$id_usuario = $_SESSION['id_usuario'];
$clave_vieja=$_POST['password'];
$clave_nueva=$_POST['password_nueva'];

$tabla = mysqli_query($con, "select password from usuarios where id_usuario = '$id_usuario'" ) or die("Error lectura de usuarios");
$registro = mysqli_fetch_array($tabla, MYSQL_ASSOC) ;
$clave_registro = $registro['password'];

if(md5($clave_vieja) == $clave_registro){
    $password= md5($clave_nueva);
    $edita="UPDATE usuarios SET password = '$password', clave = '$clave_nueva' where id_usuario = $id_usuario";
    $resultado=mysqli_query($con, $edita);
    $_SESSION['verifica']=true;
}else{
    $_SESSION['verifica']=false;
}

mysqli_free_result($tabla);
mysqli_close($con);

header("location:../registro/cambio_clave_nueva.php");
?>
