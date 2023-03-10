<?php 
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}

include('../accesorios/encabezado.php');
require('../accesorios/accesos_bd.php');

$con=conectar();

$tabla_encuesta = mysqli_query($con,"select * from encuestas where estado = 1");
$registro_encuesta = mysqli_fetch_array($tabla_encuesta);

$id_encuesta = $registro_encuesta[0];

$_SESSION['id_encuesta'] = $registro_encuesta[0];

?>

<script type="text/javascript">

    $(document).ready(function(){
        $("#detalle_marcadores").load('detalle_marcadores.php');

    });


    function agregar(){
        var nombre = document.getElementById('nombre').value;
        var nro_pregunta = document.getElementById('nro_pregunta').value;
        $("#detalle_marcadores").load('detalle_marcadores.php',{operacion:1,nombre:nombre, nro_pregunta:nro_pregunta});  
    }

    function eliminar_marcador(id, nombre){
        if(confirm("Esta seguro que desea eliminar \n"+ nombre +" ?")){
            $("#detalle_marcadores").load('detalle_marcadores.php',{operacion:2,id_marcador:id});
        }	
    }

</script>


<div class="panel panel-primary"> 
    <div class="panel-heading">
        <div class="row">
            <div class="col-sx-12 col-md-12">
                LISTADO DE PAGINADORES
            </div>            
        </div>
    </div>
    <div class="panel-body">
        <form class="form-inline">
            <div class="col-sx-12 col-md-5">
                <label for="nombre">Nombre </label>
                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ingresar textos y/o guiones">
            </div>            
        
            <div class="col-sx-12 col-md-5">
                <label for="nro_pregunta">Nro Pregunta</label>
                <input type="number" name="nro_pregunta" id="nro_pregunta" class="form-control">
            </div> 
            <div class="col-sx-12 col-md-2" style="text-align: right">                
                <input type="button" name="enviar" id="enviar" value="Agregar" class="btn btn-info" onclick="agregar()">                
            </div> 
        </form>
        <br>
        <br>
        <div id="estado">  </div>

        <div id="detalle_marcadores"> </div>
    </div>

</div>

<?php 

require_once '../accesorios/footer.php';
