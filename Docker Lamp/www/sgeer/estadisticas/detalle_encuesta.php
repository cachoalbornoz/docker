<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('location:../accesorios/salir.php');
    exit;
}

include '../accesorios/accesos_bd.php';
$con = conectar();

if (isset($_POST['operacion'])) {
    if ($_POST['operacion'] == 1) {
        $nombre_encuesta = strtoupper($_POST['nombre_encuesta']);

        $inserta_encuesta = mysqli_query($con, "insert into encuestas (nombre,estado) values ('$nombre_encuesta',1)");

        $id_encuesta = mysqli_insert_id($con);

        $inserta_pregunta = mysqli_query($con, "insert into preguntas (id_encuesta, enunciado, enunciado_mostrar, id_tipo_respuesta, nro)
        values ($id_encuesta, 'ZONA', 'ZONA', 9, 1)") or die('Revisar ingreso de preguntas');
        $inserta_pregunta = mysqli_query($con, "insert into preguntas (id_encuesta, enunciado, enunciado_mostrar, id_tipo_respuesta, nro)
        values ($id_encuesta, 'SEXO', 'SEXO', 6, 2)") or die('Revisar ingreso de preguntas');
        $inserta_pregunta = mysqli_query($con, "insert into preguntas (id_encuesta, enunciado, enunciado_mostrar, id_tipo_respuesta, nro)
        values ($id_encuesta, 'EDAD', 'EDAD', 5, 3)") or die('Revisar ingreso de preguntas');
        $inserta_pregunta = mysqli_query($con, "insert into preguntas (id_encuesta, enunciado, enunciado_mostrar, id_tipo_respuesta, nro)
        values ($id_encuesta, 'NIVEL INSTRUCCION', 'NIVEL INSTRUCCION', 7, 4)") or die('Revisar ingreso de preguntas');
        $inserta_pregunta = mysqli_query($con, "insert into preguntas (id_encuesta, enunciado, enunciado_mostrar, id_tipo_respuesta, nro)
        values ($id_encuesta, 'OCUPACION PRINCIPAL', 'OCUPACION PRINCIPAL', 8, 5)") or die('Revisar ingreso de preguntas');
    } else {
        if ($_POST['operacion'] == 2) {
            $id_encuesta = $_POST['id_encuesta'];

            //BORRAR LAS RESPUESTAS DE LOS MAESTROS (SI LA TABLA NO ES IMPORTADA)
            $seleccion_maestro = mysqli_query($con, "select id_maestro from maestro_encuestas where id_encuesta = $id_encuesta");
            while ($registro_maestro = mysqli_fetch_array($seleccion_maestro)) {
                $id_maestro = $registro_maestro[0];

                mysqli_query($con, "delete from maestro_respuestas_abiertas where id_maestro = $id_maestro");
                mysqli_query($con, "delete from maestro_respuestas_cerradas where id_maestro = $id_maestro");
                mysqli_query($con, "delete from maestro_respuestas_cruzadas where id_maestro = $id_maestro");
                mysqli_query($con, "delete from maestro_respuestas_edad where id_maestro = $id_maestro");

                mysqli_query($con, "delete from maestro_encuestas where id_encuesta = $id_encuesta");
                mysqli_query($con, "delete from encuestas where id_encuesta = $id_encuesta");
                mysqli_query($con, "delete from archivos_importados where id_encuesta = $id_encuesta");
            }
            //REVISAR SI LA ENCUESTA ES IMPORTADA - LUEGO ELIMINARLA DE LA TABLA ENCUESTAS
            $seleccion_encuestas = mysqli_query($con, "select importada from encuestas where id_encuesta = $id_encuesta");
            $registro_encuestas  = mysqli_fetch_array($seleccion_encuestas);

            if ($registro_encuestas[0] == 1) {
                mysqli_query($con, "delete from maestro_etiquetas where id_encuesta = $id_encuesta");
            } else {
                $seleccion_preguntas = mysqli_query($con, "select id_pregunta from preguntas where id_encuesta = $id_encuesta");

                while ($registro_preguntas = mysqli_fetch_array($seleccion_preguntas)) {
                    $id_pregunta = $registro_preguntas[0];

                    mysqli_query($con, "delete from respuestas where id_pregunta = $id_pregunta");

                    mysqli_query($con, "delete from preguntas where id_pregunta = $id_pregunta");
                }
            }

            mysqli_query($con, "DELETE FROM encuestas where id_encuesta = $id_encuesta");
            mysqli_query($con, "DELETE FROM archivos_importados where id_encuesta = $id_encuesta");
        }
    }
}

?>

<table class="table table-hover">
    <?php
    $tabla_encuestas = mysqli_query($con, 'select id_encuesta, nombre, estado_encuesta.estado, fecha from encuestas, estado_encuesta
    where encuestas.estado = estado_encuesta.id_estado order by encuestas.estado, nombre asc');
    while ($registro_encuestas = mysqli_fetch_array($tabla_encuestas)) {?>
    <tr>
        <td><a href="javascript:void(0)" onclick="encuesta('<?php print $registro_encuestas[0]; ?>','<?php print $registro_encuestas[1]; ?>')"><?php print $registro_encuestas[1]; ?></a></td>
        <td><?php print $registro_encuestas[2]; ?>
        </td>
        <td><a href='javascript:void(0)' title='Modifica encuesta' onClick="modifica_encuesta('<?php print $registro_encuestas[0]; ?>')"><span class="glyphicon glyphicon-pencil"></span></a></td>
        <td><a href='javascript:void(0)' title='Elimina encuesta actual' onClick="elimina_encuesta('<?php print $registro_encuestas[0]; ?>', '<?php print $registro_encuestas[1]; ?>')"><span class="glyphicon glyphicon-trash"></span></a></td>
    </tr>
    <?php
}
?>
</table>
<?php mysqli_close($con);
