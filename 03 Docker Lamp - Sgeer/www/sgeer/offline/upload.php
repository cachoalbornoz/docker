<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/accesos_bd.php');
$con=conectar();

include('../accesorios/encabezado.php');

?>

        <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sx-12 col-md-12">
                            RESUMEN DEL PROCESO
                        </div>
                    </div>
                </div>
            <div class="panel-body">
                <table class="table table-hover">  
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <?php

                $contador = 1;

                for($i=0; $i<count($_FILES['uploads']['name']); $i++) {

                    $nombre = $_FILES['uploads']['name'][$i];

                    if(move_uploaded_file($_FILES['uploads']['tmp_name'][$i],"files/".$nombre)){ ////Comprobamos si el archivo subio	
                        $nombre_archivo = "files/".$nombre;
                        $archivo = fopen($nombre_archivo,'r') or die('No se puede leer '.$nombre);
                        // RECORRO EL ARCHIVO DE RESPUESTAS
                        while (!feof($archivo)){
                            $detalle = explode(';',fgets($archivo));                

                            if(isset($detalle[0]) and isset($detalle[1])){///

                                $latitud    = $detalle[0];
                                $longitud   = $detalle[1];
                                $id_encuesta= $detalle[2];
                                $id_usuario = $detalle[3];

                                $insert = "insert into maestro_posiciones (id_encuesta, id_usuario, latitud, longitud, nro_respuesta) values ($id_encuesta, $id_usuario, '$latitud', '$longitud', $contador)";
                                mysqli_query($con, $insert) or die('Revisar insercion de posicion' . $insert);

                                mysqli_query($con,"insert into maestro_encuestas (id_usuario, id_encuesta) values ($id_usuario, $id_encuesta)") or die("No pudo registrarse el inicio de la encuesta");

                                $id_maestro = mysqli_insert_id($con);			

                                $indice = 4;

                                $preguntas = mysqli_query($con, "select id_pregunta, id_tipo_respuesta, nro from preguntas where id_encuesta = $id_encuesta order by nro");
                                while($registro_preguntas = mysqli_fetch_array($preguntas)){
                                    $id_pregunta 	= $registro_preguntas[0];
                                    $id_tipo_respuesta 	= $registro_preguntas[1];
                                    $nro_pregunta 	= $registro_preguntas[2];

                                    $respuesta = $detalle[$indice];

                                    if($id_tipo_respuesta == 1){
                                        // RESPUESTA ABIERTA
                                        mysqli_query($con, "insert into maestro_respuestas_abiertas (id_maestro, id_pregunta, nro_pregunta, respuesta) values ($id_maestro, $id_pregunta, $nro_pregunta, '$respuesta')");
                                    }else{
                                        if($id_tipo_respuesta == 2 or $id_tipo_respuesta == 3 or $id_tipo_respuesta == 5 or $id_tipo_respuesta == 6 or $id_tipo_respuesta == 7 or $id_tipo_respuesta == 8 or $id_tipo_respuesta == 9 or $id_tipo_respuesta == 11 or $id_tipo_respuesta == 12){
                                            // RESPUESTA MULTIPLES OPCIONES // MB/B/RB/RM/M/MM/NsNc // EDAD // NIVEL INSTRUCCION OCUPACION // ZONAS // SI/NO/NsNc
                                            mysqli_query($con, "insert into maestro_respuestas_cerradas (id_maestro, id_pregunta, nro_pregunta, id_respuesta) values ($id_maestro, $id_pregunta, $nro_pregunta, $respuesta)");
                                        }else{
                                            if($id_tipo_respuesta == 4){
                                                // RESPUESTAS CRUZADAS
                                                $check = explode(',',$respuesta);

                                                for($x=0;$x<count($check);$x++) {

                                                    $resp =  explode('-',$check[$x]);

                                                    if(isset($resp[0]) & $resp[0]> 0){
                                                        mysqli_query($con, "insert into maestro_respuestas_cerradas (id_maestro, id_pregunta, nro_pregunta, id_respuesta) values ($id_maestro, $id_pregunta, $nro_pregunta, $resp[0])") or die ('Revisar Resp Cruzadas 0');
                                                        mysqli_query($con, "insert into maestro_respuestas_cerradas (id_maestro, id_pregunta, nro_pregunta, id_respuesta) values ($id_maestro, $id_pregunta, $nro_pregunta, $resp[1])") or die ('Revisar Resp Cruzadas 1');  
                                                    }                                                                            
                                                } 
                                            }else{
                                                if($id_tipo_respuesta == 10){ 
                                                    // RESPUESTAS CHECK
                                                    $check = explode(',',$respuesta);

                                                    for($x=0;$x<count($check);$x++) {
                                                        mysqli_query($con, "insert into maestro_respuestas_cerradas (id_maestro, id_pregunta, nro_pregunta, id_respuesta) values ($id_maestro, $id_pregunta, $nro_pregunta, $check[$x])");
                                                    }                                  
                                                }                                   
                                            }
                                        }
                                    }	
                                $indice ++;
                                }
                            }

                        }
                        chmod("files/".$_FILES['uploads']['name'][$i],0777);
                        fclose($archivo);
                        echo "<tr><td><b> ".$_FILES['uploads']['name'][$i]."</b> tamaño ".$_FILES['uploads']['size'][$i]."bytes.</td></tr>"; 
                        $contador ++;
                    }
                }    

                unlink("files/".$nombre); 

                if($contador > 0){ // CARGAR LAS POSICIONES AL ARCHIVOS MARCADORES

                    $nombre_marcador = 'marcadores_'.$id_encuesta.'.xml'; 

                    if (file_exists('../mapas/marcadores/'.$nombre_marcador)) {
                        unlink('../mapas/marcadores/'.$nombre_marcador); 
                    }       

                    $seleccion_marcadores = mysqli_query($con, "select latitud,longitud,color,mae.id_usuario from maestro_posiciones as mae, usuarios as usu
                    where usu.id_usuario = mae.id_usuario and latitud < 0 and mae.id_encuesta = $id_encuesta") or die('Revisar Lectura de Posiciones');

                    if(mysqli_num_rows($seleccion_marcadores)> 0){            

                        $ar=fopen('../mapas/marcadores/'.$nombre_marcador,'w+');

                        $detalle = '<?xml version="1.0" encoding="utf-8" ?>';
                        fputs($ar, $detalle);
                        fputs($ar,"\r\n");
                        $detalle = '<marcadores>';
                        fputs($ar, $detalle);
                        fputs($ar,"\r\n");

                        while($registro_marcadores = mysqli_fetch_array($seleccion_marcadores)){
                            $detalle = "\t" .'<marcador>'; fputs($ar, $detalle);fputs($ar,"\r\n");

                            $detalle = "\t \t" .'<latitud>'.round($registro_marcadores[0],8).'</latitud>';	fputs($ar, $detalle); fputs($ar,"\r\n");
                            $detalle = "\t \t" .'<longitud>'.round($registro_marcadores[1],8).'</longitud>';fputs($ar, $detalle); fputs($ar,"\r\n");
                            $detalle = "\t \t" .'<color>'.$registro_marcadores[2].'</color>';fputs($ar, $detalle); fputs($ar,"\r\n");
                            $detalle = "\t \t" .'<usuario>'.$registro_marcadores[3].'</usuario>';fputs($ar, $detalle); fputs($ar,"\r\n");

                            $detalle = "\t" .'</marcador>'; fputs($ar, $detalle);	fputs($ar,"\r\n");	
                        }

                        $detalle = '</marcadores>';
                        fputs($ar, $detalle);
                        fputs($ar,"\r\n");

                        fclose($ar);
                    }
                }

                $contador --;
                ?> 
                <tr>
                    <td>
                        &nbsp;
                    </td>
                </tr>
                <tr class="bg-info">
                    <td><?php echo "Total ". $contador." archivo/s importado/s correctamente" ; ?></td>
                </tr>
                </table>
            </div>
        </div>    


<?php 
    mysqli_close($con);
    require_once '../accesorios/footer.php';