<?php 
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}

require('../accesorios/accesos_bd.php');

$con=conectar();

$id_encuesta =  $_GET['id_encuesta'];

$tabla_encuestas = mysqli_query($con, "select * from encuestas where id_encuesta = $id_encuesta");


if($registro_encuestas = mysqli_fetch_array($tabla_encuestas)){
    $id_encuesta 	= $_SESSION['id_encuesta'] = $registro_encuestas[0];
    $nombre_encuesta= $registro_encuestas[1];

    $tabla_casos = mysqli_query($con, "select count(id_encuesta) from maestro_encuestas where id_encuesta = $id_encuesta");
    $registro_casos = mysqli_fetch_array($tabla_casos);
    $nro_casos = $_SESSION['nro_casos'] = $registro_casos[0];	
}

require_once '../accesorios/encabezado.php';

?>    
    
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sx-12 col-md-12">
                <h5>
                    <label> <?php echo $nombre_encuesta ?> :: Nro casos <?php echo $nro_casos ?></label>
                </h5>
            </div>            
        </div>
    </div>
    <div class="panel-body"> 
        
        <div id="detalle_grafico">            </div>

    </div>
 </div>

        
<script type="text/javascript">
    $(document).ready(function(){
        $("#detalle_grafico").load('detalle_grafico.php');
    });
</script>

<?php

mysqli_close($con);
require_once '../accesorios/footer.php';