<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('location:../accesorios/salir.php');
    exit;
}

require '../accesorios/accesos_bd.php';

$con = conectar();

$id_encuesta = $_SESSION['id_encuesta'];

require_once '../accesorios/encabezado.php';

?>


<script type="text/javascript">
    function leer_id_encuesta() {

        var id = document.getElementById('encuesta_imp').value;

        if (id > 0) {

            var url = 'verifica_encuesta.php';
            $.ajax({
                type: 'GET',
                cache: false,
                url: url,
                data: {
                    id: id
                },
                success: function(resp) {
                    $("#estado").html("<b><font color='#FF0000'>Id de Encuesta Leido : </font></b>" + resp);
                }
            });
        }
    }

    function importar() {

        var form_importar = $("#importacion");
        form_importar.submit();
    }

    function importar_etiquetas() {

        var form_importar = $("#importacion_etiquetas");
        form_importar.submit();
    }

    function asociar() {

        var id_no_importada = document.getElementById('no_importada').value;
        var id_importada = document.getElementById('encuesta_imp2').value;

        if (id_no_importada > 0 && id_importada > 0) {
            if (confirm("Está seguro de asociar marcadores ?")) {
                $("#estado").load('asociar.php', {
                    id_no_importada: id_no_importada,
                    id_importada: id_importada
                }, function() {
                    $("#estado").html('&nbsp;Marcadores asociados !');
                });
            }
        } else {
            $("#estado").html('&nbsp;Seleccione ENCUESTA EN CURSO - y su correlativa IMPORTADA. ');
        }

    }
</script>


<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sx-12 col-md-12">
                PROCESO IMPORTACION
            </div>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <tr>
                <td>
                    Si la importación se realiza en varias etapas, seleccione la encuesta con la que trabajará.
                    <?php
                        if (isset($id_encuesta) and $id_encuesta > 0) {
                            print 'Encuesta Activa Nro: <span style="color:green">' . $id_encuesta . '</span>';
                        } else {
                            print '<span style="color:red">Seleccionar una ENCUESTA !!!</span>';
                        }
                        ?>
                    <select name="encuesta_imp" id="encuesta_imp" size="1" tabindex="1" onchange="leer_id_encuesta()" class="form-control">
                        <option value="0">Nueva importación de encuesta</option>
                        <?php
                            $tabla_encuestas = mysqli_query($con, 'select id_encuesta, nombre from encuestas where estado in (0,2,9) order by id_encuesta desc');
                            while ($fila = mysqli_fetch_array($tabla_encuestas)) {
                                ?>
                        <option value='<?php print $fila[0]; ?>'>(<?php print $fila[0]; ?>) <?php print $fila[1]; ?>
                        </option>
                        <?php
                            }
                            ?>
                    </select>
                </td>
            </tr>
        </table>

        <form action="detalle_importacion_etiquetas.php" name="importacion_etiquetas" id="importacion_etiquetas" enctype="multipart/form-data" method="post">
            <table class="table">
                <tr class="bg-danger">
                    <td><span class="glyphicon glyphicon-text-background"></span> DATOS / ETIQUETAS</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:50%"><input type="file" name="etiquetas" id="etiquetas" required class="form-control" /></td>
                    <td style="width:50%"><input type="submit" value="Importar Datos / Etiquetas" class="btn btn-info"></td>
                </tr>
                <?php
                $contador = 1;
                $tabla    = mysqli_query($con, 'select * from encuestas where estado = 9 order by fecha desc limit 1');
                while ($registro = mysqli_fetch_array($tabla)) {
                    ?>
                <tr>
                    <td><?php print $registro[0]; ?> - <?php print $registro[1]; ?>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <?php
                $contador++;
                }
                ?>
            </table>
        </form>

        <form action="detalle_importacion_informe.php" name="importacion_informe" id="importacion_informe" enctype="multipart/form-data" method="post">
            <table class="table">
                <tr class="bg-info">
                    <td><span class="glyphicon glyphicon-print"></span> INFORMES</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:50%"><input type="file" name="informe" id="informe" required class="form-control" /></td>
                    <td style="width:50%"><input type="submit" value="Importar Informe" class="btn btn-info"></td>
                </tr>
                <?php
                $contador = 1;
                $tabla    = mysqli_query($con, 'select * from archivos_importados order by fecha desc limit 1');
                while ($registro = mysqli_fetch_array($tabla)) {
                    ?>
                <tr>
                    <td><?php print $registro[2]; ?>
                    </td>
                    <td><?php print $registro[4]; ?>
                    </td>
                </tr>
                <?php
                $contador++;
                }
                ?>
            </table>
        </form>

        <table class="table">
            <tr class="bg-primary">
                <td><span class="glyphicon glyphicon-globe"></span> MAPAS</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="width:50%">
                    <select name="no_importada" id="no_importada">
                        <option value="0">Encuesta en curso / desactivada </option>
                        <?php
                    $tabla_encuestas = mysqli_query($con, 'select id_encuesta, nombre from encuestas where estado in (0,1,2) order by nombre');
                    while ($fila = mysqli_fetch_array($tabla_encuestas)) {
                        print '<option value="' . $fila[0] . '">' . $fila[1] . "</option>\n";
                    }

                    ?>
                    </select>

                    <select name="encuesta_imp2" id="encuesta_imp2">
                        <option value="0">Encuestas Importadas</option>
                        <?php
                        $tabla_encuestas = mysqli_query($con, 'select id_encuesta, nombre from encuestas where estado = 9 order by id_encuesta desc');
                        while ($fila = mysqli_fetch_array($tabla_encuestas)) {
                            print '<option value="' . $fila[0] . '">' . $fila[1] . "</option>\n";
                        }

                        ?>
                    </select>
                </td>
                <td style="width:50%">
                    <input type="button" value="Asociar Mapa" onClick="asociar()" class="btn btn-info">
                </td>
            </tr>
            <tr>
                <td>
                    <div id="estado" style="color:#063"></div>
                </td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
</div>


<?php
mysqli_close($con);
require_once '../accesorios/footer.php';
