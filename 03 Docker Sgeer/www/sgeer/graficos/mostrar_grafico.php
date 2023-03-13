<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/accesos_bd.php');
$con=conectar();

$id_encuesta 	= $_SESSION['id_encuesta'];
$id_pregunta 	= $_GET['id_pregunta'];
$tipo_grafico 	= $_GET['tipo_grafico'];

$enunciado = strip_tags($_GET['enunciado']);

unset($titulos, $valores);

$colores = array("#F2C357","#FF0000","#04B404","#FE642E", "#FFFF00","#FF00BF","#BDBDBD","#E6E6E6","#B40404","#80FF00","#00FFFF","#FA58F4",
    "#FE2E9A","#F6D8CE","#38610B","#86B404","#8904B1","#F5A9E1","#F5A9A9","#F3F781","#E3CEF6","#000000");

$tabla_pregunta = mysqli_query($con, "select id_tipo_respuesta from preguntas where id_pregunta = $id_pregunta");
$registro_pregunta =  mysqli_fetch_array($tabla_pregunta);

$tipo = $registro_pregunta[0];

switch ($tipo){
    case '2':{		  	
        // RESPUESTA TIPO CERRADA
        $seleccion_tabla = mysqli_query($con, "select id_respuesta, enunciado from respuestas where id_pregunta = $id_pregunta");
        break;
    }
    case '3':{
        // RESPUESTA TIPO OPINION
        $seleccion_tabla = mysqli_query($con, "select * from tabla_opinion");
        break;
    }
    case '5':{
        // RESPUESTA TIPO EDAD
        $seleccion_tabla = mysqli_query($con, "select * from tabla_edades"); 
        break;
    }
    case '6':{
        // RESPUESTA TIPO SEXO
        $seleccion_tabla = mysqli_query($con, "select * from tabla_sexo");
        break;
    }
    case '7':{
        // RESPUESTA TIPO NIVEL INSTRUCCION
        $seleccion_tabla = mysqli_query($con, "select * from tabla_nivel_instruccion");
        break;
    }
    case '8':{
        // RESPUESTA TIPO OCUPACION
        $seleccion_tabla = mysqli_query($con, "select * from tabla_ocupacion");
        break;
    }
    case '9':{
        // RESPUESTA TIPO ZONAS
        $seleccion_tabla = mysqli_query($con, "select * from tabla_zonas");
        break;
    }
    case '10':{
        // RESPUESTA TIPO CERRADA
        $seleccion_tabla = mysqli_query($con, "select id_respuesta, enunciado from respuestas where id_pregunta = $id_pregunta");
        break;
    }
     case '11':{
        // RESPUESTA TIPO SI/NO/NsNc
        $seleccion_tabla = mysqli_query($con, "select * from tabla_sino");
        break;
    }
    case '12':{
        // RESPUESTA TIPO OPINION
        $seleccion_tabla = mysqli_query($con, "select * from tabla_opinion_no_evalua");
        break;
    }
}

$contador=0;		
while($registro_tabla =  mysqli_fetch_array($seleccion_tabla)){	
			
    $opcion_valor = $registro_tabla[0];
    $opcion_rotulo=$registro_tabla[1];

    $seleccion_respuesta = mysqli_query($con, "select count(id_respuesta) from maestro_respuestas_cerradas where id_respuesta = $opcion_valor and id_pregunta = $id_pregunta");
    if($registro_respuesta = mysqli_fetch_array($seleccion_respuesta)){		
        $titulos[$contador]=$registro_tabla[1];
        $valores[$contador]=$registro_respuesta[0];
        $contador++;
    }
}
	
$mayor = 0;
$posicion = 0;		

for ($i=0; $i<$contador; $i++){
	   
    if($valores[$i]>$mayor){
        $posicion = $i;
        $mayor = $valores[$i];
    }; 
}

