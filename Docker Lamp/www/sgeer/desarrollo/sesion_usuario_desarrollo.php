<?php
session_start();

require('../accesorios/accesos_bd.php');

$con=conectar();
			
?>
<!DOCTYPE html>
<html>
<head>
    <title>SgeerMovil</title>
    <meta charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="../offline/mi_estilo.css" rel="stylesheet" type="text/css"/>
    <link href="../offline/jquery.mobile.icons.min.css" rel="stylesheet" type="text/css"/>

    <script src="../offline/jquery.js" type="text/javascript"></script>
    <script type="text/javascript">

        $(document).bind('mobileinit',function(){
            $.mobile.changePage.defaults.changeHash = false;
            $.mobile.hashListeningEnabled = false;
            $.mobile.pushStateEnabled = false;
        });
        
    </script>

    <link href="../offline/jquery.mobile-1.4.5.css" rel="stylesheet" type="text/css"/>
    <script src="../offline/jquery.mobile-1.4.5.js" type="text/javascript"></script>

    <style>
        .ui-page { -webkit-backface-visibility: hidden; }
    </style>
</head>


<body>
    
  <form id="encuesta" onreset="beforeReset()" data-exclude-selector="">   
    
        
    <div data-role="page" id="pagina1"> 
        
        <div data-role="header" style="overflow: hidden">            
            <h6>&COPY; SGE-V 1.1 </h6>
            <div data-role="navbar">
                <ul>
                    <li><a href="#pagina0">Inicio</a></li>
                    <?php
                    $tabla_paginadores          = mysqli_query($con, "select * from paginadores");
                    while($registro_paginadores = mysqli_fetch_array($tabla_paginadores)){  
                    ?>
                        <li><a href="#pagina<?php echo $registro_paginadores[2] ?>"><?php echo $registro_paginadores[1] ?></a></li>
                    <?php
                    }
                    ?>
                </ul>              
            </div>            
        </div>
        
        <div data-role="main">
            <div data-role="content" class="ui-content">
                
                
                <div class="ui-grid-c">
                    <div class="ui-block-a">                        
                        <select id="c" name="c" size="1" data-native-menu="false">
                            <option value="0" disabled="disabled" selected="selected">Medio comunicacion</option>
                            <option value="1">Radio</option>
                            <option value="2">Television</option>
                            <option value="3">Diarios</option>
                            <option value="4">Internet</option>
                        </select>
                    </div>
                    <div class="ui-block-b">                        
                        <select id="f" name="f" data-native-menu="false">
                            <option value="0" disabled="disabled" selected="selected">Horarios del Dia</option>
                            <option value="1">Mañana</option>
                            <option value="2">Tarde</option>
                            <option value="3">Noche</option>                    
                        </select>
                    </div>
                    <div class="ui-block-c" style=" text-align: center">
                        <a href="#" onclick="agrega_cruzado()" data-role="button" data-mini="true" data-inline="true" data-iconpos="bottom" data-icon="check" style=" background-color: greenyellow">Guardar</a>
                    </div>
                    <div class="ui-block-d" style=" text-align: center">
                        <a href="#" onclick="vaciar_lista()" data-role="button" data-mini="true" data-inline="true"  data-iconpos="bottom" data-icon="delete" style=" background-color: rosybrown">Limpiar</a>
                    </div>
                </div> 
                <br>
                <div class="ui-grid-c">
                    <div class="ui-block-a">
                        <h5>Elementos seleccionados</h5>
                        <ul data-role="listview" id="lista">
                                                        
                        </ul>                 
                    </div>
                    <div class="ui-block-b">                        
                        <input type="text" value="0" id="valori">
                    </div>
                    <div class="ui-block-c">
                       
                    </div>
                    <div class="ui-block-d">
                       
                    </div>
                </div>
                
                
            </div> 
            
        </div>
        
        <div data-role="footer" data-position="fixed" style="overflow: hidden">     
            <div data-role="navbar">
                <ul>
                    <li><input value="Limpia" type="reset" form="encuesta" data-exclude-selector=".exclude" onclick="initiateReset()" data-iconpos="top" data-icon="refresh"></li>
                    <li><a href="#pagina1" data-iconpos="bottom" data-icon="check">Finaliza</a></li>
                </ul>
            </div>                        
        </div>        
    </div>     

    

</form>
    
<script type="text/javascript">
    
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
        
        $.mobile.changePage($("#pagina0"));
        Android.guardar(texto);       
    }
    
    function agrega_cruzado(i){

        if($("#c"+i).val()> 0 && $("#f"+i).val()> 0){
            
            var valores = ',' + $("#c"+i).val() + '-' + $("#f"+i).val();
            var textos  = $("#c"+ i +"option:selected").text()+'-'+$("#f"+ i +"option:selected").text();
        
            $('#lista'+i).append('<li>' + textos + '</li>');
            $('#lista'+i).listview('refresh');
            
            var valor = document.getElementById(i).value;
            if(valor == 0){ 
                document.getElementById(i).value = valores;
            }else{
                document.getElementById(i).value = document.getElementById(i).value + valores;
            }
        }  
    }
    
    function vaciar_lista(i){
        $('#lista'+ i +'li').remove()   ;  
        document.getElementById(i).value = 0;
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
        
        if(str.length == 0){
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
                    $.mobile.changePage($("#pagina" + i));
                    error = 1;
                    break;
                }
            }else{
                var sel_f 	= document.getElementById('F_' + i); // Buscar valores en filas
                var sel_c 	= document.getElementById('C_' + i); // Buscar valores en columnas

                if(sel_f.children.length == 0 & sel_c.children.length == 0){
                    
                    var html = "<p> <a href='#pagina"+ i +"'>Respuesta " + i + " sin responder</a> </p>";
                    
                    $("#estado").html(html);
                    $("#estado").popup("open");
                    error = 1;
                    break;	
                }			
            }
        }
        //	
        if(error == 0){
            var datos = obtenerDatos(cant_preg);
            Guardar_Android(datos);
        }
    }  
    
</script>

<script>

var dataKey = 'data-exclude-selector';

function initiateReset(e) {
    e = e || window.event;

    var button = e.target,
        form = button.form,
        excludeSelector = button.getAttribute(dataKey);

    form.setAttribute(dataKey, excludeSelector);
}

function beforeReset(e) {
    e = e || window.event;

    var form = e.target,
        excludeSelector = form.getAttribute(dataKey),
        elements = form.querySelectorAll(excludeSelector),
        parents = [],
        siblings = [],
        len = elements.length,
        i, e, p, s;

    for (i = 0; i < len; i++) {
        el = elements[i];
        parents.push(p = el.parentNode);
        siblings.push(s = el.nextSibling);
        p.removeChild(el);
    }

    setTimeout(function() {
        for (var j = 0; j < len; j++){
            if (siblings[j]){
                parents[j].insertBefore(elements[j], siblings[j]);
            }else{
                parents[j].appendChild(elements[j]);
            }
        }
    });
}

</script>  

</body>
</html>
<?php mysqli_close($con); ?>