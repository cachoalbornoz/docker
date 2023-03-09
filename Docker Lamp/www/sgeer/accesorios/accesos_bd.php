<?php
//////////////////////////////////////////////////////////////////////////////////
function conectar()
{
    $host = $_SERVER['HTTP_HOST'];
    $pos  = strpos($host, 'esdata');
    if ($pos === false) {
        $servidor = '127.0.0.1';
        $usuario  = 'galbornoz';
        $password = 'albz8649';
        $database = 'db_sgeer';
    } else {
        $servidor = 'sql306.eshost.com.ar';
        $usuario  = 'eshos_19062218';
        $password = 'rdhzw4kx';
        $database = 'eshos_19062218_sgeer';
    }

    $conexion = mysqli_connect($servidor, $usuario, $password) or die('Servidor no responde a la conexión, disculpe las molestias. Server '.$servidor);
    mysqli_select_db($conexion, $database)                     or die('Verificar conexion con la Base Datos Jovenes');
    mysqli_query($conexion, "SET NAMES 'utf8'");
    return $conexion;
}

////////////////////////////////////////////////////////////////////////////////
function num2letras($num, $fem = false, $dec = true)
{
    $matuni[2]    = 'dos';
    $matuni[3]    = 'tres';
    $matuni[4]    = 'cuatro';
    $matuni[5]    = 'cinco';
    $matuni[6]    = 'seis';
    $matuni[7]    = 'siete';
    $matuni[8]    = 'ocho';
    $matuni[9]    = 'nueve';
    $matuni[10]   = 'diez';
    $matuni[11]   = 'once';
    $matuni[12]   = 'doce';
    $matuni[13]   = 'trece';
    $matuni[14]   = 'catorce';
    $matuni[15]   = 'quince';
    $matuni[16]   = 'dieciseis';
    $matuni[17]   = 'diecisiete';
    $matuni[18]   = 'dieciocho';
    $matuni[19]   = 'diecinueve';
    $matuni[20]   = 'veinte';
    $matunisub[2] = 'dos';
    $matunisub[3] = 'tres';
    $matunisub[4] = 'cuatro';
    $matunisub[5] = 'quin';
    $matunisub[6] = 'seis';
    $matunisub[7] = 'sete';
    $matunisub[8] = 'ocho';
    $matunisub[9] = 'nove';

    $matdec[2]  = 'veint';
    $matdec[3]  = 'treinta';
    $matdec[4]  = 'cuarenta';
    $matdec[5]  = 'cincuenta';
    $matdec[6]  = 'sesenta';
    $matdec[7]  = 'setenta';
    $matdec[8]  = 'ochenta';
    $matdec[9]  = 'noventa';
    $matsub[3]  = 'mill';
    $matsub[5]  = 'bill';
    $matsub[7]  = 'mill';
    $matsub[9]  = 'trill';
    $matsub[11] = 'mill';
    $matsub[13] = 'bill';
    $matsub[15] = 'mill';
    $matmil[4]  = 'millones';
    $matmil[6]  = 'billones';
    $matmil[7]  = 'de billones';
    $matmil[8]  = 'millones de billones';
    $matmil[10] = 'trillones';
    $matmil[11] = 'de trillones';
    $matmil[12] = 'millones de trillones';
    $matmil[13] = 'de trillones';
    $matmil[14] = 'billones de trillones';
    $matmil[15] = 'de billones de trillones';
    $matmil[16] = 'millones de billones de trillones';

    //Zi hack
    $float = explode('.', $num);
    $num   = $float[0];

    $num = trim((string)@$num);
    if ($num[0] == '-') {
        $neg = 'menos ';
        $num = substr($num, 1);
    } else {
        $neg = '';
    }
    while ($num[0] == '0') {
        $num = substr($num, 1);
    }
    if ($num[0] < '1' or $num[0] > 9) {
        $num = '0' . $num;
    }
    $zeros = true;
    $punt  = false;
    $ent   = '';
    $fra   = '';
    for ($c = 0; $c < strlen($num); $c++) {
        $n = $num[$c];
        if (!(strpos(".,'''", $n) === false)) {
            if ($punt) {
                break;
            }

            $punt = true;
            continue;
        } elseif (!(strpos('0123456789', $n) === false)) {
            if ($punt) {
                if ($n != '0') {
                    $zeros = false;
                }
                $fra .= $n;
            } else {
                $ent .= $n;
            }
        } else {
            break;
        }
    }
    $ent = '     ' . $ent;
    if ($dec and $fra and !$zeros) {
        $fin = ' coma';
        for ($n = 0; $n < strlen($fra); $n++) {
            if (($s = $fra[$n]) == '0') {
                $fin .= ' cero';
            } elseif ($s == '1') {
                $fin .= $fem ? ' una' : ' un';
            } else {
                $fin .= ' ' . $matuni[$s];
            }
        }
    } else {
        $fin = '';
    }
    if ((int)$ent === 0) {
        return 'Cero ' . $fin;
    }
    $tex    = '';
    $sub    = 0;
    $mils   = 0;
    $neutro = false;
    while (($num = substr($ent, -3)) != '   ') {
        $ent = substr($ent, 0, -3);
        if (++$sub < 3 and $fem) {
            $matuni[1] = 'una';
            $subcent   = 'as';
        } else {
            $matuni[1] = $neutro ? 'un' : 'un';
            $subcent   = 'os';
        }
        $t  = '';
        $n2 = substr($num, 1);
        if ($n2 == '00') {
        } elseif ($n2 < 21) {
            $t = ' ' . $matuni[(int)$n2];
        } elseif ($n2 < 30) {
            $n3 = $num[2];
            if ($n3 != 0) {
                $t = 'i' . $matuni[$n3];
            }
            $n2 = $num[1];
            $t  = ' ' . $matdec[$n2] . $t;
        } else {
            $n3 = $num[2];
            if ($n3 != 0) {
                $t = ' y ' . $matuni[$n3];
            }
            $n2 = $num[1];
            $t  = ' ' . $matdec[$n2] . $t;
        }
        $n = $num[0];
        if ($n == 1) {
            $t = ' ciento' . $t;
        } elseif ($n == 5) {
            $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
        } elseif ($n != 0) {
            $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
        }
        if ($sub == 1) {
        } elseif (!isset($matsub[$sub])) {
            if ($num == 1) {
                $t = ' mil';
            } elseif ($num > 1) {
                $t .= ' mil';
            }
        } elseif ($num == 1) {
            $t .= ' ' . $matsub[$sub] . '?n';
        } elseif ($num > 1) {
            $t .= ' ' . $matsub[$sub] . 'ones';
        }
        if ($num == '000') {
            $mils++;
        } elseif ($mils != 0) {
            if (isset($matmil[$sub])) {
                $t .= ' ' . $matmil[$sub];
            }
            $mils = 0;
        }
        $neutro = true;
        $tex    = $t . $tex;
    }
    $tex = $neg . substr($tex, 1) . $fin;
    //Zi hack --> return ucfirst($tex);

    if (isset($float[1]) and ($float[1] > 0)) {
        $decimales = ' con ' . $float[1] . ' ';
    } else {
        $decimales = '';
    }
    $end_num = ucfirst($tex) . ' ' . $decimales;
    return $end_num;
}
////////////////////////////////////////////////////////////////////////////////

