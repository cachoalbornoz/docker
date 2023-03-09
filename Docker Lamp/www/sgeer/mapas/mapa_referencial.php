<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}

require_once '../accesorios/encabezado.php';

require_once '../accesorios/accesos_bd.php';

$con=conectar();

$id_encuesta= $_SESSION['id_encuesta'];
$id_pregunta= $_GET['id_pregunta'];
$tipo_encuesta=$_GET['tipo_pregunta'];

$latitud    = $_SESSION['latitud'];
$longitud   = $_SESSION['longitud'];


$colores = array("#F2C357","#FF0000","#04B404","#FE642E", "#FFFF00","#FF00BF","#BDBDBD","#E6E6E6","#B40404","#80FF00","#00FFFF","#FA58F4",
    "#FE2E9A","#F6D8CE","#38610B","#86B404","#8904B1","#F5A9E1","#F5A9A9","#F3F781","#E3CEF6","#000000");

$indice  = 0;
?>

    
    <link href="../public/libreria/leaflet/leaflet.css" rel="stylesheet" type="text/css"/>
    <script src="../public/libreria/leaflet/leaflet.js" type="text/javascript"></script>
   
    <!-- Librerias Mouse Position -->
    <link href="../public/libreria/Leaflet.MousePosition-master/src/L.Control.MousePosition.css" rel="stylesheet" type="text/css"/>
    <script src="../public/libreria/Leaflet.MousePosition-master/src/L.Control.MousePosition.js" type="text/javascript"></script>
   
    <!-- Librerias Print Layer-->    
    <script src="../public/libreria/leaflet-easyPrint-gh-pages/dist/leaflet.easyPrint.js" type="text/javascript"></script>
    
    <!-- Librerias Scale Layer-->
    <script src="../public/libreria/leaflet-graphicscale-master/dist/Leaflet.GraphicScale.min.js" type="text/javascript"></script>
    <link href="../public/libreria/leaflet-graphicscale-master/dist/Leaflet.GraphicScale.min.css" rel="stylesheet" type="text/css"/>    
    
    <!-- Icons-->
    <link href="../public/libreria/leaflet-mapkey-icon-master/dist/L.Icon.Mapkey.css" rel="stylesheet" type="text/css"/>
    <script src="../public/libreria/leaflet-mapkey-icon-master/dist/L.Icon.Mapkey.js" type="text/javascript"></script>
    
    <!-- LayerGroup-->
    <link href="../public/libreria/leaflet-groupedlayercontrol-gh-pages/src/leaflet.groupedlayercontrol.css" rel="stylesheet" type="text/css"/>
    <script src="../public/libreria/leaflet-groupedlayercontrol-gh-pages/src/leaflet.groupedlayercontrol.js" type="text/javascript"></script>
    
    <!-- Bing Layers -->
    
    <script src="../public/libreria/leaflet-bing-layer-gh-pages/leaflet-bing-layer.js" type="text/javascript"></script>
    
    <style>    
    #map { 
        position: absolute; 
        right:0; 
        left:0;
        top: 16%;
        width : 70%;
        height: 80%;
        box-shadow: 5px 5px 5px #888;     
    }
    </style>  
    
    <div id = 'map' class="container">   </div>  
    
    <script>
    /////////////////////////////////////////////////////////////////
    var mapkey = 'pk.eyJ1IjoiY2FjaG9hbGJvcm5veiIsImEiOiJjaXFuczZudTQwMWJkZ3NqZmg5ZWd1dmljIn0.-2dfXqyCXP-d_ij7Sp1waA';
    
    var bingkey= 'Av1z4G0ITtfkMdxUW0qsvJHvYZbjDXOVibWwMhCmEJxAXf-YHYL1uoRjU9YBE-s6';
    
    var base1 = L.tileLayer('http://{s}.tile.opentopomap.org/{z}/{x}/{y}.png'),
        base2 = L.tileLayer('http://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png');
        base3 = L.tileLayer('http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png');
        base4 = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/satellite-v9/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiY2FjaG9hbGJvcm5veiIsImEiOiJjaXFuczZudTQwMWJkZ3NqZmg5ZWd1dmljIn0.-2dfXqyCXP-d_ij7Sp1waA');
        bing  = L.tileLayer.bing(bingkey);
    
    var baseLayers = {
        "MapaTopoGrafico": bing,
        "MapaFondoCalle1": base2,
        "MapaFondoCalle2": base3,        
        "MapaFondoBasico": base1,
        
    };    
    /////////////////////////////////////////////////////////////////
    var map = L.map('map',{
    fullscreenControl: true,
    fullscreenControlOptions: {position: 'topright'}}).setView([<?php echo $latitud ?>,<?php echo $longitud ?>],12);
    
    L.tileLayer('http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution:'SGE - Sistema Gestor de Encuestas'
    }).addTo(map);
   

    /////////////////////////////////////////////////////////////////
    L.control.mousePosition().addTo(map);
    /////////////////////////////////////////////////////////////////
    L.easyPrint({title: 'Imprimir mapa', position: 'topright', elementsToHide: 'p, h2'}).addTo(map);
    ////////////////////////////////////////////////////////////////
    var graphicScale = L.control.graphicScale({labelPlacement: 'bottomright'}).addTo(map);        
    /////////////////////////////////////////////////////////////////  
    </script>
    <?php 
    $i = 0;
    
    if($tipo_encuesta == 2){
        // TIPO DE RESPUESTAS CERRADAS
        $seleccion_marcadores = mysqli_query($con, "select mae_cerr.id_pregunta, latitud, longitud, mae_cerr.id_respuesta, resp.enunciado 
        from maestro_posiciones mae
        inner join maestro_respuestas_cerradas mae_cerr on mae.id_posicion = mae_cerr.id_maestro
        inner join respuestas resp on resp.id_respuesta = mae_cerr.id_respuesta
        where mae.latitud < 0 and mae.longitud < 0 and mae_cerr.id_pregunta = $id_pregunta
        order by enunciado") or die('Revisar Lectura de Posiciones');
        
    }else{
        // TIPO DE RESPUESTAS DE OPINION
        $seleccion_marcadores = mysqli_query($con, "select mae_cerr.id_pregunta, latitud, longitud, mae_cerr.id_respuesta, resp.opinion
        from maestro_posiciones mae
        inner join maestro_respuestas_cerradas mae_cerr on mae.id_posicion = mae_cerr.id_maestro
        inner join tabla_opinion resp on resp.id_opinion = mae_cerr.id_respuesta
        where mae.latitud < 0 and mae.longitud < 0 and mae_cerr.id_pregunta = $id_pregunta order by resp.id_opinion")or die('Revisar Lectura de Posiciones');
    }
    
    $registro_marcadores = mysqli_fetch_array($seleccion_marcadores);
 
    $id_respuesta   = $registro_marcadores[3];      // LE ASIGNA EL ID DE LA PRIMER RESPUESTA

    $latitud    = round($registro_marcadores[1],8);
    $longitud   = round($registro_marcadores[2],8);
    $rotulo     = sanear_string($registro_marcadores[4]);
    
    $color      = $colores[$indice];        // ASIGNA UN COLOR     
    $titulos[$i]= $rotulo;
    ?>
    <script>  
    var latitud = <?php echo $latitud ?>;
    var longitud= <?php echo $longitud ?>;
    
    var <?php echo $rotulo ?> = new L.LayerGroup(); 
    var mki = L.icon.mapkey({icon:"marker",color:'#725139',background:'<?php echo $color ?>',size:10,boxShadow:false}); 
    L.marker([latitud,longitud],{icon:mki}).bindTooltip('<?php echo $rotulo ?>').addTo(<?php echo $rotulo ?>);     
    </script>
    <?php
    
    
    while($registro_marcadores = mysqli_fetch_array($seleccion_marcadores)){ 
        $rotulo     = sanear_string($registro_marcadores[4]);
        $latitud    = round($registro_marcadores[1],8);
        $longitud   = round($registro_marcadores[2],8);
        if($id_respuesta == $registro_marcadores[3]){        
            $color  = $colores[$indice];        
        }else{
            $indice ++;
            $color = $colores[$indice];
            $titulos[$indice] = $rotulo; ?>
            <script>
                var <?php echo $rotulo ?> = new L.LayerGroup(); 
            </script>
        <?php
        }     
        ?>
        <script>
            var latitud = <?php echo $latitud ?>;
            var longitud= <?php echo $longitud ?>;
            var mki = L.icon.mapkey({icon:"marker",color:'#725139',background:'<?php echo $color ?>',size:10,boxShadow:false}); 
            
            L.marker([latitud,longitud],{icon:mki}).bindTooltip('<?php echo $rotulo ?>').addTo(<?php echo $rotulo ?>);  
        </script>
        <?php
        $id_respuesta  = $registro_marcadores[3];     // LE ASIGNA EL ID DE LA PRIMER RESPUESTA
    }  
    ?> 
        
        
    <div class="container bg-success">
        <ul class="nav navbar-nav">
            <li class="active">
                <a href="#" title="Pose el mouse sobre los colores">
                    <h6><?php echo $_GET['enunciado'] ?></h6>
                </a>    
            </li>
            <?php for($i=0;$i <= $indice; $i++){ ?>
                <li>
                    <a href="#">
                        <h6>
                            <span class="badge" style="background-color: <?php echo $colores[$i]?>;">&nbsp;</span>
                            <?php echo $titulos[$i]?>
                        </h6>
                    </a>
                </li>
            <?php } ?>
        </ul>    
    </div>
    
    
    
        
    <script>
    // Overlay layers are grouped
    var groupedOverlays = {
      "Opciones": {
          
        <?php            
        for($i=0;$i <= $indice; $i++){            
        ?>           
            "<?php echo $titulos[$i]?>": <?php echo $titulos[$i]?>,
        <?php
        }
        ?>
      }
    };
      // Use the custom grouped layer control, not "L.control.layers"
    L.control.groupedLayers(baseLayers, groupedOverlays,{collapsed:false}).addTo(map);      
    </script>
    
 <div class="container"> 
   
     
<?php 

require '../accesorios/footer.php';