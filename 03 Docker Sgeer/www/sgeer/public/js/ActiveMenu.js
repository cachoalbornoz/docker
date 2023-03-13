(function($){
    $(document).ready(function(){
        $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
            event.preventDefault(); 
            event.stopPropagation(); 
            $(this).parent().siblings().removeClass('open');
            $(this).parent().toggleClass('open');
        });
    });
})(jQuery);


function limpiar(){

    if(confirm('Seguro limpia todos los resultados ?')){
        window.location = '../estadisticas/limpiar_encuesta.php';
    }
}            

function borrar(){

    if(confirm('BORRA LA ENCUESTA COMPLETA !?')){
        window.location = '../estadisticas/borrar_encuesta.php';
    }
}
