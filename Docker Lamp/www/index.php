<!DOCTYPE html>
<html lang="es">

<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <title>Sistema Gestor de Encuestas</title>


    <link href="sgeer/public/css/bootstrap-3.3.7-dist/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="sgeer/public/css/bootstrap-3.3.7-dist/css/SigIn.css" rel="stylesheet" type="text/css" />

    <link href="sgeer/public/css/MisEstilos.css" rel="stylesheet" type="text/css" />

    <link href="sgeer/public/css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />

    <link href="sgeer/public/js/owl.carousel/owl-carousel/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="sgeer/public/js/owl.carousel/owl-carousel/owl.theme.css" rel="stylesheet" type="text/css" />

</head>

<body>

    <div class="container">

        <div class="row" style=" background-color: grey; border-radius: 5px; margin-bottom:80px ">
            <div class="col-xs-12 text-center">
                <h4 style="color: whitesmoke; font-weight: bold">Sistema Gestor Encuestas <?php print date('Y', time()); ?> </h4>
            </div>
        </div>

        <form class="form-signin" method="post" action="sgeer/registro/verifica_usuario.php">

            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input name="usuario" type="text" id="usuario" required autofocus placeholder="Usuario" class="form-control" />
            </div>

            <br>

            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input name="password" type="password" id="password" required placeholder="Clave" class="form-control" />
            </div>

            <br>

            <button class="btn btn-lg btn-primary btn-block" type="submit">Ingresar</button>

            <br />


            <?php
            if ((isset($_GET['autenticado']) and $_GET['autenticado'] == 'false')) { ?>

            <div class="row" style=" background-color: grey; border-radius: 5px ">
                <div class="col-xs-12" style=" text-align: center">
                    <h5 style="color: whitesmoke; font-weight: bold">
                    Datos incorrectos
                    </h5>
                </>
            </div>
            <?php
            }
            ?>
        </form>

        <footer class="navbar-default navbar-fixed-bottom navbar-inverse">
            <div class="owl-carousel owl-theme" style="text-align: center;font-weight: bold; color: blanchedalmond">
                <div class="item">
                    <h6>&reg; S.G.E. 2015-<?php print date('Y', time()); ?>
                    </h6>
                </div>
                <div class="item">
                    <h6><span class="glyphicon glyphicon-globe"></span> Encuestas Geo Referenciadas</h6>
                </div>
                <div class="item">
                    <h6><i class="fa fa-area-chart" aria-hidden="true"></i> Resultados Gráficos</h6>
                </div>
                <div class="item">
                    <h6><i class="fa fa-clock-o" aria-hidden="true"></i> Procesamiento Ágil</h6>
                </div>
                <div class="item">
                    <h6><i class="fa fa-rocket" aria-hidden="true"></i> Apps Livianas </h6>
                </div>
            </div>
        </footer>
    </div>

    <script src="sgeer/public/js/jquery-3.2.1.min.js" type="text/javascript"></script>
    <script src="sgeer/public/css/bootstrap-3.3.7-dist/js/bootstrap.min.js" type="text/javascript"></script>

    <script src="sgeer/public/js/owl.carousel/owl-carousel/owl.carousel.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $('.owl-carousel').owlCarousel({
                autoPlay: 7000,
                navigation: false,
                slideSpeed: 500,
                paginationSpeed: 400,
                singleItem: true,
                loop: true,
                nav: true
            });


        });
    </script>


</body>

</html>