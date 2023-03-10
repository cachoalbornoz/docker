<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require_once('../accesorios/accesos_bd.php');

$con=conectar();

$id_encuesta = $_POST['id_no_importada'];
$id_importada = $_POST['id_importada'];
////

chdir('../mapas/marcadores');

$marcadores_actual = "marcadores_".$id_encuesta.".xml";
$marcadores_import = "marcadores_".$id_importada.".xml";

rename($marcadores_actual, $marcadores_import);
			
//BORRAR LAS RESPUESTAS DE LOS MAESTROS (SI LA TABLA NO ES IMPORTADA)
$seleccion_maestro = mysqli_query($con, "select id_maestro from maestro_encuestas where id_encuesta = $id_encuesta");
while($registro_maestro = mysqli_fetch_array($seleccion_maestro)){ 
    $id_maestro = $registro_maestro[0];	
    //BORRAR RESULTADOS DE LA ENCUESTA ACTUAL
    mysqli_query($con, "delete from maestro_respuestas_abiertas where id_maestro = $id_maestro");
    mysqli_query($con, "delete from maestro_respuestas_cerradas where id_maestro = $id_maestro");
    mysqli_query($con, "delete from maestro_respuestas_cruzadas where id_maestro = $id_maestro");
    mysqli_query($con, "delete from maestro_respuestas_edad where id_maestro = $id_maestro");
}

//BORRAR POSICIONES DE LA ENCUESTA ACTUAL
mysqli_query($con, "delete from maestro_posiciones where id_encuesta = $id_encuesta");

//BORRAR REGISTROS DE LA ENCUESTA ACTUAL
mysqli_query($con, "delete from maestro_encuestas where id_encuesta = $id_encuesta");
mysqli_query($con, "delete from archivos_importados where id_encuesta = $id_encuesta");
mysqli_query($con, "delete from encuestas where id_encuesta = $id_encuesta"); 

//BORRAR REGISTROS PREGUNTAS
$seleccion_preguntas = mysqli_query($con, "select id_pregunta from preguntas where id_encuesta = $id_encuesta");				
while($registro_preguntas = mysqli_fetch_array($seleccion_preguntas)){
    $id_pregunta = $registro_preguntas[0];
    mysqli_query($con, "delete from respuestas where id_pregunta = $id_pregunta");
    mysqli_query($con, "delete from preguntas where id_pregunta = $id_pregunta");
}
?>