require_once ('../accesorios/encabezado.php');
?>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
    <script type="text/javascript">
    google.load('visualization', '1.0', {'packages':['corechart']});

    google.setOnLoadCallback(drawChart);

    function drawChart() {

    if(<?php echo $tipo_grafico?> == 1){ 
    // GRAFICO TORTA
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Sondeo');
        data.addColumn('number', 'Valores');
        data.addRows([
        <?php
        for ($i=0; $i<$contador; $i++){

            echo "['".$titulos[$i]."',".$valores[$i]."],";   
        }
        ?>
        ]);

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));

    }else{ 
    // GRAFICO BARRAS
        var data = google.visualization.arrayToDataTable([

        ['Valor', 'Encuestados', { role: 'style' }],

        <?php
        for($i=0; $i<$contador; $i ++){
            ?>
            ['<?php echo $titulos[$i] ?>',
             <?php echo $valores[$i] ?>, 
             '<?php echo $colores[$i] ?>'],            
        <?php
        }
        ?>
        ]);

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));  
    }

    <?php 
    if($tipo_grafico == 1){
    $legenda= "right";
    }else{
    $legenda= "none";
    }

    ?> 

    var options = {
        'title': '<?php echo $enunciado; ?> -  CASOS <?php echo $_SESSION['nro_casos'] ?> ',
        'is3D':true,
        'enableInteractivity':true,
        'legend': { position: '<?php echo $legenda ?>', maxLines: 20 },
        'colors': ['#013ADF','#01A9DB','#DF013A','#DF01A5', '#7401DF','#01DFA5','#01DF01','#A5DF00','#088A68','#6E6E6E','#EC9EF5','#9EDFF5','#EAF429','#686956','#C9CB9C'],
        'width':1200,
        'height':400,
        'fontSize':12,
        'slices': {<?php echo $posicion;?>: {offset: 0.4},}

        };

    chart.draw(data, options);
    }
    </script>
    
    <div style="float:left; width:100%" align="center" id="chart_div"></div>
    
    <div style="float:right; width:100%; padding-top: 5%">
        
        <table class="table" style="font-size:small ">
        <?php 
        $tabla_pregunta = mysqli_query($con, "select id_tipo_respuesta from preguntas where id_pregunta = $id_pregunta");
        $registro_pregunta =  mysqli_fetch_array($tabla_pregunta);

        $tabla_pregunta = mysqli_query($con, "select id_tipo_respuesta from preguntas where id_pregunta = $id_pregunta");
        $registro_pregunta =  mysqli_fetch_array($tabla_pregunta);
        
        $tipo = $registro_pregunta[0];

        switch ($tipo){
            case '2':{		  	
                // RESPUESTA TIPO CERRADA
                $seleccion_tabla = mysqli_query($con, "select id_respuesta, enunciado from respuestas where id_pregunta = $id_pregunta");
                break;
            }
            case '3':{
                // RESPUESTA TIPO OPINION
                $seleccion_tabla = mysqli_query($con, "select * from tabla_opinion");
                break;
            }
            case '5':{
                // RESPUESTA TIPO EDAD
                $seleccion_tabla = mysqli_query($con, "select * from tabla_edades"); 
                break;
            }
            case '6':{
                // RESPUESTA TIPO SEXO
                $seleccion_tabla = mysqli_query($con, "select * from tabla_sexo");
                break;
            }
            case '7':{
                // RESPUESTA TIPO NIVEL INSTRUCCION
                $seleccion_tabla = mysqli_query($con, "select * from tabla_nivel_instruccion");
                break;
            }
            case '8':{
                // RESPUESTA TIPO OCUPACION
                $seleccion_tabla = mysqli_query($con, "select * from tabla_ocupacion");
                break;
            }
            case '9':{
                // RESPUESTA TIPO ZONAS
                $seleccion_tabla = mysqli_query($con, "select * from tabla_zonas");
                break;
            }
            case '10':{
                // RESPUESTA TIPO CERRADA
                $seleccion_tabla = mysqli_query($con, "select id_respuesta, enunciado from respuestas where id_pregunta = $id_pregunta");
                break;
            }
            case '11':{
                // RESPUESTA TIPO SI/NO/NsNc
                $seleccion_tabla = mysqli_query($con, "select * from tabla_sino");
                break;
            }
            case '12':{
                // RESPUESTA TIPO OPINION
                $seleccion_tabla = mysqli_query($con, "select * from tabla_opinion_no_evalua");
                break;
            }
        }

        $contador=0;		
        while($registro_tabla =  mysqli_fetch_array($seleccion_tabla)){	

            $opcion_valor = $registro_tabla[0];
            $opcion_rotulo=$registro_tabla[1];

            $seleccion_respuesta = mysqli_query($con, "select count(id_respuesta) from maestro_respuestas_cerradas where id_respuesta = $opcion_valor and id_pregunta = $id_pregunta");
            if($registro_respuesta = mysqli_fetch_array($seleccion_respuesta)){	
            ?>
                <tr>
                    <td align="left"  ><?php echo $registro_tabla[1]; ?></td>
                    <td align="center"><?php echo $registro_respuesta[0]; ?></td>
                </tr>
            <?php
            }
        }
        ?>   
        <tr>
            <td>&nbsp;</td>
            <td align="center"><b><?php echo $_SESSION['nro_casos'] ?></b></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        </table>
    </div> 	

<?php 
mysqli_close($con);
require_once '../accesorios/footer.php';
