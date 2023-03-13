<?php
session_start();
require_once '../accesorios/accesos_bd.php';
$con = conectar();

$usuario  = $_POST['usuario'];
$password = $_POST['password'];

$tabla = mysqli_query($con, 'select * from usuarios where nombre_usuario="' . mysqli_real_escape_string($con, $usuario) . '"') or die('Error lectura de usuarios');

if (mysqli_num_rows($tabla) > 0) {
    $registro = mysqli_fetch_array($tabla);
    $clave    = $registro['password'];

    if (md5($password) == $clave) {
        $_SESSION['id_usuario'] = $id_usuario = $registro['id_usuario'];
        $_SESSION['usuario']    = $registro['nombre_usuario'];
        $_SESSION['estado']     = $registro['estado'];
    }
}

mysqli_free_result($tabla);
mysqli_close($con);

if (isset($_SESSION['estado'])) {
    switch ($_SESSION['estado']) {
        case 'a':
            ///////////////// ADMINISTRADORES ///////////////////////////
            header('location: ../estadisticas/principal_estadistica.php');
            break;
        case 'x':
          ///////////////// DESARROLLO ///////////////////////////
          header('location: ../desarrollo/sesion_usuario_desarrollo.php');
          break;
        case 'c':
            ///////////////// USUARIOS FINAL ///////////////////////////
            header('location: ../estadisticas/principal_estadistica.php');
            break;
    }
} else {
    header('location: ../../index.php?autenticado=false');
}
