<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/accesos_bd.php');
$con=conectar();


$id_encuesta = $_SESSION['id_encuesta'];


if(isset($_POST['operacion'])){	
    if($_POST['operacion'] == 1){
           
        $nombre         = $_POST['nombre'];
        $nro_pregunta   = $_POST['nro_pregunta'];
        
        mysqli_query($con, "insert into paginadores (nombre, nro_pregunta) values ('$nombre',$nro_pregunta)");
        
    }else{
        if($_POST['operacion'] == 2){
            
           $id= $_POST['id_marcador']; 
           
           mysqli_query($con, "delete from paginadores where id = $id");
        }	
    }
}

?>

<table class="table table-hover">
    <tr class="bg-info">
        <td>#</td>
        <td>Concepto</td>
        <td>Nro Pregunta</td>
        <td>&nbsp;</td>
    </tr>
    <?php
    $contador = 1;
    $tabla_encuestas = mysqli_query($con, "select * from paginadores order by nro_pregunta");
    while($registro_encuestas = mysqli_fetch_array($tabla_encuestas)){?>
    <tr>
        <td><?php echo $contador; ?></td>
        <td><?php echo $registro_encuestas[1] ?></td>
        <td><?php echo $registro_encuestas[2] ?></td>
        <td class="text-right">
            <a href="javascript:void(0)" title="Elimina marcador" 
               onclick="eliminar_marcador(<?php echo $registro_encuestas[0] ?>,'<?php echo $registro_encuestas[1] ?>')">
                <span class="glyphicon glyphicon-trash"></span>
            </a>
        </td>
    </tr>
    <?php
    $contador ++;
    }
    ?>
</table>

<?php 
mysqli_close($con); 