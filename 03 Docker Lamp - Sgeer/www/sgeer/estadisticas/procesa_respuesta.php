<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/accesos_bd.php');
date_default_timezone_set('America/Buenos_Aires');

$con=conectar();

$id_maestro  = $_SESSION['id_maestro'];
$id_encuesta = $_SESSION['id_encuesta'];

$id_pregunta 		= $_POST['id_pregunta']; 
$id_tipo_respuesta 	= $_POST['tipo_respuesta'];

$nro_pregunta 		= $_POST['nro'];
$respuesta			= $_POST['res'];
$id_respuesta_fila		= $_POST['resf'];
$id_respuesta_columna	= $_POST['resc'];

if($id_tipo_respuesta ==  4){// PREGUNTA CRUZADA
	$tipo_operacion = $_POST['tipo'];
    if($tipo_operacion == 1 or ($id_respuesta_fila == 99 and $id_respuesta_columna == 99)){//AGREGA RESPUESTA
        $seleccion_respuesta = mysqli_query($con, "select id_maestro from maestro_respuestas_cruzadas where id_maestro = $id_maestro and id_pregunta = $id_pregunta and id_respuesta_fila = $id_respuesta_fila and id_respuesta_columna = $id_respuesta_columna");
        if(mysqli_num_rows($seleccion_respuesta) == 0){
            mysqli_query($con, "insert into maestro_respuestas_cruzadas (id_maestro, id_pregunta, nro_pregunta, id_respuesta_fila, id_respuesta_columna) values ($id_maestro, $id_pregunta, $nro_pregunta, $id_respuesta_fila, $id_respuesta_columna)");
        }				
    }else{//ELIMINA RESPUESTA INDIVIDUAL
        mysqli_query($con, "delete from maestro_respuestas_cruzadas where id_maestro = $id_maestro and id_pregunta = $id_pregunta and id_respuesta_fila = $id_respuesta_fila and id_respuesta_columna = $id_respuesta_columna");
    }
}else{
    if($id_tipo_respuesta == 5){// EDAD
        $seleccion_respuesta = mysqli_query($con, "select id_maestro_resp from maestro_respuestas_edad where id_maestro = $id_maestro");
        if(mysqli_num_rows($seleccion_respuesta) > 0){
            mysqli_query($con, "update maestro_respuestas_edad set edad = $respuesta where id_maestro = $id_maestro");			
        }else{
            mysqli_query($con, "insert into maestro_respuestas_edad (id_maestro, edad) values ($id_maestro, $respuesta)");
        }
    }else{// RESPUESTA ABIERTA
        if($id_tipo_respuesta == 1){
            $seleccion_respuesta = mysqli_query($con, "select id_maestro from maestro_respuestas_abiertas where id_maestro = $id_maestro and id_pregunta = $id_pregunta");
            if(mysqli_num_rows($seleccion_respuesta) > 0){
                mysqli_query($con, "update maestro_respuestas_abiertas set respuesta = $respuesta where id_maestro = $id_maestro and id_pregunta = $id_pregunta");			
            }else{
                mysqli_query($con, "insert into maestro_respuestas_abiertas (id_maestro, id_pregunta, nro_pregunta, respuesta) values ($id_maestro, $id_pregunta, $nro_pregunta, '$respuesta')");
            }
        }else{// OTRO TIPO RESPUESTAS
            if($id_tipo_respuesta == 6){
                if($respuesta == 2){
                    $_SESSION['mujer']=1;
                    $_SESSION['hombre']=0;					
                }else{
                    $_SESSION['hombre']=1;
                    $_SESSION['mujer']=0;
                }
            }
            $seleccion_respuesta = mysqli_query($con, "select id_maestro from maestro_respuestas_cerradas where id_maestro = $id_maestro and id_pregunta = $id_pregunta");
            if(mysqli_num_rows($seleccion_respuesta) > 0){
                    mysqli_query($con, "update maestro_respuestas_cerradas set id_respuesta = $respuesta where id_maestro = $id_maestro and id_pregunta = $id_pregunta");			
            }else{
                mysqli_query($con, "insert into maestro_respuestas_cerradas (id_maestro, id_pregunta, nro_pregunta, id_respuesta) values ($id_maestro, $id_pregunta, $nro_pregunta, $respuesta)");
            }
        }
    }
}
// AGREGA RESPUESTA ABIERTA 		
if($id_respuesta_fila == 99 and $id_respuesta_columna == 99){
    mysqli_query($con, "insert into maestro_respuestas_abiertas (id_maestro, id_pregunta, nro_pregunta, respuesta) values ($id_maestro, $id_pregunta, $nro_pregunta, '$respuesta')");
}

mysqli_close($con);
