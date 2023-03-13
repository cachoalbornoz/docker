<?php
session_start();

if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}

require_once '../accesorios/encabezado.php';

$id_usuario = $_SESSION['id_usuario'];

?>


<script type="text/javascript">

    function descarga_archivo(){
        var id = document.getElementById('encuesta_imp').value;
        if(id > 0){
            window.location="descarga.php?id=" + id;
        }
    }

    function refrescar(){
        window.location.reload();
    }

    function ver_mapa(id){
        if(id > 0){
            window.location = "../mapas/crear_marcadores.php?id_encuesta=" + id ;
        }
    }

    function ver_resultados(id){
        if(id > 0){
            window.location = "../graficos/inicio_grafico.php?id_encuesta=" + id	;	
        }
    }
</script>
    
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-12 col-md-12">                
                <span class="glyphicon glyphicon-th"></span>
                ENCUESTA EN CURSO :   
                <strong>
                <?php
                require_once '../accesorios/accesos_bd.php';
                $con=conectar();
                $tabla_encuestas = mysqli_query($con, "select id_encuesta, nombre from encuestas where estado = 1"); 
                if($fila = mysqli_fetch_array($tabla_encuestas)){
                    $id_encuesta = $fila[0];
                    $_SESSION['id_encuesta'] = $id_encuesta;

                    $nombre_encuesta= $fila[1];

                    $tabla_casos = mysqli_query($con, "select count(id_encuesta) from maestro_encuestas where id_encuesta = $id_encuesta");
                    $registro_casos = mysqli_fetch_array($tabla_casos);
                    $nro_casos = $_SESSION['nro_casos'] = $registro_casos[0];

                    $seleccion  = mysqli_query($con,"select avg(latitud), avg(longitud) from maestro_posiciones 
                    where latitud < 0 and longitud < 0 and id_encuesta =  $id_encuesta");
                    $registro   = mysqli_fetch_array($seleccion);

                    $latitud   = $registro[0];
                    $longitud  = $registro[1];

                    $_SESSION['latitud']    = $registro[0];
                    $_SESSION['longitud']   = $registro[1];

                }else{
                    $id_encuesta 	= 0;
                    $nombre_encuesta= '<span style="color: white" class="glyphicon glyphicon-info-sign"></span> NINGUNA ';
                }
                echo $nombre_encuesta;
                ?>
                </strong>    
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div class="container"> 
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <span class="glyphicon glyphicon-list"></span> 
                    <a href="javascript:void(0)" onClick="ver_resultados(<?php echo $id_encuesta?>)"> 
                        Resultados Parciales (<b><span style="color:#FF0000"><?php if(isset($nro_casos)){echo $nro_casos;}else{ echo 0;} ?></span></b> casos)
                    </a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <span class="glyphicon glyphicon-globe"></span> 
                    <a href="javascript:void(0)" onClick="ver_mapa(<?php echo $id_encuesta?>)">
                        Mapa GeoReferenciado de encuestas 
                    </a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12">

                </div>
            </div>
        </div>    
    </div>        
        
    <div class="panel-footer">
        <div class="row">
            <div class="col-xs-12">
                <div id="estado" style="color:#900">  
                    
                </div>
            </div>
        </div>
    </div>
</div>    
    

<?php 
mysqli_close($con);
require_once '../accesorios/footer.php';

