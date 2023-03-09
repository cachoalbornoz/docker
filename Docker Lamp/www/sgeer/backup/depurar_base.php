<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require_once('../accesorios/accesos_bd.php');

$con=conectar();

$tabla_encuestas = mysqli_query($con, "select id_encuesta from encuestas where estado = 1");
$contador = 0;

while($registro_encuestas = mysqli_fetch_array($tabla_encuestas)){

    $id_encuesta = $registro_encuestas[0];

    $tabla_preguntas =  mysqli_query($con, "select max(nro) from preguntas where id_encuesta = $id_encuesta;");
    $registro_preguntas = mysqli_fetch_array($tabla_preguntas);
    $nro_preguntas = $registro_preguntas[0];

    $tabla_depuracion = mysqli_query($con,"select id_maestro from maestro_encuestas where id_encuesta = $id_encuesta and total_respuestas < $nro_preguntas");	


    while($registro_depuracion = mysqli_fetch_array($tabla_depuracion)){

        $id_maestro = $registro_depuracion[0];

        mysqli_query($con, "delete from maestro_respuestas_abiertas where id_maestro = $id_maestro");
        mysqli_query($con, "delete from maestro_respuestas_cerradas where id_maestro = $id_maestro");
        mysqli_query($con, "delete from maestro_respuestas_cruzadas where id_maestro = $id_maestro");
        mysqli_query($con, "delete from maestro_respuestas_edad where id_maestro = $id_maestro");

        mysqli_query($con, "delete from maestro_encuestas where id_maestro = $id_maestro");

        $contador ++;		
    }

}

echo "Total de encuestas eliminadas sin finalizar : <b>$contador</b>";
mysqli_close($con);
?>

