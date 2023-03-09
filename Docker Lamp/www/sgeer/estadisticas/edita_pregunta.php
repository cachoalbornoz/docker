<?php 
session_start();
if(!isset($_SESSION['usuario']))
{
	header('location:../accesorios/salir.php');
	exit;
}
require('../accesorios/accesos_bd.php');

$con=conectar();

$id_pregunta= $_GET['id_pregunta'];
$enunciado 	= strtoupper($_GET['enunciado']);
$enunciado_m= $_GET['enunciado_mostrar'];
$id_resp  	= $_GET['id_resp'];
		
$tabla_pregunta = mysqli_query($con,"update preguntas set enunciado = '$enunciado', id_tipo_respuesta = $id_resp, enunciado_mostrar = '$enunciado_m' where id_pregunta = $id_pregunta") or die ('Revisar Edicion Preguntas');
												
mysqli_close($con);

header('location:padron_preguntas.php?id_encuesta='.$_SESSION['id_encuesta']);
?>
