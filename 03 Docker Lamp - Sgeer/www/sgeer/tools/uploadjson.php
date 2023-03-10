<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require('../accesorios/accesos_bd.php');
$con=conectar();

include('../accesorios/encabezado.php');

?>

        <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sx-12 col-md-12">
                            RESUMEN DEL PROCESO
                        </div>
                    </div>
                </div>
            <div class="panel-body">
                                
                <?php       
                
                
                $contador   = 1;
                
                function convertJSONtoArray($data){
                    
                    foreach($data as $key => $value){
                        if(is_array($value)){
                            if(isset($value["geometry"])){
                                echo "Encontrado";
                            }else{                                
                                foreach($value as $k => $v){
                                    if(is_array($v)){
                                        if(isset($v["geometry"])){                                        
                                            return $v;
                                        }                            
                                    }
                                }    
                                
                            }                            
                        }
                    }
                }
                
                
                
                
                for($i=0; $i<count($_FILES['uploads']['name']); $i++) {

                    $nombre = $_FILES['uploads']['name'][$i];

                    if(move_uploaded_file($_FILES['uploads']['tmp_name'][$i],$nombre)){ ////Comprobamos si el archivo subio
                        
                        $datosjson  = file_get_contents($nombre); 
                        $data       = json_decode($datosjson, true);                   
                        echo "<pre>";
                        var_dump(convertJSONtoArray($data));
                        echo "</pre>";
                    }
                }    

                unlink($nombre); 

                ?> 
                
                
            </div>
        </div>    


<?php 
    mysqli_close($con);
    require_once '../accesorios/footer.php';