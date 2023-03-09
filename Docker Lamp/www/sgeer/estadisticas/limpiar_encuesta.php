<?php
session_start();

require('../accesorios/accesos_bd.php');

$con=conectar();

mysqli_query($con, "TRUNCATE TABLE maestro_encuestas");

mysqli_query($con, "TRUNCATE TABLE maestro_posiciones");
   
mysqli_query($con, "TRUNCATE TABLE maestro_respuestas_abiertas");

mysqli_query($con, "TRUNCATE TABLE maestro_respuestas_cerradas");

mysqli_query($con, "TRUNCATE TABLE archivos_exportados");

mysqli_query($con, "TRUNCATE TABLE paginadores");

array_map('unlink', glob("../offline/files/*.*"));

array_map('unlink', glob("../mapas/marcadores/*.*"));

header('location:principal_estadistica.php');