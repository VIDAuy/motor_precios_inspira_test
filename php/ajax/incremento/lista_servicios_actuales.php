<?php
include_once '../../configuraciones.php';


$cedula = $_REQUEST['cedula'];
if ($cedula == "") devolver_error(ERROR_GENERAL);


$servicios_actuales_piscina = obtener_servicios_actuales(1, $cedula);
if ($servicios_actuales_piscina == false) devolver_error("Ocurrieron errores al obtener los servicios actuales de piscina");
$cantidad_piscina = mysqli_num_rows($servicios_actuales_piscina);

$servicios_actuales_padron = obtener_servicios_actuales(2, $cedula);
if ($servicios_actuales_padron == false) devolver_error("Ocurrieron errores al obtener los servicios actuales de padrón");
$cantidad_padron = mysqli_num_rows($servicios_actuales_padron);


if ($cantidad_piscina <= 0 && $cantidad_padron <= 0) devolver_error("No se pudieron encontrar los productos");
$servicios_actuales = $cantidad_piscina > 0 ? $servicios_actuales_piscina : $servicios_actuales_padron;


$count = 1;
$total_importe = 0;
$acotado = 0;
while ($row = mysqli_fetch_assoc($servicios_actuales)) {
    $numero_servicio = $row['servicio'];
    $nombre_servicio = obtener_datos_servicio($numero_servicio);
    $horas = $row['horas'];
    $importe = $row['importe'];
    $promo_estaciones = 0;
    if ($numero_servicio == "01" && in_array($importe, ["530", "1060", "1590"])) $promo_estaciones++;
    $promo_estaciones = $promo_estaciones > 0 ? "- <span class='text-success'>Sanatorio Estaciones</span>" : "";
    $cod_promo = $row['cod_promo'];
    $nombre_promo = in_array($cod_promo, ["", null]) ? obtener_nombre_promo($cod_promo) : "";
    $cod_promo = $cod_promo != "" && $nombre_promo != "" ? "- 🚀 $nombre_promo" : "";

    $lista_servicios[] = "<li class='list-group-item list-group-item-secondary'><strong>" . $count++ . ".</strong> {$nombre_servicio} - ⏰ {$horas}hrs {$promo_estaciones} {$cod_promo}</li>";
    $total_importe = $total_importe + $importe;

    if (in_array($numero_servicio, ["63", "65"])) $acotado++;
    $array_numeros_servicios[] = $numero_servicio;
}



$response['error'] = false;
$response['lista_servicios'] = $lista_servicios;
$response['importe_total'] = $total_importe;
$response['acotado'] = $acotado > 0 ? 1 : 0;
$response['numeros_servicios'] = $array_numeros_servicios;
echo json_encode($response);




function obtener_servicios_actuales($opcion, $cedula)
{
    $conexion = $opcion == 1 ? connection(DB_CALL) : connection(DB_ABMMOD);
    $tabla = TABLA_PADRON_PRODUCTO_SOCIO;

    //Con el "NOT IN" filtro los servicios son "duplicados" porque se ofrecen en paquetes EJ. 06 y 08 es Reintegro Opcional.
    $filtro_paquete = "AND servicio NOT IN (08, 110)";

    try {
        $sql = "SELECT servicio, SUM(hora) AS 'horas', SUM(importe) AS 'importe', cod_promo FROM {$tabla} WHERE cedula = '$cedula' $filtro_paquete GROUP BY servicio";
        $consulta = mysqli_query($conexion, $sql);
    } catch (\Throwable $error) {
        registrar_errores($sql, "lista_servicios_actuales.php", $error);
        $consulta = false;
    }

    return $consulta;
}


function obtener_datos_servicio($numero_servicio)
{
    $conexion = connection(DB);
    $tabla1 = TABLA_SERVICIOS;
    $tabla2 = TABLA_NUMEROS_SERVICIOS;

    try {
        $sql = "SELECT 
                 s.nombre_servicio 
                FROM 
                 {$tabla1} s 
                 INNER JOIN {$tabla2} ns ON s.id = ns.id_servicio 
                WHERE 
                 ns.numero_servicio = '$numero_servicio' 
                LIMIT 1;";
        $consulta = mysqli_query($conexion, $sql);
    } catch (\Throwable $error) {
        registrar_errores($sql, "lista_servicios_actuales.php", $error);
        $consulta = false;
    }

    $resultados = $consulta != false ? mysqli_fetch_assoc($consulta)['nombre_servicio'] : false;

    return $resultados;
}


function obtener_nombre_promo($cod_promo)
{
    $conexion = connection(DB);
    $tabla = TABLA_PROMOCIONES;

    try {
        $sql = "SELECT * FROM {$tabla} WHERE numero_promo = '$cod_promo'";
        $consulta = mysqli_query($conexion, $sql);
    } catch (\Throwable $error) {
        registrar_errores($sql, "lista_servicios_actuales.php", $error);
        $consulta = false;
    }

    $resultados = $consulta != false ? mysqli_fetch_assoc($consulta)['nombre_promocion'] : false;

    return $resultados;
}
