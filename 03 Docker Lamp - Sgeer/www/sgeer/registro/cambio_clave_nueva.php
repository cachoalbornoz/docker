<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('location:../accesorios/salir.php');
    exit;
}
require_once('../accesorios/accesos_bd.php');
$con=conectar();

require_once '../accesorios/encabezado.php'; 

?>

    <script type="text/javascript">
    $(document).ready(function(){
        $("#password").select()	
    });
    
    function cambiar(){	
        var passn=document.getElementsByName("password_nueva")[0]
        var passn1=document.getElementsByName("password_nueva1")[0]
    
        if ((passn.value !== passn1.value)||(passn1.value ==0))	{
            $("#estado").show()
            $("#estado").html("Las claves no coinciden")
            $("#estado").fadeOut(3500);
        }else{
            var form = document.getElementById("clave")
            form.submit()
        }
    }
    </script>
    
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-sx-12 col-md-12">
                    CAMBIO DE CLAVE
                </div>

            </div>
        </div>
        <div class="panel-body">   
     
            <form id="clave" name="clave" method="post" action="verifica_password.php">
            <div class="row">
                <div class="col-sm-4">
                    <input name="password" type="password" id="password" size="25" placeholder="Clave actual" class="form-control"/>
                </div>
                <div class="col-sm-4">

                </div>        
                <div class="col-sm-4">

               </div>  
            </div>  
            <div class="row">
                <hr>
            </div>    

            <div class="row"> 
                <div class="col-sm-4">
                    <input name="password_nueva" type="password" id="password_nueva" size="25" placeholder="Clave nueva" class="form-control"/>
                </div>        
                <div class="col-sm-4">
                    <input name="password_nueva1" type="password" id="password_nueva1" size="25" placeholder="Reingrese clave nueva" class="form-control"/>       
                </div>
                <div class="col-sm-4" style="text-align: right">
                    <button type="button" class="btn btn-info" onClick="cambiar()">Cambiar clave</button>
                </div>  
            </div>  


            <div class="row">
                <div class="col-sm-4">   </div>
                <div class="col-sm-4">   </div>        
                <div class="col-sm-4">   
                    <div style="float:right">
                        <h4>
                        <?php
                        if (isset($_SESSION['verifica']) and (!$_SESSION['verifica'])){
                            echo "<span style='color:red'>Clave incorrecta, no se modifico </span>"; 
                        }
                        if (isset($_SESSION['verifica']) and ($_SESSION['verifica'])){
                            echo "<span style='color:green'>Clave modificada correctamente </span>";   
                        }
                        unset($_SESSION['verifica']);
                        ?>

                        </h4>
                    </div>    
                </div>  
            </div> 
            </form>  
        </div> 
    </div>
    
<?php

require_once '../accesorios/footer.php';
