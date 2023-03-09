<?php
session_start();

require('../accesorios/accesos_bd.php');

$con=conectar();
					
$tabla_encuestas = mysqli_query($con, "select * from encuestas where estado = 1");

if($registro_encuestas = mysqli_fetch_array($tabla_encuestas)){

    $id_encuesta = $_SESSION['id_encuesta']	= $registro_encuestas[0];

    $_SESSION['id_encuesta']= $registro_encuestas[0];
    $_SESSION['nombre']     = $registro_encuestas[1];

    $tabla_respuestas       = mysqli_query($con, "select count(id_pregunta) from preguntas where id_encuesta = $id_encuesta");
    $registro_preguntas     = mysqli_fetch_array($tabla_respuestas);
    $_SESSION['cant_preg']  = $registro_preguntas[0];    
}

if(isset($_SESSION['cant_preg']) && $_SESSION['cant_preg'] > 5){
    
    $paginador = array();
    
    $tabla_paginadores = mysqli_query($con, "select * from paginadores order by nro_pregunta");
    while($registro_paginadores = mysqli_fetch_array($tabla_paginadores)){
        
        $paginador[] = array("nombre" => $registro_paginadores[1], "pagina" => $registro_paginadores[2]);        
    }    
  

?>
<!DOCTYPE html>
<html>
<head>
    <title>SgeerMovil</title>
    <meta charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link rel="stylesheet" href="temas/MiEstilo.min.css" type="text/css" />
    <link rel="stylesheet" href="temas/jquery.mobile.icons.min.css" type="text/css" />
    
    <link href="jquery.mobile.structure-1.4.5.min.css" rel="stylesheet" type="text/css"/>
    
    <script src="jquery.js" type="text/javascript"></script>
    <script src="custom-scripting.js" type="text/javascript"></script>
    
    <!-- ymz Alert -->
    <link href="ymz_box.css" rel="stylesheet" type="text/css"/>
    <script src="ymz_box.min.js" type="text/javascript"></script>       
    
    <script src="jquery.mobile-1.4.5.js" type="text/javascript"></script>

    <style>
        .ui-page { -webkit-backface-visibility: hidden; }
    </style>
</head>

<body>
 
    

    <form id="encuesta">
    <div data-role="page" id="pagina0" data-theme="a">        
        <div data-role="header" style="overflow: hidden">
            <h6>&COPY; SGE-V 1.1</h6>
        </div>    

        <div data-role="main" data-theme="a">
            <div class="ui-content"> 
                <div data-role="fieldcontain">
                    <input type="hidden" name="usuario" id="usuario" value="1" />
                    <input type="hidden" name="id_encuesta" id="id_encuesta" value="<?php echo $_SESSION['id_encuesta'] ?>"/> 
                </div> 
                              
                <table  style="width: 100%;margin-left: 0px;  padding: 0px; text-align: center; font-size: small">
                <thead>
                  <tr>
                    <td>EDAD</td>
                    <td>VARON</td>
                    <td>MUJER</td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>16-19</td>
                    <td><input type="number" name="tv16" id="tv16" value="0" readonly="readonly" style="text-align: center"/></td>
                    <td><input type="number" name="tm16" id="tm16" value="0" readonly="readonly" style="text-align: center"/></td>
                  </tr>
                  <tr>
                    <td>20-29</td>
                    <td><input type="number" name="tv20" id="tv20" value="0" readonly="readonly" style="text-align: center"/></td>
                    <td><input type="number" name="tm20" id="tm20" value="0" readonly="readonly" style="text-align: center"/></td>
                  </tr>
                  <tr>
                    <td>30-39</td>
                    <td><input type="number" name="tv30" id="tv30" value="0" readonly="readonly" style="text-align: center"/></td>
                    <td><input type="number" name="tm30" id="tm30" value="0" readonly="readonly" style="text-align: center"/></td>
                  </tr> 
                  <tr>
                    <td>40-49</td>
                    <td><input type="number" name="tv40" id="tv40" value="0" readonly="readonly" style="text-align: center"/></td>
                    <td><input type="number" name="tm40" id="tm40" value="0" readonly="readonly" style="text-align: center"/></td>
                  </tr>
                  <tr>
                    <td>50-59</td>
                    <td><input type="number" name="tv50" id="tv50" value="0" readonly="readonly" style="text-align: center"/></td>
                    <td><input type="number" name="tm50" id="tm50" value="0" readonly="readonly" style="text-align: center"/></td>
                  </tr>
                  <tr>
                    <td>60 +</td>
                    <td><input type="number" name="tv60" id="tv60" value="0" readonly="readonly" style="text-align: center"/></td>
                    <td><input type="number" name="tm60" id="tm60" value="0" readonly="readonly" style="text-align: center"/></td>
                  </tr> 
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>TOTAL</td>
                    <td><input type="number" name="totalv" id="totalv" value="0" readonly="readonly" style="text-align: center"/></td>
                    <td><input type="number" name="totalm" id="totalm" value="0" readonly="readonly" style="text-align: center"/></td>
                  </tr> 
                </tbody>
                </table> 
                              
            </div>            
        </div>        
        <div data-role="footer" data-position="fixed" data-tap-toggle="false">           
            <div data-role="navbar">
                <ul>
                    <li><a href="#" onClick="salida()" data-iconpos="bottom" data-icon="power">Salir</a></li>
                    <li><a href="#" data-iconpos="bottom" data-icon="arrow-l">Atras</a></li>
                    <li><a href="#pagina1" data-iconpos="bottom" data-icon="arrow-r">Inicia</a></li>
                    <li><a href="#" onClick="acerca()" data-iconpos="bottom" data-icon="info">Desarrollo</a></li>
                </ul>
            </div>            
        </div>        
    </div>
     
    <div data-role="page" id="pagina1" data-theme="a">
        <div data-role="header" style="overflow: hidden"> 
            <h6>&COPY; SGE-V 1.1</h6>           
        </div>
        <div data-role="main">
            <div data-role="content" class="ui-content" style="font-size: small;margin:20px; position:relative">
                <h3>1) ZONA</h3>
                <select id="1" name="1" data-native-menu="true" data-mini="true">
                    <option value="0" disabled="disabled" selected="selected"></option>
                    <?php
                    $seleccion_respuestas = mysqli_query($con, "select * from tabla_zonas");
                    while ($registro_respuestas = mysqli_fetch_array($seleccion_respuestas)){?>
                        <option value="<?php echo $registro_respuestas[0]?>"><?php echo $registro_respuestas[1] ?></option>                    
                    <?php    
                    }
                    ?>
                </select>  
                
                <h3>2) SEXO</h3>
                <select id="2" name="2" data-native-menu="true" data-mini="true">
                    <option value="0" disabled="disabled" selected="selected"></option>
                    <option value="1">VARON</option>
                    <option value="2">MUJER</option>
                </select>
                
                <h3>3) EDAD</h3>
                <select id="3" name="3" data-native-menu="true" data-mini="true">
                    <option value="0"></option>
                    <?php
                    $seleccion_respuestas = mysqli_query($con, "select * from tabla_edades");
                    while ($registro_respuestas = mysqli_fetch_array($seleccion_respuestas)){?>
                        <option value="<?php echo $registro_respuestas[0]?>"><?php echo $registro_respuestas[1] ?></option>                    
                    <?php    
                    }
                    ?>
                </select>    
                
                <h3>4) NIVEL INSTRUCCION </h3>                                     
                <select id="4" name="4" data-native-menu="true" data-mini="true">
                    <option value="0" disabled="disabled" selected="selected"></option>
                    <?php
                    $seleccion_respuestas = mysqli_query($con, "select * from tabla_nivel_instruccion");
                    while ($registro_respuestas = mysqli_fetch_array($seleccion_respuestas)){?>
                        <option value="<?php echo $registro_respuestas[0]?>"><?php echo $registro_respuestas[1] ?></option>                    
                    <?php    
                    }
                    ?>
                </select>    
                
                <h3>5) OCUPACION PRINCIPAL </h3>                                    
                <select id="5" name="5" data-native-menu="true" data-mini="true"> 
                    <option value="0" disabled="disabled" selected="selected"></option>
                    <?php
                    $seleccion_respuestas = mysqli_query($con, "select * from tabla_ocupacion");
                    while ($registro_respuestas = mysqli_fetch_array($seleccion_respuestas)){?>
                        <option value="<?php echo $registro_respuestas[0]?>"><?php echo $registro_respuestas[1] ?></option>                    
                    <?php    
                    }
                    ?>
                </select>
            </div>   
        </div>
        <div data-role="footer" data-position="fixed" data-tap-toggle="false">           
            <div data-role="navbar">
                <ul>
                    <li><a href="#" onClick="salida()" data-iconpos="bottom" data-icon="power">Salir</a></li>
                    <li><a href="#pagina0" data-iconpos="bottom" data-icon="arrow-l">Atras</a></li>
                    <li><a href="#pagina2" data-iconpos="bottom" data-icon="arrow-r">Siguiente</a></li>
                    <li><input value="Limpia" type="button" onclick="limpiar();" data-iconpos="top" data-icon="refresh"></li>
                </ul>
            </div>            
        </div>
    </div>
    
    <div data-role="page" id="pagina2" data-theme="a">
        <div data-role="header" data-position="fixed" style="overflow: hidden" data-tap-toggle="false"> 
            <h6>&COPY; SGE-V 1.1</h6>
            <div data-role="navbar"> 
                <ul>
                    <?php                     
                    foreach ($paginador as $pagina) {?>                        
                        <li><a href="#div<?php echo $pagina['pagina']-2 ?>" data-ajax="false" data-iconpos="bottom" data-icon="star"><?php echo $pagina['nombre'] ?></a></li>                        
                    <?php                        
                    }
                    ?>                                       
                </ul>
              
            </div> 
        </div>
        <div data-role="main">
            <div data-role="content" class="ui-content" style="font-size: small;margin:20px; position:relative">
            <?php                

            $seleccion_preguntas = mysqli_query($con,"select * from preguntas where nro > 5 order by nro asc");
            
            while($registro_preguntas = mysqli_fetch_array($seleccion_preguntas)){

                $id_pregunta = $registro_preguntas[0];
                $nro_pregunta= $registro_preguntas[4];

                $seleccion_respuestas = mysqli_query($con,"select * from respuestas where id_pregunta = $id_pregunta");
                $tipo_respuesta = $registro_preguntas[2];
                $cant_respuestas= mysqli_num_rows($seleccion_respuestas);?>

                
                <h3><?php echo $nro_pregunta .') '. $registro_preguntas[3]; ?></h3>
                <?php
                $id = $nro_pregunta; ?>
                
                <div id="div<?php echo $id ?>">
                <?php
                switch ($tipo_respuesta){
                ///// RESPUESTAS ABIERTAS  
                case 1:
                ?>
                <input id="<?php echo $id ?>" name="<?php echo $id ?>" type="text" value="-" data-clear-btn="true" />
                <?php
                break;
                ///// RESPUESTAS OPCIONES MULTIPLES	
                case 2:                                                       
                if($cant_respuestas > 4){
                ?>    
                <select id="<?php echo $id ?>" name="<?php echo $id ?>" data-native-menu="true" data-mini="true">
                    <option value="0">&nbsp;</option>
                    <?php
                    while ($registro_respuestas = mysqli_fetch_array($seleccion_respuestas)){
                        echo "<option value=\"".$registro_respuestas[0]."\">".$registro_respuestas[2]."</option>\n";
                    }
                    ?>
                </select> 
                <?php  
                }else{
                   ?>
                    <fieldset data-role="controlgroup" data-type="vertical">
                    <?php
                    $cont = 1;
                    while ($registro_respuestas = mysqli_fetch_array($seleccion_respuestas)){
                    ?>
                       <input type="radio" id="r<?php echo $id ?><?php echo $registro_respuestas[0]?>" name="opcion<?php echo $id ?>" value="<?php echo $registro_respuestas[0]?>" onclick="asignar(<?php echo $id ?>,<?php echo $registro_respuestas[0]?>)"/>
                       <label for="r<?php echo $id ?><?php echo $registro_respuestas[0]?>" style="font-size: small"><?php echo $registro_respuestas[2]?></label>
                    <?php  
                    $cont ++;
                    }
                    ?>                 
                    </fieldset>  
                    <input type="hidden" id="<?php echo $id ?>" value="0">
                <?php    
                }
                break;
                ///// RESPUESTAS MB/B/RB/RM/M/MM/NsNc	
                case 3:
                $seleccion_respuestas = mysqli_query($con,"select * from tabla_opinion");
                ?>
                <select id="<?php echo $id ?>" name="<?php echo $id ?>" data-native-menu="true" data-mini="true">
                    <option value="0">&nbsp;</option>
                    <?php
                    while ($registro_respuestas = mysqli_fetch_array($seleccion_respuestas)){
                        echo "<option value=\"".$registro_respuestas[0]."\">".$registro_respuestas[1]."</option>\n";
                    }
                    ?>
                </select>
                <?php
                break;
                ///// RESPUESTAS TABLA CRUZADA	
                case 4:

                $seleccion_titulo = mysqli_query($con,"select enunciado from respuestas where id_pregunta = $id_pregunta and fila = 3");    
                $registro_respuestas = mysqli_fetch_array($seleccion_titulo);
                $titulo_fila    = $registro_respuestas[0];

                $seleccion_titulo = mysqli_query($con,"select enunciado from respuestas where id_pregunta = $id_pregunta and fila = 4");    
                $registro_respuestas = mysqli_fetch_array($seleccion_titulo);
                $titulo_columna    = $registro_respuestas[0];

                ?>
                <div class="ui-grid-a">
                    <div class="ui-block-a">                        
                        <select id="c<?php echo $id ?>" name="c<?php echo $id ?>" size="1" data-native-menu="true">
                            <option value="0" disabled="disabled" selected="selected"><?php echo $titulo_fila ?></option>
                            <?php 
                            $seleccion_titulo = mysqli_query($con,"select id_respuesta, enunciado from respuestas where id_pregunta = $id_pregunta and fila = 1");    
                            while($registro_respuestas = mysqli_fetch_array($seleccion_titulo)){
                            ?>
                            <option value="<?php echo $registro_respuestas[0] ?>"><?php echo $registro_respuestas[1] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="ui-block-b">                        
                        <select id="f<?php echo $id ?>" name="f<?php echo $id ?>" data-native-menu="true">
                            <option value="0" disabled="disabled" selected="selected"><?php echo $titulo_columna ?></option>
                            <?php 
                            $seleccion_titulo = mysqli_query($con,"select id_respuesta, enunciado from respuestas where id_pregunta = $id_pregunta and fila = 2");    
                            while($registro_respuestas = mysqli_fetch_array($seleccion_titulo)){
                            ?>
                            <option value="<?php echo $registro_respuestas[0] ?>"><?php echo $registro_respuestas[1] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="ui-grid-a">
                    <div class="ui-block-a" style=" text-align: center">
                        <a href="#" onclick="vaciar_lista(<?php echo $id ?>)" data-role="none"  data-inline="true"  data-iconpos="bottom" data-icon="delete" >
                            <h5>LIMPIAR</h5>
                        </a>
                    </div>
                    <div class="ui-block-b" style=" text-align: center">
                        <a href="#" onclick="agrega_cruzado(<?php echo $id ?>)" data-role="none" data-inline="true" data-iconpos="bottom" data-icon="check" style=" background-color: greenyellow">
                            <h5>GUARDAR</h5>
                        </a>
                    </div>
                </div> 
                <br>
                <div class="ui-grid-a">                       
                    <input type="hidden" id="<?php echo $id ?>" value="0" class="cruzado">
                    <h5>Su selección :</h5>
                    <textarea id="textarea<?php echo $id ?>" name="textarea<?php echo $id ?>">0</textarea>
                </div>               

                <!-- RESPUESTAS MULTIPLES CHECK  -->
                <?php
                break;
                case 10:
                ?>
                <select id="select<?php echo $id ?>" data-native-menu="false" multiple="multiple" onchange="asignar_check(<?php echo $id ?>)" data-mini="true">
                <?php
                while ($registro_respuestas = mysqli_fetch_array($seleccion_respuestas)){ ?>
                    <option value="<?php echo $registro_respuestas[0] ?>"><?php echo $registro_respuestas[2]?></option>
                <?php    
                }
                ?>
                </select>  
                <input type="hidden" id="<?php echo $id ?>" value="0" class="cruzado">
                <!-- FIN MULTIPLES CHECK -->   

                <?php
                }
                ?>
            </div>
                
            <?php    
            }     
            ?>                     
        </div>   
        </div>

        <div data-role="footer" data-position="fixed" style="overflow: hidden" data-tap-toggle="false">     
            <div data-role="navbar">
                <ul>
                    <li><a href="#" onClick="salida()" data-iconpos="bottom" data-icon="power">Salir</a></li>
                    <li><a href="#pagina1" data-iconpos="bottom" data-icon="arrow-l">Atrás</a></li>
                    <li><a href="#" data-iconpos="bottom" data-icon="check" onclick="no_vacio(<?php echo $_SESSION['cant_preg'] ?>)">Guardar</a></li>
                    <li><input value="Limpia" type="button" onclick="limpiar();" data-iconpos="top" data-icon="refresh"></li>
                </ul>
            </div>                        
        </div>        
    </div>
    
    </form> 
    
<script type="text/javascript">
    
    function limpiar(){        
        
        ymz.jq_confirm({title:"Restablecer", text:"Seguro que limpia ?", no_btn:"No", yes_btn:"Confirma", 
            no_fn:function(){}, 
            yes_fn:function(){
                $(".ui-input-text").val('').filter('.ui-slider-input').slider('refresh');
                $(".ui-checkbox input[type='checkbox'], .ui-radio input[type='radio']").prop("checked", false).checkboxradio("refresh"); 
                $('.ui-select select').val('').selectmenu('refresh');
                $("input:text").val('-');
                $('.cruzado').val(0);
            }
        });        
    }
    
    function acerca(){
        
        var texto = 'cachoalbornoz@gmail.com / 0343 154 586951';
        ymz.jq_alert({title:"Guillermo Albornoz", text:texto, ok_btn:"Ok", close_fn:null});
        
    }
    
    
    function Guardar_Android(texto) {        
        
        var sexo = document.getElementById(2).value ;
        var edad = document.getElementById(3).value ;
        
        if(sexo == 1){  
            // VARON
            if(edad == 1){
                document.getElementById('tv16').value = parseFloat(document.getElementById('tv16').value) + 1;
            }else{
                if(edad == 2){
                    document.getElementById('tv20').value = parseFloat(document.getElementById('tv20').value) + 1;
                }else{
                    if(edad == 3){
                        document.getElementById('tv30').value = parseFloat(document.getElementById('tv30').value) + 1;
                    }else{
                        if(edad == 4){
                            document.getElementById('tv40').value = parseFloat(document.getElementById('tv40').value) + 1;
                        }else{
                            if(edad == 5){
                                document.getElementById('tv50').value = parseFloat(document.getElementById('tv50').value) + 1;
                            }else{
                                document.getElementById('tv60').value = parseFloat(document.getElementById('tv60').value) + 1;
                            }
                        }
                    }
                }
            }
            document.getElementById('totalv').value = parseFloat(document.getElementById('totalv').value) + 1;
        }else{ 
            // MUJER
            if(edad == 1){
                document.getElementById('tm16').value = parseFloat(document.getElementById('tm16').value) + 1;
            }else{
                if(edad == 2){
                    document.getElementById('tm20').value = parseFloat(document.getElementById('tm20').value) + 1;
                }else{
                    if(edad == 3){
                        document.getElementById('tm30').value = parseFloat(document.getElementById('tm30').value) + 1;
                    }else{
                        if(edad == 4){
                            document.getElementById('tm40').value = parseFloat(document.getElementById('tm40').value) + 1;
                        }else{
                            if(edad == 5){
                                document.getElementById('tm50').value = parseFloat(document.getElementById('tm50').value) + 1;
                            }else{
                                document.getElementById('tm60').value = parseFloat(document.getElementById('tm60').value) + 1;
                            }
                        }
                    }
                }
            }
            document.getElementById('totalm').value = parseFloat(document.getElementById('totalm').value) + 1;
        }
        
        // Limpiar valores
       
        $(".ui-input-text").val('').filter('.ui-slider-input').slider('refresh');
        $(".ui-checkbox input[type='checkbox'], .ui-radio input[type='radio']").prop("checked", false).checkboxradio("refresh"); 
        $('.ui-select select').val('').selectmenu('refresh');
        $("input:text").val('-');
        $('.cruzado').val(0);
        
        $.mobile.changePage($("#pagina0"));
        Android.guardar(texto); 
        
        
    }
    
    function agrega_cruzado(i){

        if($("#c"+i).val()> 0 && $("#f"+i).val()> 0){
            
            var valores = ',' + $("#c"+i).val() + '-' + $("#f"+i).val();
            var textos  = $("#c"+ i +" option:selected").text()+'-'+$("#f"+ i +" option:selected").text();
             
            var valor = document.getElementById(i).value;
            if(valor == 0){ 
                document.getElementById(i).value = valores;
                document.getElementById('textarea'+ i).value = textos + ', ';
            }else{
                document.getElementById(i).value = document.getElementById(i).value + valores;
                document.getElementById('textarea'+ i).value = document.getElementById('textarea'+ i).value + textos + ', ';
            }
            
            $("#"+i).refresh;
        }  
    }
    
    function vaciar_lista(i){
        document.getElementById(i).value = 0;
        document.getElementById('textarea'+ i).value = 0;
    }
    
    
    function salida() {
        Android.salir();
    }
    
    function asignar(id,valor){
        document.getElementById(id).value = valor;
    }
    function asignar_check(id){
        document.getElementById(id).value = $('select[id=select'+id+']').val();
        str = document.getElementById(id).value;
        
        if(str.length == 0){ // comprueba si deseleccionó todo
            document.getElementById(id).value = 0;
        }        
    }
    function obtenerDatos(cant_preg) {
        var cadena = ''; 
        var id_encuesta = document.getElementById('id_encuesta').value;
        var nro_usuario = document.getElementById('usuario').value;

        for (i=1; i <= cant_preg ; i ++){
            var nro = document.getElementById(i);
            if(typeof nro !== 'undefined' && nro !== null) {

                var nro = document.getElementById(i).value ;

            }else{
                var sel_f 	= document.getElementById('F_' + i); // Buscar valores en filas
                var sel_c 	= document.getElementById('C_' + i); // Buscar valores en columnas

                texto = '';

                for(x=0; x < sel_f.children.length; x++){
                    var child_f = sel_f.children[x];
                    var child_c = sel_c.children[x];

                    if(child_f.value > 0){
                        var texto = texto + child_f.value + '_' + child_c.value + '.' ;
                    }
                }
                var texto = texto.substring(0, texto.length-1);
                var nro = texto;
            }		
            cadena = cadena + nro + ';' ;
        }	
        return id_encuesta + ';' + nro_usuario + ';' + cadena ;
    } 

    function no_vacio(cant_preg){
        
        var error = 0;
        for (i=1; i <= cant_preg ; i ++){
            var respuesta = document.getElementById(i);
            if(typeof respuesta !== 'undefined' && respuesta !== null) {
                if(document.getElementById(i).value == 0){
                    if(i < 5){
                        $.mobile.changePage($("#pagina1"));
                    }else{
                        var texto = "Complete Respuesta " + i;
                        ymz.jq_alert({title:"Incompleto", text:texto, ok_btn:"Ok", close_fn:null});
                                                   
                        $.mobile.changePage($("#pagina2"));               }
                    
                        error = 1;
                        break;
                }
            }
        }
	
        if(error == 0){
            var datos = obtenerDatos(cant_preg);
            Guardar_Android(datos);
        }
    }  
    
</script>



</body>
</html>
<?php 
mysqli_close($con);
}else{
    
    echo '<div data-role="page" id="aviso">
            <div data-role="header" style="text-align: center">
                <h6>&COPY; SGE-V 1.1</h6>
            </div>
            <div data-role="content" class="ui-content" style="text-align:center"> 
            
                <p style=" font-weight: bold">Aviso: No hay preguntas cargadas aún. </p>
                  
            </div>
        </div>';
    
}