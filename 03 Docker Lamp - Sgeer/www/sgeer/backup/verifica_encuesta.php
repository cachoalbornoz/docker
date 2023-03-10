<?php
session_start();
require('../accesorios/accesos_bd.php');
$con=conectar();
$id = $_GET['id'];

$tabla_encuestas = mysqli_query($con, "select id_encuesta from encuestas where id_encuesta = $id"); 
$fila = mysqli_fetch_array($tabla_encuestas);

$_SESSION['id_encuesta']=$fila[0];

echo $fila[0];

mysqli_close($con); 

?>
