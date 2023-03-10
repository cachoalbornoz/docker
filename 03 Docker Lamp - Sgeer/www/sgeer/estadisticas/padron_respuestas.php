<?php 
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}

require('../accesorios/accesos_bd.php');

$con=conectar();


$id_pregunta = $_SESSION['id_pregunta'] = $_GET['id_pregunta'];

$tabla_pregunta = mysqli_query($con,"select preg.*, tipo from preguntas as preg, tipo_respuestas as tr where preg.id_tipo_respuesta = tr.id_tipo_respuesta and id_pregunta = $id_pregunta");
$registro_pregunta = mysqli_fetch_array($tabla_pregunta);

$nro_pregunta 		= $registro_pregunta[2];
$enunciado              = $registro_pregunta[3];
$id_tipo_respuesta 	= $registro_pregunta['id_tipo_respuesta']; 
$tipo_respuesta		= $registro_pregunta[6];

require_once '../accesorios/encabezado.php';

?>

<script type="text/javascript">

    $(document).ready(function(){
        $("#detalle_respuesta").load('detalle_respuesta.php');
        $("#enunciado").select();
    });

    function agregar_respuesta(id){
        var enunciado = document.getElementById('enunciado').value;
        var valor_spss= document.getElementById('valor_spss').value;
        var fila = document.getElementById('fila').value;

        if(enunciado != 0){
            $("#detalle_respuesta").load('detalle_respuesta.php',{operacion:1, enunciado: enunciado, fila:fila, valor_spss:valor_spss, id_tipo:id});
            $("#enunciado").val('');
            $("#valor_spss").val('');
            $("#enunciado").focus();
        }
    }

    function modifica_respuesta(id){
        window.location = ('padron_respuestas_edita.php?id_respuesta=' + id);
    }

    function elimina_respuesta(){      
           
        if (($("input[name*='respuesta']:checked").length)<=0) {
             alert("Seleccione un elemento por favor ");
             return false;
         }else{
             if(confirm("Elimina los seleccionados ? ")){
             var cadena = '';
                 $("input[name*='respuesta']:checked").each(function() {
                     cadena += $(this).val() + ', ';
                 });
                 $("#detalle_respuesta").load('detalle_respuesta.php',{operacion:2,cadena: cadena});
             }   
         }       
    }
</script>


        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sx-12 col-md-12">
                        <?php echo $enunciado ?> - <strong><?php echo $tipo_respuesta ?></strong>
                    </div>            
                </div>
            </div>
            <div class="panel-body">  		
                <div id="estado">  </div>

                <table class="table table-hover">
                    <tr align="left">
                        <td align="left">RESPUESTA POSIBLES A MOSTRAR</td>
                        <td align="center">VALOR VARIABLE <b>SPSS</b></td>
                        <td align="center">TIPO ENCABEZADO</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left"><input type="text" id="enunciado" name="enunciado" tabindex="1" class="form-control"></td>
                        <td align="center"><input type="text" id="valor_spss" name="valor_spss" tabindex="2" class="form-control"></td>
                        <td align="center">
                                
                            <select id="fila" name="fila" tabindex="3" size="1" class="form-control">
                                <option value="0" selected ></option>
                                <option value="1">Valor Fila </option>
                                <option value="2">Valor Columna </option>
                                <option value="3">Encabezado Fila </option>
                                <option value="4">Encabezado Columna </option>
                            </select>
                                    
                        </td>
                        <td class="text-right">
                            <input type="button" tabindex="5" value="Agregar respuesta" class="btn btn-info" onClick="agregar_respuesta(<?php echo $id_tipo_respuesta ?>)">
                        </td>
                    </tr> 
                </table>                 
                <div id="detalle_respuesta"> </div>
            </div>
        </div>

<?php 
mysqli_close($con);
require_once '../accesorios/footer.php';


