<?php 
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/accesos_bd.php');

$con=conectar();

$id_encuesta = $_GET['id_encuesta'];
$enunciado = $_GET['enunciado'];
$estado = $_GET['estado'];

$tabla_encuesta = mysqli_query($con,"update encuestas set nombre = '$enunciado', estado = $estado where id_encuesta = $id_encuesta") or die ('Revisar Edición Encuestas');

mysqli_close($con);

header('location:padron_encuesta.php');
?>
