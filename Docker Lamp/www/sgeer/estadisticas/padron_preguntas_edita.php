<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/accesos_bd.php');

$con=conectar();


$id_pregunta = $_GET['id_pregunta'];
$tabla_pregunta = mysqli_query($con,"select preg.*, tipo from preguntas as preg, tipo_respuestas as tr where preg.id_tipo_respuesta = tr.id_tipo_respuesta and id_pregunta = $id_pregunta");
$registro_pregunta = mysqli_fetch_array($tabla_pregunta);

$enunciado 		= $registro_pregunta[3];
$id_tipo_respuesta 	= $registro_pregunta[2];
$enunciado_mostrar	= $registro_pregunta[5]; 
$tipo_respuesta		= $registro_pregunta[6];

include('../accesorios/encabezado.php');

?>


    <script type="text/javascript">

    $(document).ready(function(){
        $("#enunciado").select()

    });

    function modificar_pregunta(id){
        var enunciado 	= document.getElementById('enunciado').value;
        var enunciado_m	= document.getElementById('enunciado_mostrar').value;
        var id_resp   	= document.getElementById('id_tipo_respuesta').value;

        window.location = ('edita_pregunta.php?id_pregunta=' + id + '&enunciado=' + enunciado + '&id_resp=' + id_resp + '&enunciado_mostrar=' + enunciado_m);	
    }

    </script>

<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sx-12 col-md-12">
                <?php echo $enunciado ?></b> - <?php echo $tipo_respuesta ?>
            </div>            
        </div>
    </div>
    <div class="panel-body">
        <div id="estado">  </div>

        <table class="table">
            <tr align="center">
                <td align="left">ENUNCIADO</td>
                <td align="left">ENUNCIADO</td>
                <td>TIPO RESPUESTA</td>
                <td>&nbsp;</td>
            </tr>
            <tr align="center">
                <td align="left">
                    <input type="text" id="enunciado" name="enunciado" tabindex="1" size="40" class="form-control" value="<?php echo $enunciado ?>">
                </td>
                <td align="left">
                    <input type="text" id="enunciado_mostrar" name="enunciado_mostrar" tabindex="2" size="40" class="form-control" value="<?php echo $enunciado_mostrar ?>">
                </td>
                <td>
                    <select name="id_tipo_respuesta" id="id_tipo_respuesta" tabindex="3" class="form-control">
                    <option value="<?php echo $id_tipo_respuesta?>"><?php echo $tipo_respuesta?></option>
                    <?php
                    $tipo_respuestas = "select id_tipo_respuesta, tipo from tipo_respuestas where id_tipo_respuesta not in (5,6,7,8,9) order by orden asc";
                    $registro = mysqli_query($con, $tipo_respuestas); 
                    while($fila = mysqli_fetch_array($registro)){
                        echo "<option value=\"".$fila[0]."\">".$fila[1]."</option>\n";
                    }	
                    ?>
                    </select>
                </td>
                <td>
                    <input type="button" tabindex="5" value="Modificar" class="btn btn-info" onClick="modificar_pregunta('<?php echo $id_pregunta ?>')">
                </td>
            </tr>
        </table>
    </div>    
</div>
  

<?php

mysqli_close($con);
require_once '../accesorios/footer.php';
