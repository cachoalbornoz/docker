<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}

require('../accesorios/accesos_bd.php');

$con = conectar();
$id_usuario = $_SESSION['id_usuario'];

require_once '../accesorios/encabezado.php';

?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-sx-12 col-md-12">
                    SUBIR ARCHIVOS JSON
                </div>
            </div>
        </div>
        <div class="panel-body">

            <form action="uploadjson.php" method="post" enctype="multipart/form-data" name="form_json" class="form-inline">

            
                
            <table class="table table-hover"> 
            <tr>
                <td>
                    <label class="btn btn-default btn-file">
                        <input type="file" name="uploads[]" multiple="multiple" required>
                    </label> 
                </td>
                <td class="text-right">
                    <input type="submit" value="Subir Json Files" class="btn btn-info" >                       
                </td>
            </tr>      
            
            

            </form>
        </div>    
    </div>    


<?php 
mysqli_close($con); 
require_once '../accesorios/footer.php';