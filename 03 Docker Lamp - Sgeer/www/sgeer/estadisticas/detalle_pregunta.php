<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
?>
<script src="../public/js/data_table.js"></script>

<script type="text/javascript">    

$('#preguntas')
    .DataTable({
        stateSave: true,
        info: true,
        binfo: true,
        oPaginate: false,
        sInfo: false,
        sLengthMenu: false,
        lengthChange: true,
        responsive: true,
        paging: false,
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ preguntas",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "_START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar ",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
</script>

<?php
require('../accesorios/accesos_bd.php');

$con=conectar();

$id_encuesta = $_SESSION['id_encuesta'];

$tabla_encuesta = mysqli_query($con,"select * from encuestas where id_encuesta = $id_encuesta");
$registro_encuesta = mysqli_fetch_array($tabla_encuesta);

$nombre_encuesta = $registro_encuesta[1];
$estado = $registro_encuesta[2];

if(isset($_POST['operacion'])){	
    if($_POST['operacion'] == 1){
        $tabla_preguntas = mysqli_query($con, "select max(nro) from preguntas where id_encuesta = $id_encuesta");
        $registro_preguntas = mysqli_fetch_array($tabla_preguntas);
        $nro = $registro_preguntas[0] + 1;

        $enunciado  = strtoupper($_POST['enunciado']);
        $enunciado_m= strtoupper($_POST['enunciado_mostrar']);
        $tipo_resp  = $_POST['tipo_resp'];

        $inserta_pregunta = mysqli_query($con, "insert into preguntas (id_encuesta, enunciado, enunciado_mostrar, id_tipo_respuesta, nro) values ($id_encuesta, '$enunciado', '$enunciado_m', $tipo_resp, $nro)") or die('Revisar ingreso de preguntas');

    }else{
        if($_POST['operacion'] == 2){
            
            $cadena = explode(',',$_POST['cadena']);
    
            foreach ($cadena as $id) {         
                
                $id_pregunta = $id;
                
                mysqli_query($con, "delete from preguntas where id_pregunta = $id_pregunta");
                mysqli_query($con, "delete from respuestas where id_pregunta = $id_pregunta");
                mysqli_query($con, "delete from maestro_respuestas_cerradas where id_pregunta = $id_pregunta");
                mysqli_query($con, "delete from maestro_respuestas_abiertas where id_pregunta = $id_pregunta");
                mysqli_query($con, "delete from maestro_respuestas_cruzadas where id_pregunta = $id_pregunta");
            }
        }
    }
    //REORDENAR NRO PREGUNTAS
    $nro = 1;
    $tabla_preguntas = mysqli_query($con, "select id_pregunta, nro from preguntas where id_encuesta = $id_encuesta order by nro asc");
    while($registro_preguntas = mysqli_fetch_array($tabla_preguntas)){
        $id_pregunta = $registro_preguntas[0];
        mysqli_query($con, "update preguntas set nro = $nro where id_pregunta = $id_pregunta");
        $nro = $nro + 1;		
    }	
}


?>

<table id="preguntas" class="table table-hover">
<thead>
    <tr class="bg-info">
        <th>
            <a href="#" onclick="elimina_pregunta()">
                <span class="glyphicon glyphicon-trash">  </span> 
            </a>
        </th>
        <th>Nro</th>
        <th>Enunciado Encuesta</th>
        <th>Enunciado Resultado</th>
        <th>Tipo</th>
        <th>&nbsp;</th>
    </tr>
</thead>
<tbody>
<?php

if($estado < 2){
    $tabla_preguntas = mysqli_query($con, "select preg.*, tr.tipo from preguntas as preg, tipo_respuestas as tr where preg.id_tipo_respuesta = tr.id_tipo_respuesta
    and preg.id_encuesta = $id_encuesta order by preg.nro desc");
    while($registro_preguntas = mysqli_fetch_array($tabla_preguntas)){
    ?>
    <tr onmouseover="this.style.backgroundColor='#CCCCCC'" onmouseout="this.style.backgroundColor='#FFFFFF'">
        <td><input type="checkbox" name="pregunta[]" value="<?php echo $registro_preguntas[0] ?>"></td>
        <td><?php echo $registro_preguntas[4] ?></td>
        <td><a href="padron_respuestas.php?id_pregunta=<?php echo $registro_preguntas[0] ?>" class="link"><?php echo $registro_preguntas[3] ?></a></td>
        <td><?php echo $registro_preguntas[5] ?></td>
        <td><?php echo $registro_preguntas[6] ?></td>
        <td><a href='javascript:void(0)' title='Modifica pregunta' onClick="modifica_pregunta('<?php echo $registro_preguntas[0] ?>')"><span class="glyphicon glyphicon-pencil"></span></a></td>
    </tr>
    <?php
    }
}else{
    $nombre_tabla = $id_encuesta.'_etiquetas';
    $tabla_preguntas = mysqli_query($con, "select * from $nombre_tabla group by campo order by id_respuesta");
    while($registro_preguntas = mysqli_fetch_array($tabla_preguntas)){
    ?>
    <tr>
        <td>&nbsp;</td>
        <td><a href="padron_respuestas_imp.php?campo=<?php echo $registro_preguntas[1] ?>"><?php echo $registro_preguntas[1] ?></a></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><a href='javascript:void(0)' title='Modifica pregunta' onClick="modifica_pregunta_imp('<?php echo $registro_preguntas[0] ?>')"><span class="glyphicon glyphicon-pencil"></span></a></td>
        <td>&nbsp;</td>
    </tr>
    <?php	
    }
}


mysqli_close($con);
?>										
</tbody>	
</table>
