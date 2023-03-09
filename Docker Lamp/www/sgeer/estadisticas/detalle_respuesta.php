<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/accesos_bd.php');

$con=conectar();

$id_pregunta = $_SESSION['id_pregunta'];

if(isset($_POST['operacion'])){	
    if($_POST['operacion'] == 1){
        $tabla_respuesta = mysqli_query($con, "select max(orden) from respuestas where id_pregunta = $id_pregunta"); 
        if($registro_respuesta = mysqli_fetch_array($tabla_respuesta)){
            $orden = $registro_respuesta[0] + 1;
        }else{
            $orden = 1;
            
        }
        $enunciado 	= strtoupper($_POST['enunciado']);
        $valor_spss	= $_POST['valor_spss'];
        $fila		= $_POST['fila'];
        mysqli_query($con, "insert into respuestas (id_pregunta, enunciado, fila, orden, valor_spss) values ($id_pregunta, '$enunciado', $fila, $orden, $valor_spss)") or die('Revisar ingreso de respuestas');
        
        $id_tipo = $_POST['id_tipo'];
        
        if($orden == 1){ // AGREGA AUTOMATICAMENTE UNA RESPUESTA Ns/Nc 
            
            if($id_tipo == 2){ // SI EL TIPO DE PREGUNTA ES OPCIONES MULTIPLES
                mysqli_query($con, "insert into respuestas (id_pregunta, enunciado, fila, orden, valor_spss) values ($id_pregunta, 'NsNc', $fila, 99, 99)") or die('Revisar ingreso de respuestas');
            }            
        }        
        
    }else{
        if($_POST['operacion'] == 2){
            
            $cadena = explode(',',$_POST['cadena']);
    
            foreach ($cadena as $id) {         
                
                $id_respuesta = $id;
                mysqli_query($con, "delete from respuestas where id_respuesta = $id_respuesta");
            }            
            
        }else{
            $enunciado = strtoupper($_POST['enunciado']);
            $fila      = $_POST['fila'];
            if($fila == 1){
                $tabla_respuesta = mysqli_query($con, "select max(orden) from respuestas where id_pregunta = $id_pregunta and fila = 1"); 
                if($registro_respuesta = mysqli_fetch_array($tabla_respuesta)){
                    $orden = $registro_respuesta[0] + 1;
                }else{
                    $orden = 1;
                }
            }else{
                $tabla_respuesta = mysqli_query($con, "select max(orden) from respuestas where id_pregunta = $id_pregunta and fila = 2"); 
                if($registro_respuesta = mysqli_fetch_array($tabla_respuesta)){
                    $orden = $registro_respuesta[0] + 1;
                }else{
                    $orden = 1;
                }
            }
            mysqli_query($con, "insert into respuestas (id_pregunta, enunciado, orden, fila) values ($id_pregunta, '$enunciado', $orden, $fila)") or die('Revisar ingreso de respuestas cruzadas');
        }		
    }
    
    //REORDENAR ORDEN DE RESPUESTA 
    
    $orden = 1;
    $tabla_respuestas = mysqli_query($con, "select id_respuesta, orden from respuestas where id_pregunta = $id_pregunta order by valor_spss asc");
    while($registro_respuestas = mysqli_fetch_array($tabla_respuestas)){
        $id_respuesta = $registro_respuestas[0];
        mysqli_query($con, "update respuestas set orden = $orden where id_respuesta = $id_respuesta");
        $orden = $orden + 1;        
    }   
}

?>

<table class="table table-hover">
<tr class="bg-info">
    <td>
        <a href="#" onclick="elimina_respuesta()">
            <span class="glyphicon glyphicon-trash">  </span> 
        </a>
    </td>
    <td>ORDEN</td>
    <td>RESPUESTA</td>
    <td>VALOR SPSS<b></b></td>
    <td>TIPO ENCABEZADO</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<?php

$tabla_respuestas = mysqli_query($con, "select * from respuestas where id_pregunta = $id_pregunta order by orden desc");
while($registro_respuestas = mysqli_fetch_array($tabla_respuestas)){
    ?>
    <tr onmouseover="this.style.backgroundColor='#CCCCCC'" onmouseout="this.style.backgroundColor='#FFFFFF'">
        <td><input type="checkbox" name="respuesta[]" value="<?php echo $registro_respuestas[0] ?>"></td>
        <td><?php echo $registro_respuestas[3] ?></td>
        <td><?php echo $registro_respuestas[2] ?></td>
        <td><?php echo $registro_respuestas[5] ?></td>
        <td>
        <?php 

        if($registro_respuestas[4] == 1){
            echo "Valor Fila" ;
        }else{
            if($registro_respuestas[4] == 2){
                echo "Valor Columna" ;
            }else{
                if($registro_respuestas[4] == 3){
                    echo "Encabezado Fila" ;
                }else{
                    if($registro_respuestas[4] == 4){
                        echo "Encabezado Columna" ;
                    }else{
                        echo "---" ;
                    }
                }                
            }
        }
        ?>
        </td>
        <td><a href='javascript:void(0)' title='Modifica respuesta' onClick="modifica_respuesta('<?php echo $registro_respuestas[0] ?>')"><span class="glyphicon glyphicon-pencil"></span></a></td>
    </tr>
    <?php
}

?>
</table>

<?php
mysqli_close($con);
?>