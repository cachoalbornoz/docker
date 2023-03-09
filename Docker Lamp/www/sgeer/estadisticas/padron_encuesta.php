<?php 
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}

require_once '../accesorios/encabezado.php'; 

if(isset($_SESSION['id_encuesta'])){
    unset($_SESSION['id_encuesta']);
}

?>

        <script type="text/javascript">

            $(document).ready(function(){
                $("#detalle_encuesta").load('detalle_encuesta.php');
                $("#nombre_encuesta").select();

            });


            function cargar(){
                var nombre = document.getElementById('nombre_encuesta').value;

                if(nombre != 0){
                    $("#detalle_encuesta").load('detalle_encuesta.php',{operacion:1,nombre_encuesta:nombre});

                    document.getElementById('nombre_encuesta').value = '';
                }
            }

            function elimina_encuesta(id, nombre){
                if(confirm("Esta seguro que desea eliminar \n"+ nombre +" ?")){
                    $("#detalle_encuesta").load('detalle_encuesta.php',{operacion:2,id_encuesta:id});
                }	
            }

            function modifica_encuesta(id){
                window.location = ('padron_encuesta_edita.php?id_encuesta=' + id);
            }

            function encuesta(id, estado){

                if(estado != 9){
                    window.location = ('padron_preguntas.php?id_encuesta=' + id);
                }

            }

        </script>


        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sx-12 col-md-12">
                        ADMINISTRAR ENCUESTAS
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sx-12 col-md-2">
                        <b>ENCUESTA NUEVA</b>
                    </div>
                    <div class="col-sx-12 col-md-6">
                        <input type="text" id="nombre_encuesta" name="nombre_encuesta" class="form-control">  
                    </div>
                    <div class="col-sx-12 col-md-4" style="text-align: right">
                        <input type="button" value="Crear" onClick="cargar()" class="btn btn-info">
                    </div>
                </div>  
                <div class="row">
                    <div class="col-sx-12 col-md-12">
                        <div id="estado">&nbsp; </div>
                    </div>
                </div>  

                <div id="detalle_encuesta"> </div>

            </div>
        </div>
      
<?php

require_once '../accesorios/footer.php';

