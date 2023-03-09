<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/accesos_bd.php');

$con=conectar();

$nombre_archivo = $_FILES['etiquetas']['tmp_name'];
$archivo = fopen($nombre_archivo,'r') or die('No se puede leer '.$nombre_archivo);
$nombre_encuesta = explode('.',$_FILES['etiquetas']['name']);
$nombre_encuesta = strtoupper($nombre_encuesta[0]);


$seleccion = mysqli_query($con, "select id_encuesta from encuestas where nombre like '$nombre_encuesta' and estado = 9");
if($registro = mysqli_fetch_array($seleccion)){
    $id_encuesta = $registro[0];
    mysqli_query($con, "update encuestas set fecha = NOW() where id_encuesta = $id_encuesta") or $mensaje="Revisar actualización tabla importada existente";	
}else{
    $inserta_encuesta = mysqli_query($con, "insert into encuestas (nombre, estado, importada) values ('$nombre_encuesta', 9, 1)");
    $id_encuesta = mysqli_insert_id($con);
}

$_SESSION['id_encuesta'] = $id_encuesta;

// BUSCAR ID de ENCUESTA ACTIVA
$consulta           = mysqli_query($con, "select id_encuesta from encuestas where estado = 1") or die('Revisar Encuesta Activa'); 
$registro           = mysqli_fetch_array($consulta);
$id_encuesta_activa = $registro[0];

$registros = 0;

while(!feof($archivo)){ // CARGAR DESDE EL ARCHIVO LOS VALORES DE LAS RESPUESTAS     
   
    if($registros == 0){
        $cabecera = explode(';',fgets($archivo));
        $nro_campos = count($cabecera);
        
        // BORRAR TABLA TEMPORAL
        mysqli_query($con, "drop table maestro_importacion_temp");
        
        // LIMPIA LAS PREGUNTAS QUE ESTAN CARGADAS
        mysqli_query($con, "delete from maestro_preguntas where id_encuesta = $id_encuesta_activa");
            
        // CARGAR DESDE EL ARCHIVO LAS PREGUNTAS AL MAESTRO
        $contador = 1;                
        for ($i = 0; $i < $nro_campos; $i++){   
            
            $consulta_preg = mysqli_query($con, "select * from preguntas where nro = $contador and id_encuesta = $id_encuesta_activa order by nro") or die('Revisar Lectura Preg'); 
            $registro_preg = mysqli_fetch_array($consulta_preg);
            
            $campo    = str_replace(" ", "_", $registro_preg[5]); // REEMPLAZA ESPACIOS X GUIONES
            $campo    = str_replace(".", "_", $campo);
            $campo    = strtoupper($campo);            // PONE MAYUSC - Y QUITAR ESPACIOS DELANTE Y ATRAS             
            $tipo     = $registro_preg[2];
            
            mysqli_query($con, "insert into maestro_preguntas (id_encuesta, nro_pregunta, campo, id_tipo_respuesta) values ($id_encuesta, $contador, '$campo',$tipo)") or die('Revisar Proceso Importacion'); 
           
            $contador ++;
        }         
        
        // CREAR TABLA TEMPORAL CON LOS CAMPOS DEL ARCHIVO IMPORTADO
        mysqli_query($con, "CREATE TABLE maestro_importacion_temp (ID_MAESTRO INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY) COLLATE='utf8_spanish_ci' ENGINE = InnoDB;") 
                or die("Revisar creacion tabla temporal");
        $contador = 1; 
        for ($i = 0; $i < $nro_campos; $i++){
            $consulta_preg = mysqli_query($con, "select * from preguntas where nro = $contador and id_encuesta = $id_encuesta_activa order by nro") or die('Revisar Lectura Preg'); 
            $registro_preg = mysqli_fetch_array($consulta_preg);
            
            $campo    = str_replace(" ", "_", $registro_preg[5]); // REEMPLAZA ESPACIOS X GUIONES
            $campo    = str_replace(".", "_", $campo);
            $campo    = strtoupper($campo);    
            mysqli_query($con, "ALTER TABLE maestro_importacion_temp ADD $campo VARCHAR(50) NOT NULL;") or die("Revisar Agregado Campos");    
        
            $contador ++;
        }       
    }else{
        $cuerpo = explode(';',fgets($archivo));
        if(!feof($archivo)){
            // CARGAR VALORES DESDE EL ARCHIVO LAS PREGUNTAS AL MAESTRO
            
            $insercion = "insert into maestro_importacion_temp (";
            
            $contador = 1; 
            for ($i = 0; $i < $nro_campos; $i++){  
                $consulta_preg = mysqli_query($con, "select * from preguntas where nro = $contador and id_encuesta = $id_encuesta_activa order by nro") or die('Revisar Lectura Preg'); 
                $registro_preg = mysqli_fetch_array($consulta_preg);

                $campo    = str_replace(" ", "_", $registro_preg[5]); // REEMPLAZA ESPACIOS X GUIONES
                $campo    = str_replace(".", "_", $campo);
                $campo    = strtoupper($campo);  
            
                $insercion .= "$campo,";
                
                $contador ++;
            }
            $insercion = substr($insercion, 0, -1);
            
            $insercion .= ") values (";
            
            for ($i = 0; $i < $nro_campos; $i++){    
                $insercion .= "'".lcadena(trim($cuerpo[$i]))."',";
            }
            $insercion = substr($insercion, 0, -1);  // Quitar la ultima coma
            
            $insercion .= ")";
            
            try{
                mysqli_query($con, $insercion);

            } catch (Exception $ex) {
                echo "Revisar linea $registros";
                $registros --;
            }  
        } 
    }
    $registros ++;
}


fclose($archivo);

// PASAR LOS DATOS AL MAESTRO DE IMPORTACION

$tabla_preguntas = mysqli_query($con, "select id_pregunta, campo from maestro_preguntas where id_encuesta = $id_encuesta order by nro_pregunta");

while($registro_preguntas = mysqli_fetch_array($tabla_preguntas)){
    
    $id_pregunta= $registro_preguntas[0];
    $campo      = $registro_preguntas[1];
    
    $tabla_importacion = mysqli_query($con,"select $campo, count(id_maestro) from maestro_importacion_temp group by $campo");
    
    $registros = 0;
    
    while($registro_importacion = mysqli_fetch_array($tabla_importacion)){

        if(isset($registro_importacion[0])){$valor = sanear_string($registro_importacion[0]);}else{$valor = 0;}     
        if(isset($registro_importacion[1])){$cantidad = $registro_importacion[1];}else{$cantidad = 0;}   

        mysqli_query($con, "insert into maestro_etiquetas (id_encuesta, id_pregunta, valor, cantidad)
        values ($id_encuesta,$id_pregunta,'$valor',$cantidad)") or die('Revisar importacion'); 
        
        $registros = $registros + $cantidad;
    }
}

// BORRAR DATOS TEMPORALES 
mysqli_query($con, "drop table maestro_importacion_temp");

$mensaje = $nombre_encuesta." <b>Ok</b>. "." Campos : <b>".$nro_campos."</b> Registros : <b>".$registros."</b> correctamente importados !";

include('../accesorios/encabezado.php');

?>


    
    <table class="table table-striped">
        <tr>
            <td><b> IMPORTACION DE ETIQUETAS</b></td>
            <td>&nbsp;</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3"><?php echo $mensaje ?></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
    </table>    


<?php 

mysqli_close($con); 
require_once '../accesorios/footer.php';