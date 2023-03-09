<?php
ini_set('max_execution_time', 0);
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}

require('../accesorios/accesos_bd.php');

$con=conectar();

$nombre_archivo = $_FILES['archivo']['tmp_name'];

$archivo = fopen($nombre_archivo,'r') or $mensaje ='No se puede leer '.$nombre_archivo;

$nombre_encuesta = explode('.',$_FILES['archivo']['name']);

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

$registros = 0;

while(!feof($archivo)){ // CARGAR DESDE EL ARCHIVO LOS VALORES DE LAS RESPUESTAS     
   
    if($registros == 0){
        $cabecera = explode(';',fgets($archivo));
        $nro_campos = count($cabecera); 
        
        // LIMPIA LAS PREGUNTAS QUE ESTAN CARGADAS
        mysqli_query($con, "delete from maestro_preguntas where id_encuesta = $id_encuesta");
            
        // CARGAR DESDE EL ARCHIVO LAS PREGUNTAS AL MAESTRO
        $contador = 1;
        for ($i = 0; $i < $nro_campos; $i++){    
            $campo = strtoupper(trim($cabecera[$i]));   
            mysqli_query($con, "insert into maestro_preguntas (id_encuesta, nro_pregunta, campo) values ($id_encuesta, $contador, '$campo')"); 
            $contador ++;
        }         
        
        // BORRAR TABLA TEMPORAL
        mysqli_query($con, "DROP TABLE maestro_importacion_temp");
        
        // CREAR TABLA TEMPORAL CON LOS CAMPOS DEL ARCHIVO IMPORTADO
        mysqli_query($con, "CREATE TABLE maestro_importacion_temp (ID_MAESTRO INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY) COLLATE='utf8_spanish_ci' ENGINE = InnoDB;") or die("Revisar creacion tabla temporal");
        for ($i = 0; $i < $nro_campos; $i++){
            $campo = strtoupper(trim($cabecera[$i]));  
            mysqli_query($con, "ALTER TABLE maestro_importacion_temp ADD $campo INT(2) NOT NULL;") or die("Revisar Agregado Campos");    
        }         
        
    }else{
        $cuerpo = explode(';',fgets($archivo));
        if(!feof($archivo)){
            // CARGAR VALORES DESDE EL ARCHIVO LAS PREGUNTAS AL MAESTRO
            
            $insercion = "insert into maestro_importacion_temp ("; 
            
            for ($i = 0; $i < $nro_campos; $i++){  
                $campo = strtoupper(trim($cabecera[$i]));  
                $insercion .= "$campo,";
            }
            $insercion = substr($insercion, 0, -1);
            
            $insercion .= ") values (";
            
            for ($i = 0; $i < $nro_campos; $i++){    
                $insercion .= "$cuerpo[$i],";
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

        if(isset($registro_importacion[0])){$id_valor = $registro_importacion[0];}else{$id_valor = 0;}     
        if(isset($registro_importacion[1])){$cantidad = $registro_importacion[1];}else{$cantidad = 0;}   

        mysqli_query($con, "insert into maestro_importacion (id_encuesta, id_pregunta, id_valor, cantidad)
        values ($id_encuesta,$id_pregunta,$id_valor,$cantidad)") or die('Revisar importacion'); 
        
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
        <td><b> IMPORTACION DATOS</b></td>
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