function mes($numero)
{
    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $mes   = $meses[$numero - 1];
    return $mes;
}

function lcadena($s)
{
    $s = utf8_encode($s);
    return $s;
}
function sanear_string($string)
{
    $string = trim($string);

    $string = str_replace(
        ['à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'],
        ['a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'],
        $string
    );

    $string = str_replace(
        ['è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'],
        ['e', 'e', 'e', 'E', 'E', 'E', 'E'],
        $string
    );

    $string = str_replace(
        ['ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'],
        ['i', 'i', 'i', 'I', 'I', 'I', 'I'],
        $string
    );

    $string = str_replace(
        ['ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'],
        ['o', 'o', 'o', 'O', 'O', 'O', 'O'],
        $string
    );

    $string = str_replace(
        ['ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'],
        ['u', 'u', 'u', 'U', 'U', 'U', 'U'],
        $string
    );

    /*
       $string = str_replace(
           array('ñ', 'Ñ', 'ç', 'Ç'),
           array('n', 'N', 'c', 'C',),
           $string
       );
     */
    $string = str_replace(
        ['ç', 'Ç'],
        ['c', 'C'],
        $string
    );

    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        ['\\', '¨', 'º', '-', '~',
            '#', '@', '|', '!', '"',
            '·', '$', '%', '&', '/',
            '(', ')', '?', "'", '¡',
            '¿', '[', '^', '`', ']',
            '+', '}', '{', '¨', '´',
            '>', '< ', ';', ',', ':',
            '.', ],
        '',
        $string
    );
    $string = preg_replace('[\s+]', '', $string);

    return $string;
}
