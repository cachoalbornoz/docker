<?php

require_once '../accesorios/accesos_bd.php';
$con = conectar();

if (isset($_POST['marca'])) {
    if ($_POST['marca'] == 1) {
        $id_encuesta = $_POST['id_encuesta'];

        $tabla_encuestas    = mysqli_query($con, "select id_encuesta, nombre from encuestas where id_encuesta = $id_encuesta");
        $registro_encuestas = mysqli_fetch_array($tabla_encuestas);

        $id_encuesta = $registro_encuestas[0];

        $nombre_encuesta = $registro_encuestas[1];

        $nombre_archivo = $nombre_encuesta . '.txt';

        $tabla_archivo = mysqli_query($con, "select id_archivo from archivos_exportados where nombre like '$nombre_archivo'");

        if (mysqli_fetch_array($tabla_archivo)) {
            $select_archivo = "update archivos_exportados set fecha = now() where nombre like '$nombre_archivo' ";
        } else {
            $select_archivo = "insert into archivos_exportados (nombre) VALUES ('$nombre_archivo')";
        }

        $resultado = mysqli_query($con, $select_archivo);

        $carpeta = 'archivos/exportacion/';
        $ar      = fopen($carpeta . $nombre_archivo, 'w');

        $detalle = 'Usuario';

        // ARMA EL ENCABEZADO

        $tabla_cerrada = mysqli_query($con, "select nro,enunciado_mostrar from preguntas where id_encuesta = $id_encuesta order by nro");
        while ($registro_cerrada = mysqli_fetch_array($tabla_cerrada)) {
            $campo   = str_replace(' ', '_', $registro_cerrada[1]); // REEMPLAZA ESPACIOS X GUIONES
            $campo   = str_replace('.', '_', $campo);
            $campo   = strtoupper(trim($campo));
            $detalle = $detalle . ';' . $campo;
        }

        fputs($ar, $detalle);
        fputs($ar, "\r\n");

        $detalle = null;

        // ARMA EL DETALLE DE LA EXPORTACION

        $contador        = 0;
        $tabla_encuestas = mysqli_query($con, "select id_maestro, id_usuario from maestro_encuestas where id_encuesta = $id_encuesta");

        while ($registro_encuestas = mysqli_fetch_array($tabla_encuestas)) {
            $id_maestro = $registro_encuestas[0];
            $id_usuario = $registro_encuestas[1];

            $detalle = $id_usuario;

            $id_pregunta_ant = '';

            $tabla_preguntas = mysqli_query($con, "select preg.id_pregunta, preg.id_tipo_respuesta, cerr.id_respuesta
            from preguntas preg left join maestro_respuestas_cerradas cerr on preg.id_pregunta = cerr.id_pregunta and cerr.id_maestro = $id_maestro order by nro");

            while ($registro_preguntas = mysqli_fetch_array($tabla_preguntas)) {
                $id_respuesta = $registro_preguntas[2];
                $id_pregunta  = $registro_preguntas[0];

                switch ($registro_preguntas[1]) {
                    case '1':  // RESPUESTAS ABIERTAS
                        $tabla_respuesta = mysqli_query($con, "select respuesta from maestro_respuestas_abiertas where id_maestro = $id_maestro and id_pregunta = $id_pregunta");
                        break;
                    case '2':	// RESPUESTAS CERRADAS
                        $tabla_respuesta = mysqli_query($con, "select valor_spss from respuestas where id_respuesta = $id_respuesta");
                        break;
                    case '3':
                        // RESPUESTAS TABLA DE OPINION
                        $tabla_respuesta = mysqli_query($con, "select id_opinion from tabla_opinion where id_opinion = $id_respuesta");
                        break;
                    case '4':
                        // RESPUESTAS CRUZADAS
                        $tabla_respuesta = mysqli_query($con, "select valor_spss from respuestas where id_respuesta = $id_respuesta");
                        break;
                    case '5':
                        // RESPUESTAS EDAD
                        $tabla_respuesta = mysqli_query($con, "select id from tabla_edades where id = $id_respuesta");
                        break;
                    case '6':
                        // RESPUESTAS SEXO
                        $tabla_respuesta = mysqli_query($con, "select id from tabla_sexo where id = $id_respuesta");
                        break;
                    case '7':
                        // RESPUESTAS NIVEL DE INSTRUCCION
                        $tabla_respuesta = mysqli_query($con, "select id_nivel from tabla_nivel_instruccion where id_nivel = $id_respuesta");
                        break;
                    case '8':
                        // RESPUESTAS OCUPACION
                        $tabla_respuesta = mysqli_query($con, "select id_ocupacion from tabla_ocupacion where id_ocupacion = $id_respuesta");
                        break;
                    case '9':
                        // RESPUESTAS ZONAS
                        $tabla_respuesta = mysqli_query($con, "select id from tabla_zonas where id = $id_respuesta");
                        break;
                    case '10':
                        // RESPUESTAS MULTIPLES CHECK
                        $tabla_respuesta = mysqli_query($con, "select valor_spss from respuestas where id_respuesta = $id_respuesta");
                        break;
                    case '11':
                        // RESPUESTAS SI/NO/NsNc
                        $tabla_respuesta = mysqli_query($con, "select id from tabla_sino where id = $id_respuesta");
                        break;
                    case '12':
                        // RESPUESTAS SI/NO/NsNc/Desconoce
                        $tabla_respuesta = mysqli_query($con, "select id_opinion from tabla_opinion_no_evalua where id = $id_respuesta");
                        break;
                }

                try {

                    $registro_respuesta = mysqli_fetch_array($tabla_respuesta);

                    if($id_pregunta_ant == $id_pregunta){
                        $detalle = $detalle.'-'.$registro_respuesta[0];
                    }else{
                       //  QUITO POSIBLES COMAS Y PUNTO/COMA
                        $registro_respuesta[0] = str_replace(',','',$registro_respuesta[0]);
                        $registro_respuesta[0] = str_replace(';','',$registro_respuesta[0]);
                        $detalle = $detalle.';'.$registro_respuesta[0];
                    }

                $tabla_respuesta = NULL;

                $id_pregunta_ant = $id_pregunta;

                } catch (Exception $ex) {
                    echo 'Revisar IdMaestro '.$id_maestro.' Preguntas '.$registro_preguntas[1].' NroError '.$ex;
                }
            }

            fputs($ar, $detalle);
            fputs($ar, "\r\n");

            $contador++;
        }

        fclose($ar);

        print "Se exportaron <b>$contador</b> encuestas.<br>";
    }
    if ($_POST['marca'] == 2) {
        $id_archivo     = $_POST['id'];
        $nombre_archivo = $_POST['nombre'];
        $carpeta        = 'archivos/exportacion/';
        if (file_exists($carpeta . $nombre_archivo)) {
            unlink($carpeta . $nombre_archivo);
        }

        mysqli_query($con, "delete from archivos_exportados where id_archivo = $id_archivo");
    }
}
?>


<table class="table table-hover">
    <tr>
        <td>Archivos generados</td>
        <td>Fecha Exportación</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <?php
$tabla_archivos = mysqli_query($con, 'select * from archivos_exportados order by fecha desc');
while ($registro_archivos = mysqli_fetch_array($tabla_archivos)) {
    ?>
    <tr>
        <td><?php print $registro_archivos['nombre']; ?>
        </td>
        <td><?php print $registro_archivos['fecha']; ?>
        </td>
        <td><a href='javascript:void(0)' onClick="descarga_archivo('<?php print $registro_archivos['nombre']; ?>')" title='Descargar archivo'>( Descargar )</td>
        <td><a href='javascript:void(0)' onClick="borrar_archivo('<?php print $registro_archivos[0]; ?>','<?php print $registro_archivos['nombre']; ?>')" title='Borrar archivo'>( Borrar )</td>
    </tr>
    <?php
}

mysqli_close($con);
?>
</table>