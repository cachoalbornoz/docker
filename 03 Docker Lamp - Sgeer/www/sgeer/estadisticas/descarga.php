<?php

require('../accesorios/accesos_bd.php');

$con=conectar();

$id_encuesta = $_GET['id'];

$tabla = mysqli_query($con, "select nombre_informe from archivos_importados where id_encuesta = $id_encuesta");
$registro = mysqli_fetch_array($tabla);

$nombre_archivo = $registro[0];

$nombre_archivo = '../backup/archivos/informes/'.$nombre_archivo;

mysqli_close($con);

if(strlen($registro[0]) > 0){
    download_file($nombre_archivo);
}else{
    echo '
    <script type="text/javascript">
        alert("Disculpe, no hay informe para esa encuesta.")
        window.history.back()
    </script>
    ';
}

function download_file($archivo, $downloadfilename = null){
    if (file_exists($archivo)){
        $downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($archivo);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $downloadfilename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($archivo));

        ob_clean();
        flush();
        readfile($archivo);
        exit;
    }
} 

?>