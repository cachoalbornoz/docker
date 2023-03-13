<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/accesos_bd.php');

$con=conectar();
$estado 	= $_SESSION['estado'];
$id_encuesta 	= $_SESSION['id_encuesta'];

?>
<table class="table" style="text-align: center">
    <thead>
    <tr>
        <td>&nbsp;</td>
        <td class="bg-info" style="text-align: left">Enunciados de la Encuesta</td>
        <td class="bg-info">Tipo</td>
        <td class="bg-info">Circular</td>
        <td class="bg-info">Barras</td>
        <td class="bg-info">Mapas</td>
    </tr>
    </thead>
    <tbody>
    <?php    
    $contador = 1;	
    $tabla_preguntas_graficas = mysqli_query($con, "select preg.*,tipo.tipo from preguntas preg inner join tipo_respuestas tipo on preg.id_tipo_respuesta = tipo.id_tipo_respuesta
    where id_encuesta = $id_encuesta order by nro ");
    while($registro_preguntas_graficas = mysqli_fetch_array($tabla_preguntas_graficas)){
    $tipo = $registro_preguntas_graficas['id_tipo_respuesta'];
    ?>
    <tr onmouseover="this.style.backgroundColor='#CCCCCC'" onmouseout="this.style.backgroundColor='#FFFFFF'">   
        <td class="bg-info">
            <?php echo $contador ;?> <span class="glyphicon glyphicon-screenshot"> </span> 
        </td>
        <td class="text-left">
            <?php echo $registro_preguntas_graficas[5] ; ?>
        </td>
        <td class="text-left">
            <?php echo $registro_preguntas_graficas[6] ; ?>
        </td>
        <td> 
        <?php
        if($tipo == 2 or $tipo == 3 or $tipo == 5 or $tipo == 6 or $tipo == 7 or $tipo == 8 or $tipo == 9 or $tipo == 10 or $tipo == 11 or $tipo == 12){ ?>
            <a href="mostrar_grafico.php?id_pregunta=<?php echo $registro_preguntas_graficas[0] ?>&tipo_grafico=1&tipo_pregunta=<?php echo $registro_preguntas_graficas[2]?>&enunciado=<?php echo $registro_preguntas_graficas[5]?>"><i class="fa fa-pie-chart" aria-hidden="true"></i></a>
        <?php
        }else{
            if($tipo == 1 or $tipo == 4){?>            
                <a href="../estadisticas/resultado_encuesta.php?id_pregunta=<?php echo $registro_preguntas_graficas[0]?>&tipo_pregunta=<?php echo $registro_preguntas_graficas[2]?>" title="Ver resultados">Respuestas</a>
            <?php
            }
        }
        ?>
        </td>
        <td>
        <?php
        if($tipo == 2 or $tipo == 3 or $tipo == 5 or $tipo == 6 or $tipo == 7 or $tipo == 8 or $tipo == 9 or $tipo == 10 or $tipo == 11 or $tipo == 12){ ?>
            <a href="mostrar_grafico.php?id_pregunta=<?php echo $registro_preguntas_graficas[0] ?>&tipo_grafico=2&tipo_pregunta=<?php echo $registro_preguntas_graficas[2]?>&enunciado=<?php echo $registro_preguntas_graficas[5]?>"><span class="glyphicon glyphicon-signal"></span></a>
        <?php
        }
        ?>
        </td>
        <td>
        <?php 
        
        if($tipo == 2 or $tipo == 3 or $tipo == 11){   
        ?>
            <a href="../mapas/mapa_referencial.php?id_pregunta=<?php echo $registro_preguntas_graficas[0] ?>&tipo_pregunta=<?php echo $registro_preguntas_graficas[2]?>&enunciado=<?php echo $registro_preguntas_graficas[3]?>"><span class=" glyphicon glyphicon-globe" ></span></a>
        <?php 
        }
        
        ?>       
        </td>
    </tr>
    <?php
    $contador ++ ;
    }
    mysqli_close($con);
    ?>
    </tbody>
</table>
