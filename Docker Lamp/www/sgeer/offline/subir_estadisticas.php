<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('location:../accesorios/salir.php');
    exit;
}

require '../accesorios/accesos_bd.php';

$con        = conectar();
$id_usuario = $_SESSION['id_usuario'];

require_once '../accesorios/encabezado.php';

?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sx-12 col-md-12">
                SUBIR ARCHIVOS DE RESULTADOS A LA ENCUESTA
            </div>
        </div>
    </div>
    <div class="panel-body">

        <form action="upload.php" method="post" enctype="multipart/form-data" name="form_encuestas" class="form-inline">

            <?php
            $seleccion = mysqli_query($con, 'select id_encuesta from encuestas where estado=1');
            $num_row = mysqli_num_rows(($seleccion));
            
            if ($num_row > 0) { // VERIFICA SI HAY ENCUESTA EN CURSO O ACTIVA
                
                $registro  = mysqli_fetch_array($seleccion);
                $id_encuesta = $registro[0]; ?>

            <table class="table table-hover">
                <tr class="bg-info">
                    <td>
                        <label class="btn btn-default btn-file">
                            <input type="file" name="uploads[]" multiple="multiple" required>
                        </label>
                    </td>
                    <td class="text-right">
                        <input type="submit" value="Subir encuesta" class="btn btn-info">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td>Nro Usuario</td>
                    <td>Total</td>
                </tr>

                <?php

            $seleccion = mysqli_query($con, "select id_usuario, count(id_usuario) from maestro_encuestas where id_encuesta = $id_encuesta group by id_usuario");
                while ($registro = mysqli_fetch_array($seleccion)) {
                    ?>
                <tr>
                    <td><?php print $registro[0]; ?>
                    </td>
                    <td><?php print $registro[1]; ?>
                    </td>
                </tr>
                <?php
                }
                $seleccion = mysqli_query($con, "select count(id_encuesta) from maestro_encuestas where id_encuesta = $id_encuesta");
                $registro  = mysqli_fetch_array($seleccion);
            ?>
                <tr>
                    <td>&nbsp;</td>
                    <td><?php print $registro[0]; ?>
                    </td>
                </tr>
            </table>

            <?php
            } else {?>

            <table class="table table-hover">
                <tr>
                    <td>
                        <span style="color: red" class="glyphicon glyphicon-info-sign"></span> No existe una encuesta ACTIVO / CURSO
                    </td>
                </tr>
            </table>

            <?php
            }
            ?>



        </form>
    </div>
</div>


<?php
mysqli_close($con);
require_once '../accesorios/footer.php';
