<?php 
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}

require('../accesorios/accesos_bd.php');

$con=conectar();


$id_encuesta = $_GET['id_encuesta'];
$tabla_encuesta = mysqli_query($con,"select * from encuestas where id_encuesta = $id_encuesta");
$registro_encuesta = mysqli_fetch_array($tabla_encuesta);

$nombre_encuesta = $registro_encuesta[1];
$estado = $registro_encuesta[2];

include('../accesorios/encabezado.php');

?>

<script type="text/javascript">

    $(document).ready(function(){
        $("#nombre_encuesta").select();

    });

    function modificar(id){
        var enunciado = document.getElementById('nombre_encuesta').value;
        var est	  = document.getElementById('estado').value;
        window.location = ('edita_encuesta.php?id_encuesta=' + id + '&enunciado=' + enunciado + '&estado=' + est);	
    }
</script>


<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sx-12 col-md-12">
                MODIFICAR NOMBRE / ESTADO - ENCUESTA <?php echo strtoupper($nombre_encuesta) ?>
            </div>
            
        </div>
    </div>
    <div class="panel-body">
        
        
  		
    <table class="table">
        <tr>
            <td>NOMBRE DE LA ENCUESTA</td>
            <td>ESTADO</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <input type="text" id="nombre_encuesta" name="nombre_encuesta" value="<?php echo $nombre_encuesta ?>" class="form-control">
            </td>
            <td>
                <select name="estado" id="estado" size="1" class="form-control">
                <?php
                $estados = "select id_estado, estado from estado_encuesta order by id_estado";
                $registro = mysqli_query($con, $estados); 
                while($fila = mysqli_fetch_array($registro)){
                    echo "<option value=\"".$fila[0]."\">".$fila[1]."</option>\n";
                }	
                mysqli_free_result($registro);	
                ?>            
                </select>
            </td>
            <td class="text-right">
                <input type="button" value="Modificar" onClick="modificar('<?php echo $id_encuesta ?>')" class="btn btn-info">
            </td>
        </tr>        
    </table> 
        
    </div>
</div>


<?php
mysqli_close($con);
require_once '../accesorios/footer.php'; 
