<?php 
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/accesos_bd.php');
$con=conectar();

$id_encuesta= $_GET['id_encuesta'];

//OBTENER LA LATITUD Y LONGITUD PARA CENTRAR EL MAPA DE ENCUESTAS

$nombre_marcador = 'marcadores_'.$id_encuesta.'.xml'; 

if (file_exists('../mapas/marcadores/'.$nombre_marcador)) { 
		
    $seleccion  = mysqli_query($con,"select avg(latitud), avg(longitud) from maestro_posiciones where latitud < 0 and id_encuesta = $id_encuesta");
    $registro   = mysqli_fetch_array($seleccion,MYSQL_BOTH);

    $latitud    = $registro[0];
    $longitud   = $registro[1];

    header('location:mapa.php?latitud='.$latitud.'&longitud='.$longitud.'&archivo='.$nombre_marcador);
    
}
else{
    echo '
    <script type="text/javascript">
        alert("Disculpe, no hay posiciones registradas.")
        window.history.back()
    </script>
    ';
}
