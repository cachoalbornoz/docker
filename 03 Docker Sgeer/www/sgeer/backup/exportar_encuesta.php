<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}

require('../accesorios/accesos_bd.php');


$con = conectar();
$id_usuario = $_SESSION['id_usuario'];

require_once '../accesorios/encabezado.php';

?>


    <script type="text/javascript">

    $(document).ready(function(){
        $("#detalle_exportacion").load("detalle_exportar_encuesta.php",{});
    });

    function generar_back_up(){

        var id_encuesta = document.getElementById('encuesta').value;
        $("#detalle_exportacion").html('Procesando datos, aguarde ...  <img class=" img-rounded" src="cargando.gif" alt="cargando" width="16" height="16" > ');
        
        if(id_encuesta > 0){
            $("#detalle_exportacion").load("detalle_exportar_encuesta.php",{marca:1, id_encuesta:id_encuesta});
        }
    }


    function descarga_archivo(archivo){
            window.location="descarga.php?archivo=" + archivo;
    }

    function borrar_archivo(id, nombre){
        var id_archivo = id;
        var nom = nombre;
        if (confirm("Esta seguro que desea borrar el archivo " + nom + " ?")){
            $("#detalle_exportacion").load("detalle_exportar_encuesta.php",{marca:2, id:id_archivo, nombre:nom});
        }
    }

    </script>


        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sx-12 col-md-12">
                        EXPORTAR ENCUESTAS
                    </div>
                </div>
            </div>
            <div class="panel-body">   
                <table class="table table-hover">
                <tr>
                    <td style="width: 50%">
                        <select name="encuesta" id="encuesta" size="1" tabindex="1" class="form-control">
                        <option value="0" selected>Seleccione la encuesta</option>
                        <?php

                        $tabla_encuestas = mysqli_query($con, "select id_encuesta, nombre from encuestas where estado <> 9"); 

                        while($fila = mysqli_fetch_array($tabla_encuestas)){
                            echo "<option value=\"".$fila[0]."\">".$fila[1]."</option>\n";
                        }	
                        ?>
                        </select>

                    </td>

                    <td style="width: 50%">
                        <input type="button" onClick="generar_back_up()" value="Exportar Datos" class="btn btn-info">
                    </td>
                </tr>
                </table>

                <div id="detalle_exportacion"></div>    
            </div>
        </div>    
    
<?php 
mysqli_close($con);
require_once '../accesorios/footer.php';

