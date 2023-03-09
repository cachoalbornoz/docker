<?php 
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/accesos_bd.php');

$con=conectar();

$id_respuesta	= $_GET['id_respuesta'];
$enunciado 		= strtoupper($_GET['enunciado']);
$valor_spss		= $_GET['valor_spss'];
$fila			= $_GET['fila'];
		
$tabla_respuesta = mysqli_query($con,"update respuestas set enunciado = '$enunciado', valor_spss = $valor_spss, fila=$fila where id_respuesta = $id_respuesta") or die ('Revisar Edicion Respuestas');
												
mysqli_close($con);

header('location:padron_respuestas.php?id_pregunta='.$_SESSION['id_pregunta']);
?>
