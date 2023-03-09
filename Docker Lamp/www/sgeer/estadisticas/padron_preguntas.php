<?php 
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/encabezado.php');

require('../accesorios/accesos_bd.php');

$con=conectar();

$id_encuesta = $_SESSION['id_encuesta'] = $_GET['id_encuesta'];

$tabla_encuesta = mysqli_query($con,"select * from encuestas where id_encuesta = $id_encuesta");
$registro_encuesta = mysqli_fetch_array($tabla_encuesta);

$nombre_encuesta = $registro_encuesta[1];



?>


<script type="text/javascript">

$(document).ready(function(){
    $("#detalle_pregunta").load('detalle_pregunta.php');
    $("#enunciado").select();
});


function cargar_pregunta(){
    var enunciado 	= document.getElementById('enunciado').value;
    var enunciado_m	= document.getElementById('enunciado_mostrar').value;
    var tipo_resp   = document.getElementById('id_tipo_respuesta').value;

    if((enunciado != 0)){
        $("#detalle_pregunta").load('detalle_pregunta.php',{operacion:1,enunciado:enunciado, enunciado_mostrar:enunciado_m, tipo_resp: tipo_resp});
        document.getElementById('enunciado').focus();
    }
}

function mostrar_opciones(){
    var tipo_respuesta = document.getElementById('id_tipo_respuesta').value;

    if(tipo_respuesta == 4){
        $("#rotulo_respuestas").hide();
        $("#opciones_respuestas").hide();
        $("#rotulo_cruzadas").show();
        $("#opciones_cruzadas").show();
    }else{
        $("#rotulo_respuestas").show();
        $("#opciones_respuestas").show();
        $("#rotulo_cruzadas").hide();
        $("#opciones_cruzadas").hide();
    }	
}

function modifica_pregunta(id){
    window.location = ('padron_preguntas_edita.php?id_pregunta=' + id);
}

function modifica_pregunta_imp(id){
    window.location = ('padron_preguntas_edita_imp.php?id_pregunta=' + id);
}

function elimina_pregunta(){      
           
    if (($("input[name*='pregunta']:checked").length)<=0) {
         alert("Seleccione un elemento por favor");
         return false;
     }else{
         if(confirm("Elimina los seleccionados ? ")){
            var cadena = '';
            $("input[name*='pregunta']:checked").each(function() {
                cadena += $(this).val() + ', ';
            });
            $("#detalle_pregunta").load('detalle_pregunta.php',{operacion:2,cadena: cadena});
         }   
     }       
}

</script>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sx-12 col-md-12">
                        ADMINISTRAR PREGUNTAS - ENCUESTA <?php echo strtoupper($nombre_encuesta) ?>
                    </div>

                </div>
            </div>
            <div class="panel-body">     

                <table class="table">
                <tr align="center">
                    <td align="left">ENUNCIADO (Encuesta)</td>
                    <td align="left">ENUNCIADO (A Mostrar en los Resultados)</td>
                    <td>TIPO RESPUESTA</td>
                    <td>&nbsp;</td>
                </tr>
                <tr align="center">
                    <td align="left">
                        <input type="text" id="enunciado" name="enunciado" tabindex="1" class="form-control" ></td>
                    <td align="left">
                        <input type="text" id="enunciado_mostrar" name="enunciado_mostrar" tabindex="2" class="form-control" ></td>
                    <td>
                        <select name="id_tipo_respuesta" id="id_tipo_respuesta" tabindex="3" class="form-control" onChange="mostrar_opciones()">
                            <option value="0"></option>
                            <?php
                            $tipo_respuestas = "select id_tipo_respuesta, tipo from tipo_respuestas where id_tipo_respuesta not in (5,6,7,8,9) order by orden asc";
                            $registro = mysqli_query($con, $tipo_respuestas); 
                            while($fila = mysqli_fetch_array($registro))        {
                                echo "<option value=\"".$fila[0]."\">".$fila[1]."</option>\n";
                            }	
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="button" tabindex="5" value="Agregar" onClick="cargar_pregunta()" class="btn btn-info">
                    </td>
                </tr>
                </table>  
                <div id="estado">  </div>
                <div id="detalle_pregunta"> </div>
            </div>    
        </div>


<?php 

mysqli_close($con);

require_once '../accesorios/footer.php';