<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test WebDev</title>
</head>

<body>
    <h1>
        <?php print 'Bienvenido Docker '; ?>
    </h1>

    <p>
    <?php

        $host     = 'db';  //the name of the mysql service inside the docker file.
        $user     = 'devuser';
        $password = 'devpass';
        $db       = 'test_db';

        $conexion = mysqli_connect($host, $user, $password) or die('Servidor no responde a la conexión, disculpe las molestias. Server '.$host);

        if ($conexion->connect_error) {
            print 'connection failed' . $conn->connect_error;
        } else {
            print 'successfully connected to MYSQL';
        }

    ?>
    </p>
    
</body>

</html>