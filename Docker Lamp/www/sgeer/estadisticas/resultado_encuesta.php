<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/accesos_bd.php');
$con=conectar();

$tabla_encuestas = mysqli_query($con, "select id_encuesta, nombre from encuestas where estado = 1");

if($registro_encuestas = mysqli_fetch_array($tabla_encuestas)){
    $id_encuesta= $registro_encuestas[0];
    $nombre 	= $registro_encuestas[1];
}

$tabla_casos = mysqli_query($con, "select count(id_encuesta) from maestro_encuestas where id_encuesta = $id_encuesta");
$registro_casos = mysqli_fetch_array($tabla_casos);
$nro_casos = $registro_casos[0];

require_once('../accesorios/encabezado.php');

?>

<div class="panel panel-primary"> 
    <div class="panel-heading">
        <div class="row">
            <div class="col-sx-12 col-md-12">
                <?php echo $nombre ?> :: Resúmen sobre un total de (<b><?php echo $nro_casos ?></b>) encuestas
            </div>            
        </div>
    </div>
    <div class="panel-body"> 
    
        <?php

        $id_pregunta	= $_GET['id_pregunta'];
        $tipo_pregunta	= $_GET['tipo_pregunta'];

        $tabla_preguntas = mysqli_query($con, "select id_pregunta, enunciado from preguntas where id_pregunta = $id_pregunta");
        $registro_preguntas = mysqli_fetch_array($tabla_preguntas); ?>
        
        <div class="row">
            <div class="col-sx-12 text-center"><h4> <?php echo $registro_preguntas[1] ; ?> </h4> </div>
        </div>
        <br>
        <?php
        if($tipo_pregunta == 4){    // RESPUESTA TIPO TABLA CRUZADA       

            $tabla_preguntas = mysqli_query($con, "select cerr.id_respuesta, resp.enunciado from preguntas preg 
            inner join maestro_respuestas_cerradas cerr on preg.id_pregunta = cerr.id_pregunta 
            inner join respuestas resp on cerr.id_respuesta = resp.id_respuesta and preg.id_pregunta = $id_pregunta and preg.id_tipo_respuesta = 4 order by nro");
            while($registro_preguntas = mysqli_fetch_array($tabla_preguntas)){ 	

                $id_respuesta_ant   = $registro_preguntas[0];              
                $enunciado_ant = $registro_preguntas[1]; 

                $registro_preguntas = mysqli_fetch_array($tabla_preguntas);
                $valores[] = $enunciado_ant .' - '.$registro_preguntas[1];                
            }    
            
            sort($valores);           
            
            function contarValoresArray($array){
                $contar=array();

                foreach($array as $value){
                    if(isset($contar[$value])){
                        $contar[$value]+=1;
                    }else{
                        $contar[$value]=1;
                    }
                }
                return $contar;
            }
            
            
            print_r(contarValoresArray($valores));
            
            echo "</br>";
            
        }else{                      // RESPUESTA TIPO ABIERTA
        ?>  
        <table class="table"> 
        <tr>
            <td>
            <?php
            $tabla_respuestas_abiertas = mysqli_query($con, "select respuesta from maestro_respuestas_abiertas where id_pregunta = $id_pregunta");
            while($registro_respuestas_abiertas = mysqli_fetch_array($tabla_respuestas_abiertas)){ 
                
                if($registro_respuestas_abiertas[0] <> '-'){ //PARA QUE NO SE IMPRIMAN LOS VALORES POR DEFECTO
                    echo $registro_respuestas_abiertas[0].", \t" ;
                }                
            }	
            ?>	
            </td>
        </tr>	
        <tr>
            <td>&nbsp;</td>
        </tr>
        <?php
        }
        ?>  
        </table>
    </div>    
</div>

<?php 
mysqli_close($con); 
require_once '../accesorios/footer.php';

