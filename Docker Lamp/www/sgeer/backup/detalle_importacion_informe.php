<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}

require('../accesorios/accesos_bd.php');
$con=conectar();

    

$nombre_archivo = $_FILES['informe']['name'];

$id_encuesta = $_SESSION['id_encuesta'];

$tabla_informes = mysqli_query($con, "select id_encuesta from archivos_importados where id_encuesta = $id_encuesta");

if(mysqli_num_rows($tabla_informes) == 0){
    $inserta_informe = mysqli_query($con, "insert into archivos_importados (nombre_informe, id_encuesta) values ('$nombre_archivo', $id_encuesta)");
}else{
    $registro_informes = mysqli_fetch_array($tabla_informes);
    $id_encuesta = $registro_informes[0];
    mysqli_query($con, "update archivos_importados set fecha = NOW(), nombre_informe = '$nombre_archivo' where id_encuesta = $id_encuesta") or die("Revisar actualización tabla informes existente");		
}

$carpeta = '../backup/archivos/informes/';
move_uploaded_file($_FILES['informe']['tmp_name'],$carpeta.$nombre_archivo);
chmod($carpeta.$nombre_archivo,0777);	
    
require_once ('../accesorios/encabezado.php');

?>   
<div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sx-12 col-md-12">
                        IMPORTACION DE INFORMES
                    </div>
                </div>
            </div>
            <div class="panel-body">
    
                <table class="table">
                    <tr>
                      <td colspan="2"><?php echo "<b> $nombre_archivo </b> subido correctamente ";?></td>
                      <td align="right"><input type="button" onclick="window.history.back()" value="Salir" class="boton"></td>
                    </tr>
                </table>
        </div>     
    </div>
      
<?php 

require_once '../accesorios/footer.php';
