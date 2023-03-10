<?php 
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}

require('../accesorios/accesos_bd.php');

$con=conectar();

$id_respuesta = $_GET['id_respuesta'];

$tabla_respuesta = mysqli_query($con,"select * from respuestas where id_respuesta = $id_respuesta");
$registro_respuesta = mysqli_fetch_array($tabla_respuesta);

$enunciado = $registro_respuesta[2];
$valor_spss= $registro_respuesta[5];
$encabezado= $registro_respuesta[4];

include('../accesorios/encabezado.php');

?>

        <script type="text/javascript">

            $(document).ready(function(){
                $("#enunciado").select();	
            });

            function modificar_respuesta(id){
                var enunciado 	= document.getElementById('enunciado').value;
                var valor_spss  = document.getElementById('valor_spss').value; 
                var fila	= document.getElementById('fila').value;
                window.location = ('edita_respuesta.php?id_respuesta=' + id + '&enunciado=' + enunciado + '&valor_spss=' + valor_spss + '&fila=' + fila);	
            }

        </script>

         <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sx-12 col-md-12">
                        ADMINISTRAR PREGUNTAS - ENUNCIADO <strong><?php echo strtoupper($enunciado) ?></strong>
                    </div>

                </div>
            </div>
             <div class="panel-body"> 

                <div id="estado">  </div>

                <table class="table table-hover">
                    <tr align="center">
                        <td align="left">ENUNCIADO RESPUESTA</td>
                        <td>VALOR VARIABLE <b>SPSS</b></td>
                        <td>TIPO ENCABEZADO</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr align="center">
                        <td align="left"><input type="text" id="enunciado" name="enunciado" class="form-control" value="<?php echo $enunciado ?>"></td>
                        <td><input type="text" id="valor_spss" name="valor_spss" class="form-control" size="8" style="text-align:center" value="<?php echo $valor_spss ?>"></td>
                        <td>
                            <?php
                            if($encabezado == 0){
                                $enc_cero="selected";
                                $enc_uno="";
                                $enc_dos="";
                                $enc_tres="";
                                $enc_cuatro="";
                            }else{
                                if($encabezado == 1){
                                    $enc_cero="";
                                    $enc_uno="selected";
                                    $enc_dos="";
                                    $enc_tres="";
                                    $enc_cuatro="";
                                }else{
                                    if($encabezado == 2){
                                        $enc_cero="";
                                        $enc_uno="";
                                        $enc_dos="selected";
                                        $enc_tres="";
                                        $enc_cuatro="";
                                    }else{
                                        if($encabezado == 3){
                                            $enc_cero="";
                                            $enc_uno="";
                                            $enc_dos="";
                                            $enc_tres="selected";
                                            $enc_cuatro="";
                                        }else{
                                            if($encabezado == 4){
                                                $enc_cero="";
                                                $enc_uno="";
                                                $enc_dos="";
                                                $enc_tres="";
                                                $enc_cuatro="selected";
                                            }
                                        }                   
                                    }                
                                }
                            }
                            ?>
                            <select id="fila" name="fila" size="1" class="form-control">
                                <option value="0" <?php echo $enc_cero ?>></option>
                                <option value="1" <?php echo $enc_uno ?>>Valor Fila</option>
                                <option value="2" <?php echo $enc_dos ?>>Valor Columna</option>
                                <option value="3" <?php echo $enc_tres ?>>Encabezado Fila</option>
                                <option value="4" <?php echo $enc_cuatro ?>>Encabezado Columna</option>
                            </select>
                        </td>
                        <td class="text-right">
                            <input type="button" value="Modificar" class="btn btn-info" onClick="modificar_respuesta('<?php echo $id_respuesta ?>')">
                        </td>
                    </tr>
                </table>  
            </div>                
        </div>
  

<?php 

mysqli_close($con); 
require_once '../accesorios/footer.php';