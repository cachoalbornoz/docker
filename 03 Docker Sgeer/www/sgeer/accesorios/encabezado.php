<!DOCTYPE html>
<html>
<head>    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    
    <link rel="shortcut icon" href="../../favicon.ico" type="image/x-icon"/>
    <title>Sistema Gestor de Encuestas</title>
            
    <link href="../public/css/bootstrap-3.3.7-dist/css/bootstrap.css" type="text/css" rel="stylesheet"> 
    <link href="../public/css/bootstrap-3.3.7-dist/css/bootstrap-theme.css" rel="stylesheet" type="text/css"/>
    <link href="../public/css/normalize.css" rel="stylesheet" type="text/css"/>
    <link href="../public/css/MisEstilos.css" rel="stylesheet" type="text/css"/>
    
    <link href="../public/css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
     
    <script src="../public/js/jquery.js" type="text/javascript"></script>
    <script src="../public/css/bootstrap-3.3.7-dist/js/bootstrap.js" type="text/javascript"></script>
    
    <script src="../public/js/ActiveMenu.js" type="text/javascript"></script>
    
    
</head>

<body>
     
    <div class="container"> 
        
        <header>  
            <nav class="navbar navbar-default navbar-fixed-top" role="navigation">            
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navegacion" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">
                            <img src="../public/imagenes/sge.png" alt="logo" class="img-responsive">
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="navegacion">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    SGE - Menú <span class="caret"></span>
                                </a>
                                
                                <ul class="dropdown-menu">
                                    <li><a href="../estadisticas/principal_estadistica.php"><span class="glyphicon glyphicon-home"></span> Inicio</a></li>                                      
                                    <li role="separator" class="divider"></li>
                                    
                                    <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-users" aria-hidden="true"></i> Administrar Encuestas</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="../estadisticas/padron_encuesta.php"><i class="fa fa-cogs" aria-hidden="true"></i> Padron Encuestas</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li><a href="../estadisticas/padron_marcadores.php"><i class="fa fa-bars" aria-hidden="true"></i> Marcadores</a></li>
                                        </ul>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="../offline/subir_estadisticas.php"><i class="fa fa-file-text" aria-hidden="true"></i> Subir Archivos </a></li>
                                    <li role="separator" class="divider"></li>
                                    <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i style="color: green" class="fa fa-android" aria-hidden="true"></i> Pruebas Android</a>
                                        <ul class="dropdown-menu">   
                                            <li><a href="../movil/movil.php"><i class="fa fa-twitter" aria-hidden="true"></i> BootStrap</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li class="disabled"><a href="../offline/detalle_inicio_android.php"><i class="fa fa-empire" aria-hidden="true"></i> JQMobile</a></li>
                                        </ul>
                                    </li> 
                                    <?php if($_SESSION['estado']== 'a'){ ?>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#" onclick="limpiar()"><span class="glyphicon glyphicon-trash"></span> Limpiar Encuesta</a></li>   
                                    <li role="separator" class="divider"></li>
                                    <li><a href="../backup/exportar_encuesta.php"><span class="glyphicon glyphicon-level-up"></span> Exportar Encuestas</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#" onclick="borrar()"><span style="color: red" class="glyphicon glyphicon-alert"></span> Borrar Encuesta !</a></li> 
                                    <li role="separator" class="divider"></li>
                                    <li><a href="../tools/subir_json.php"><span class="glyphicon glyphicon-barcode"></span> Leer Json Files</a></li> 
                                    <?php } ?>                                       
                                </ul>                 
                          </li>
                        </ul>

                        <ul class="nav navbar-nav navbar-right"> 
                            <li><a href="../registro/cambio_clave_nueva.php"><span class="glyphicon glyphicon-cog"></span> Cambio Clave</a></li>
                            
                            <li><a href="#" onClick='window.location="../accesorios/salir.php"' title="Salir"><span class="glyphicon glyphicon-log-out"></span> (<?php echo $_SESSION['usuario'] ?>)</a> </li>
                        </ul>
                        
                    </div>  
                </div>            
            </nav>
        </header>       
       
