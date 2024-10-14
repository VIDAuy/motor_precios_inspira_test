<?php

function connection($db = DB, $charset = true)
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    try {
        $conexion = mysqli_connect($db["host"], $db["user"],  $db["password"], $db["db"]);
        if ($charset) mysqli_set_charset($conexion, "utf8");
    } catch (mysqli_sql_exception $e) {
        echo $e->getMessage();
    }
    return $conexion;
}

function connection_nodum()
{
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $serverName        = '192.168.252.12\NODUM-SERVER,1433';
    $connectionInfo    = array(
        'Database'    => 'nodum',
        'UID'        => 'consultas',
        'PWD'        => 'consultas.2k19',
        'Characterset'    => (string)    'UTF-8'
    );
    $conexion = sqlsrv_connect($serverName, $connectionInfo);

    if (!$conexion) {
        echo "Conexión no se pudo establecer.<br />";
        die(print_r(sqlsrv_errors(), true));
    }

    return $conexion;
}

function connection_nodum_Modelo_RSE()
{
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $serverName        = '192.168.252.12\NODUMBD,1433';
    $connectionInfo    = array(
        'Database'    => 'Modelo_RSE',
        'UID'        => 'consultas',
        'PWD'        => 'consultas.2k19',
        'Characterset'    => (string)    'UTF-8'
    );
    $conexion = sqlsrv_connect($serverName, $connectionInfo);

    if (!$conexion) {
        echo "Conexión no se pudo establecer.<br />";
        die(print_r(sqlsrv_errors(), true));
    }

    return $conexion;
}


function escapeString($fields,  $conexion = false)
{
    $conexion  = $conexion == false ? connection() : $conexion;

    if (is_array($fields)) {
        foreach ($fields as $key => $field) {
            $fields[$key] = is_numeric($fields[$key]) ? $fields[$key] : mysqli_real_escape_string($conexion, trim($fields[$key]));
        }
    } else mysqli_real_escape_string($conexion, trim($fields));



    return $fields;
}

function WhileFetchAssoc($consulta)
{
    $resultado = [];
    if (mysqli_num_rows($consulta) > 0) {
        while ($r = mysqli_fetch_assoc($consulta)) {
            $resultado[] = $r;
        }
    }
    return $resultado;
}

function LogDB($nombre, $consulta, $e)
{
    Logger($nombre, " Archivo: {$e->getFile()}   Linea: {$e->getLine()} \n  Consulta: {$consulta} \n   Mensaje: {$e->getMessage()} \n  ", "error");
}
