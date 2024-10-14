<?php
include_once '../../configuraciones.php';

$array_datos_beneficiario = $_REQUEST['array_datos_beneficiario'];
$array_servicios_agregados = $_REQUEST['array_servicios_agregados'];
$observacion = $_REQUEST['observacion'];
$id_metodo_pago = $_REQUEST['id_metodo_pago'];
$metodo_pago = $_REQUEST['metodo_pago'];
$nombre_titular_onajpu = $_REQUEST['nombre_titular_onajpu'];
$cedula_titular_onajpu = $_REQUEST['cedula_titular_onajpu'];
$array_tarjeta_titular = isset($_REQUEST['array_tarjeta_titular']) ? $_REQUEST['array_tarjeta_titular'] : [];
$importe_total = $_REQUEST['importe_total'];
$convenio = $_REQUEST['convenio'];

if (
    count($array_datos_beneficiario) <= 0 ||
    count($array_servicios_agregados) <= 0 ||
    $observacion == "" ||
    $id_metodo_pago == "" ||
    ($id_metodo_pago == 1 && ($nombre_titular_onajpu == "" || $cedula_titular_onajpu == "")) ||
    (in_array($id_metodo_pago, ["4", "5", "6", "7", "8", "9", "10"]) && count($array_tarjeta_titular) <= 0) ||
    in_array($importe_total, ["", "0", 0])
) devolver_error(ERROR_GENERAL);


//Separo servicios de los grupos familiares del resto.
$array_servicio_grupo_familiar = [];
$array_servicios = [];
foreach ($array_servicios_agregados as $servicios) {
    if (in_array($servicios['numero_servicio'], ["13", "15"])) {
        array_push($array_servicio_grupo_familiar, $servicios);
    } else {
        array_push($array_servicios, $servicios);
    }
}



$id_socio_padron = agregar_padron_datos_socios();
if ($id_socio_padron == false) devolver_error("Ocurrieron errores al registrar al socio");


$registrar_direccion = registrar_direccion_socio($id_socio_padron);
if ($registrar_direccion == false) devolver_error("Ocurrieron errores al registrar la dirección del socio");


if (count($array_servicios) > 0) {
    $registrar_productos = agregar_padron_producto_socios(
        $array_datos_beneficiario,
        $observacion,
        $array_servicios,
        $id_metodo_pago
    );
    if ($registrar_productos == false) devolver_error("Ocurrieron errores al registrar los productos");
}


if (count($array_servicio_grupo_familiar) > 0) {
    $array_beneficiarios_servicio = $_REQUEST['array_beneficiarios_servicio'];

    $registrar_productos = agregar_padron_producto_socios(
        $array_datos_beneficiario,
        $observacion,
        $array_servicio_grupo_familiar,
        $id_metodo_pago
    );

    $agregar_beneficiarios_servicio_padron = agregar_padron_datos_socio_grupo_familiar();
    if ($agregar_beneficiarios_servicio_padron == false)
        devolver_error("Ocurrieron errores al registrar a los socios del grupo familiar");

    $registrar_productos = agregar_padron_productos_grupo_familiar(
        $array_datos_beneficiario,
        $array_beneficiarios_servicio,
        $array_servicio_grupo_familiar,
        $id_metodo_pago
    );
    if ($registrar_productos == false)
        devolver_error("Ocurrieron errores al registrar los productos del grupo familiar");
}


$registro_historial = registrar_historial_venta($id_socio_padron, "ALTA A TRAVES DE CALL");
if ($registrar_productos == false) devolver_error("Ocurrieron errores al registrar en el historial de venta");



$response['error'] = false;
$response['mensaje'] = 'Se ha completado la afiliación con éxito!';
echo json_encode($response);




function agregar_padron_datos_socios()
{
    $conexion = connection(DB_CALL, false);
    $tabla = TABLA_PADRON_DATOS_SOCIO;


    $id_vendedor = $_SESSION["mpi_id_vendedor"];
    $nombre_vendedor = $_SESSION["mpi_nombre_vendedor"];
    $numero_vendedor = $_SESSION["mpi_cedula_vendedor"];

    $array_datos_beneficiario = $_REQUEST['array_datos_beneficiario'];
    $observacion = $_REQUEST['observacion'];
    $id_metodo_pago = $_REQUEST['id_metodo_pago'];
    $metodo_pago = $_REQUEST['metodo_pago'];
    $nombre_titular_onajpu = $_REQUEST['nombre_titular_onajpu'];
    $cedula_titular_onajpu = $_REQUEST['cedula_titular_onajpu'];
    $array_tarjeta_titular = isset($_REQUEST['array_tarjeta_titular']) ? $_REQUEST['array_tarjeta_titular'] : [];
    $importe_total = $_REQUEST['importe_total'];
    $convenio = $_REQUEST['convenio'];

    /** Datos del beneficiario **/
    $nombre_completo = $array_datos_beneficiario["nombre_completo"];
    $celular = $array_datos_beneficiario["celular"];
    $telefono_fijo = $array_datos_beneficiario["telefono_fijo"];
    $telefono_alternativo = $array_datos_beneficiario["telefono_alternativo"];
    $tel = "";
    if ($celular != "") $tel .= "$celular ";
    if ($telefono_fijo != "") $tel .= "$telefono_fijo ";
    if ($telefono_alternativo != "") $tel .= $telefono_alternativo;
    $tel = trim($tel);
    $cedula = $array_datos_beneficiario["cedula"];
    $direccion = $array_datos_beneficiario["direccion"];
    $id_localidad = $array_datos_beneficiario["id_localidad"];
    $nombre_localidad = $array_datos_beneficiario["nombre_localidad"];
    $fecha_nacimiento = $array_datos_beneficiario["fecha_nacimiento"];
    $edad = calcular_edad($fecha_nacimiento);
    $correo_electronico = $array_datos_beneficiario["correo_electronico"] != "" ? $array_datos_beneficiario["correo_electronico"] : "";
    $dato_extra = $array_datos_beneficiario["dato_extra"];
    /** End Datos del beneficiario **/

    /** Datos de la tarjeta **/
    $numero_tarjeta = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["numero_tarjeta"] : 0;
    $tipo_tarjeta = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["tipo_tarjeta"] : 0;
    $cvv_tarjeta = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["cvv_tarjeta"] : 0;
    $banco_emisor = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["banco_emisor"] : 0;
    $cedula_titular = (count($array_tarjeta_titular) > 0) ? $array_tarjeta_titular["cedula_titular"] : ($id_metodo_pago == 1 ? $cedula_titular_onajpu : 0);
    $nombre_titular = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["nombre_titular"] : 0;
    $nombre_titular = (count($array_tarjeta_titular) > 0) ? $array_tarjeta_titular["nombre_titular"] : ($id_metodo_pago == 1 ? $nombre_titular_onajpu : 0);
    $mes_vencimiento = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["mes_vencimiento"] : 0;
    $anio_vencimiento = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["anio_vencimiento"] : 0;
    $email_titular = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["email_titular"] : "";
    $tel_titular = "";
    if (count($array_tarjeta_titular) > 0) {
        $celular_tarjeta_titular = $array_tarjeta_titular["celular_titular"];
        $telefono_tarjeta_titular = $array_tarjeta_titular["telefono_titular"];
        $tel_titular =
            ($celular_tarjeta_titular != "" && $telefono_tarjeta_titular != "") ?
            "$celular_tarjeta_titular $telefono_tarjeta_titular" : (($celular_tarjeta_titular != "" && $telefono_tarjeta_titular == "") ? $celular : (($telefono_tarjeta_titular != "" && $telefono_tarjeta_titular == "") ? $telefono_tarjeta_titular : ""));
    }
    /** End Datos de la tarjeta **/

    $obtener_radio_ruta = obtener_radio_ruta($id_metodo_pago, $metodo_pago, $nombre_localidad);
    $cantidad_radio_ruta = mysqli_num_rows($obtener_radio_ruta);
    if ($id_metodo_pago == 3 && $cantidad_radio_ruta <= 0) devolver_error("No hay cobrador para esta ruta, seleccione otro método de pago");
    $resultados_radio_ruta = mysqli_fetch_assoc($obtener_radio_ruta);
    $ruta = $cantidad_radio_ruta > 1 ? "" : $resultados_radio_ruta['ruta'];
    $radio = $resultados_radio_ruta['radio'];
    if ($cvv_tarjeta == "") $cvv_tarjeta = 0;


    $sucursal = "1372";
    $sucursal_cobranzas = $convenio != "" ? $convenio : $sucursal;
    $sucursal_cobranza_num = in_array($radio, ["1372", "13728"]) ? '1372' : '99';
    $empresa_marca = in_array($radio, ["1372", "13728"]) ? '18' : '99';
    $empresa_rut = "08";
    $id_relacion = in_array($id_metodo_pago, ["4", "5", "6", "7", "8", "9", "10"]) ? "99-$cedula" : "$empresa_rut-$cedula"; // Si es tarjeta 99-cedula
    $rutcentralizado = '08';
    $metodo_pago = obtener_metodo_pago($radio);


    try {
        $sql = "INSERT INTO {$tabla} SET 
                id = NULL,
                nombre = '$nombre_completo',
                tel = '$tel',
                cedula = '$cedula',
                direccion = '$direccion',
                sucursal = '$sucursal',
                ruta = '$ruta',
                radio = '$radio',
                activo = '1',
                fecha_nacimiento = '$fecha_nacimiento',
                edad = '$edad',
                tarjeta = '$tipo_tarjeta',
                tipo_tarjeta = '$tipo_tarjeta',
                numero_tarjeta = '$numero_tarjeta',
                nombre_titular = '$nombre_titular',
                cedula_titular = '$cedula_titular',
                telefono_titular = '$tel_titular',
                anio_e = '$anio_vencimiento',
                mes_e = '$mes_vencimiento',
                cuotas_mercadopago = '0', 
                sucursal_cobranzas = '$sucursal_cobranzas',
                sucursal_cobranza_num = '$sucursal_cobranza_num',
                empresa_marca = '$empresa_marca',
                flag = '1',
                count = '0',
                observaciones = '$observacion',
                grupo = '0',
                idrelacion = '$id_relacion',
                empresa_rut = '$empresa_rut',
                total_importe = '$importe_total',
                nactual = '1',
                `version` = '1',
                flagchange = '1',
                rutcentralizado = '$rutcentralizado',
                `PRINT` = '0',
                EMITIDO = '1',
                movimientoabm = 'ALTA',
                abm = 'ALTA',
                abmactual = '1',
                `check` = '0',
                usuario = '$numero_vendedor',
                usuariod = '0',
                fechafil = NOW(),
                radioViejo = '0',
                extra = '0',
                nomodifica = '0',
                metodo_pago = '$metodo_pago',
                cvv = '$cvv_tarjeta',
                existe_padron = '0',
                email = '$correo_electronico',
                email_titular = '$email_titular',
                tarjeta_vida = '1',
                banco_emisor = '$banco_emisor',
                accion = '1',
                estado = '1',
                localidad = '$id_localidad',
                dato_extra = '$dato_extra',
                llamada_entrante = '0',
                origen_venta = '0',
                alta = '1',
                es_admin = '0',
                id_usuario = '$id_vendedor'";
        $consulta = mysqli_query($conexion, $sql);
    } catch (\Throwable $error) {
        registrar_errores($sql, "completar_afiliacion_individual.php", $error);
        $consulta = false;
    }

    $resultados = $consulta ? mysqli_insert_id($conexion) : false;

    mysqli_close($conexion);
    return $resultados;
}


function agregar_padron_datos_socio_grupo_familiar()
{
    $conexion = connection(DB_CALL, false);
    $tabla = TABLA_PADRON_DATOS_SOCIO;


    $id_vendedor = $_SESSION["mpi_id_vendedor"];
    $nombre_vendedor = $_SESSION["mpi_nombre_vendedor"];
    $numero_vendedor = $_SESSION["mpi_cedula_vendedor"];

    $resultados = true;

    foreach ($_REQUEST['array_beneficiarios_servicio'] as $beneficiarios_servicio) {
        $nombre_completo = $beneficiarios_servicio["nombre"];
        $cedula = $beneficiarios_servicio["cedula"];
        $tel = $beneficiarios_servicio["telefono"];
        $fecha_nacimiento = $beneficiarios_servicio["fecha_nacimiento"];
        $edad = calcular_edad($fecha_nacimiento);


        $observacion = $_REQUEST['observacion'];
        $id_metodo_pago = $_REQUEST['id_metodo_pago'];
        $metodo_pago = $_REQUEST['metodo_pago'];
        $nombre_titular_onajpu = $_REQUEST['nombre_titular_onajpu'];
        $cedula_titular_onajpu = $_REQUEST['cedula_titular_onajpu'];
        $array_tarjeta_titular = isset($_REQUEST['array_tarjeta_titular']) ? $_REQUEST['array_tarjeta_titular'] : [];
        $importe_total = $_REQUEST['importe_total'];
        $convenio = $_REQUEST['convenio'];

        /** Datos del beneficiario **/
        $array_datos_beneficiario = $_REQUEST['array_datos_beneficiario'];
        $cedula_titular_grupo = $array_datos_beneficiario["cedula"];
        $direccion = $array_datos_beneficiario["direccion"];
        $id_localidad = $array_datos_beneficiario["id_localidad"];
        $nombre_localidad = $array_datos_beneficiario["nombre_localidad"];
        $correo_electronico = $array_datos_beneficiario["correo_electronico"] != "" ? $array_datos_beneficiario["correo_electronico"] : "";
        $dato_extra = $array_datos_beneficiario["dato_extra"];
        /** End Datos del beneficiario **/

        /** Datos de la tarjeta **/
        $numero_tarjeta = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["numero_tarjeta"] : 0;
        $tipo_tarjeta = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["tipo_tarjeta"] : 0;
        $cvv_tarjeta = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["cvv_tarjeta"] : 0;
        $banco_emisor = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["banco_emisor"] : 0;
        $cedula_titular = (count($array_tarjeta_titular) > 0) ? $array_tarjeta_titular["cedula_titular"] : ($id_metodo_pago == 1 ? $cedula_titular_onajpu : 0);
        $nombre_titular = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["nombre_titular"] : 0;
        $nombre_titular = (count($array_tarjeta_titular) > 0) ? $array_tarjeta_titular["nombre_titular"] : ($id_metodo_pago == 1 ? $nombre_titular_onajpu : 0);
        $mes_vencimiento = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["mes_vencimiento"] : 0;
        $anio_vencimiento = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["anio_vencimiento"] : 0;
        $email_titular = count($array_tarjeta_titular) > 0 ? $array_tarjeta_titular["email_titular"] : "";
        $tel_titular = "";
        if (count($array_tarjeta_titular) > 0) {
            $celular_tarjeta_titular = $array_tarjeta_titular["celular_titular"];
            $telefono_tarjeta_titular = $array_tarjeta_titular["telefono_titular"];
            $tel_titular =
                ($celular_tarjeta_titular != "" && $telefono_tarjeta_titular != "") ?
                "$celular_tarjeta_titular $telefono_tarjeta_titular" : (($celular_tarjeta_titular != "" && $telefono_tarjeta_titular == "") ? $celular : (($telefono_tarjeta_titular != "" && $telefono_tarjeta_titular == "") ? $telefono_tarjeta_titular : ""));
        }
        /** End Datos de la tarjeta **/

        $obtener_radio_ruta = obtener_radio_ruta($id_metodo_pago, $metodo_pago, $nombre_localidad);
        $cantidad_radio_ruta = mysqli_num_rows($obtener_radio_ruta);
        $resultados_radio_ruta = mysqli_fetch_assoc($obtener_radio_ruta);
        $ruta = $cantidad_radio_ruta > 1 ? "" : $resultados_radio_ruta['ruta'];
        $radio = $resultados_radio_ruta['radio'];

        $sucursal = "1372";
        $sucursal_cobranzas = $convenio != "" ? $convenio : $sucursal;
        $sucursal_cobranza_num = in_array($radio, ["1372", "13728"]) ? '1372' : '99';
        $empresa_marca = in_array($radio, ["1372", "13728"]) ? '18' : '99';
        $empresa_rut = "08";
        $id_relacion = in_array($id_metodo_pago, ["4", "5", "6", "7", "8", "9", "10"]) ? "99-$cedula_titular_grupo" : "$empresa_rut-$cedula_titular_grupo"; // Si es tarjeta 99-cedula
        $rutcentralizado = '08';
        $metodo_pago = obtener_metodo_pago($radio);


        try {
            $sql = "INSERT INTO {$tabla} SET 
                id = NULL,
                nombre = '$nombre_completo',
                tel = '$tel',
                cedula = '$cedula',
                direccion = '$direccion',
                sucursal = '$sucursal',
                ruta = '$ruta',
                radio = '$radio',
                activo = '1',
                fecha_nacimiento = '$fecha_nacimiento',
                edad = '$edad',
                tarjeta = '$tipo_tarjeta',
                tipo_tarjeta = '$tipo_tarjeta',
                numero_tarjeta = '$numero_tarjeta',
                nombre_titular = '$nombre_titular',
                cedula_titular = '$cedula_titular',
                telefono_titular = '$tel_titular',
                anio_e = '$anio_vencimiento',
                mes_e = '$mes_vencimiento',
                cuotas_mercadopago = '0', 
                sucursal_cobranzas = '$sucursal_cobranzas',
                sucursal_cobranza_num = '$sucursal_cobranza_num',
                empresa_marca = '$empresa_marca',
                flag = '1',
                count = '0',
                observaciones = '$observacion',
                grupo = '0',
                idrelacion = '$id_relacion',
                empresa_rut = '$empresa_rut',
                total_importe = '0',
                nactual = '1',
                `version` = '1',
                flagchange = '1',
                rutcentralizado = '$rutcentralizado',
                `PRINT` = '0',
                EMITIDO = '1',
                movimientoabm = 'ALTA',
                abm = 'ALTA',
                abmactual = '1',
                `check` = '0',
                usuario = '$numero_vendedor',
                usuariod = '0',
                fechafil = NOW(),
                radioViejo = '0',
                extra = '0',
                nomodifica = '0',
                metodo_pago = '$metodo_pago',
                cvv = '$cvv_tarjeta',
                existe_padron = '0',
                email = '$correo_electronico',
                email_titular = '$email_titular',
                tarjeta_vida = '1',
                banco_emisor = '$banco_emisor',
                accion = '1',
                estado = '1',
                localidad = '$id_localidad',
                dato_extra = '$dato_extra',
                llamada_entrante = '0',
                origen_venta = '0',
                alta = '1',
                es_admin = '0',
                id_usuario = '$id_vendedor'";
            $consulta = mysqli_query($conexion, $sql);
        } catch (\Throwable $error) {
            registrar_errores($sql, "completar_afiliacion_individual.php", $error);
            $consulta = false;
        }


        $resultados = $consulta ? mysqli_insert_id($conexion) : false;

        if ($resultados != false) {
            $registro_historial = registrar_historial_venta($resultados, "ALTA A TRAVES DE CALL");
            if ($registro_historial == false) devolver_error("Ocurrieron errores al registrar en el historial de venta");

            $registrar_direccion = registrar_direccion_socio($resultados, $cedula);
            if ($registrar_direccion == false) devolver_error("Ocurrieron errores al registrar la dirección del socio");
        }
    }


    mysqli_close($conexion);
    return $resultados;
}


function registrar_direccion_socio($id_socio_padron, $cedula_param = false)
{
    $conexion = connection(DB_CALL, false);
    $tabla = TABLA_DIRECCIONES_SOCIOS;

    $array_datos_beneficiario = $_REQUEST['array_datos_beneficiario'];
    $cedula = $cedula_param != false ? $cedula_param : $array_datos_beneficiario["cedula"];
    $calle = mysqli_real_escape_string($conexion, $array_datos_beneficiario["calle"]);
    $puerta = $array_datos_beneficiario["puerta"];
    $manzana = $array_datos_beneficiario["manzana"];
    $solar = $array_datos_beneficiario["solar"];
    $apartamento = $array_datos_beneficiario["apartamento"];
    $esquina = mysqli_real_escape_string($conexion, $array_datos_beneficiario["esquina"]);
    $referencia = mysqli_real_escape_string($conexion, $array_datos_beneficiario["referencia"]);

    try {
        $sql = "INSERT INTO {$tabla} SET 
                id_socio = '$id_socio_padron',
                calle = '$calle',
                puerta = '$puerta',
                manzana = '$manzana',
                solar = '$solar',
                apartamento = '$apartamento',
                esquina = '$esquina',
                referencia = '$referencia',
                cedula_socio = '$cedula'";
        $consulta = mysqli_query($conexion, $sql);
    } catch (\Throwable $error) {
        registrar_errores($sql, "completar_afiliacion_individual.php", $error);
        $consulta = false;
    }

    mysqli_close($conexion);
    return $consulta;
}


function agregar_padron_producto_socios($datos_beneficiario, $observacion, $array_servicios, $id_metodo_pago)
{
    $conexion = connection(DB_CALL, false);
    $tabla = TABLA_PADRON_PRODUCTO_SOCIO;

    $cedula = $datos_beneficiario['cedula'];
    $fecha_nacimiento = $datos_beneficiario['fecha_nacimiento'];
    $edad = calcular_edad($fecha_nacimiento);

    $id_vendedor = $_SESSION["mpi_id_vendedor"];
    $nombre_vendedor = $_SESSION["mpi_nombre_vendedor"];
    $numero_vendedor = $_SESSION["mpi_cedula_vendedor"];

    $errores = 0;
    //Recorro los servicios
    foreach ($array_servicios as $servicios) {
        $id_servicio = $servicios['numero_servicio'];
        $numeros_servicio = obtener_numeros_servicio($id_servicio);
        $cantidad_horas = $servicios['cantidad_horas'] != "" ? $servicios['cantidad_horas'] : 8;
        $modulos_horas = $cantidad_horas == 8 ? 1 : ($cantidad_horas == 16 ? 2 : 3);
        $promo_estaciones = $servicios['promo_estaciones'];
        $numero_promo = $servicios['numero_promo'];
        $numero_promo = !in_array($numero_promo, ["", null]) ? obtener_datos_promocion($numero_promo) : 0;
        $total_importe = $servicios['total_importe'];
        $total_importe = $total_importe != "false" ? $total_importe : calcular_precio_servicio($edad, $id_servicio, $cantidad_horas, $promo_estaciones, $total_importe);
        $empresa_rut = "05";
        $id_relacion = in_array($id_metodo_pago, ["4", "5", "6", "7", "8", "9", "10"]) ? "99-$cedula" : "$empresa_rut-$cedula"; // Si es tarjeta 99-cedula


        $total_importe = round($total_importe / $modulos_horas);

        //Recorro los números de servicio
        while ($row = mysqli_fetch_assoc($numeros_servicio)) {
            $servicio = $row["numero_servicio"];
            if ($id_servicio == "13") $servicio = "63";
            if ($id_servicio == "15") $servicio = "65";

            //Registro los productos en módulos de 8 horas
            for ($i = 0; $i < $modulos_horas; $i++) {
                try {
                    $sql = "INSERT INTO {$tabla} SET
                    id = NULL,
                    cedula = '$cedula',
                    servicio = '$servicio',
                    hora = '8',
                    importe = '$total_importe',
                    cod_promo = '$numero_promo',
                    fecha_registro = NOW(),
                    numero_contrato = '0',
                    fecha_afiliacion = NOW(),
                    nombre_vendedor = '$nombre_vendedor',
                    observaciones = '$observacion',
                    lugar_venta = '0',
                    vendedor_independiente = '0',
                    activo = '999',
                    movimiento = 'ALTA',
                    fecha_inicio_derechos = '2015-09-15',
                    numero_vendedor = '$numero_vendedor',
                    keepprice1 = '$total_importe',
                    promoactivo = '0',
                    tipo_de_cobro = '0',
                    tipo_iva = '2',
                    idrelacion = '$id_relacion',
                    codigo_precio = '0',
                    aumento = '0',
                    empresa = '$empresa_rut',
                    nactual = '1',
                    servdecod = '$servicio',
                    count = '0',
                    `version` = '1',
                    abm = 'ALTA',
                    abmactual = '1',
                    usuario = '$id_vendedor',
                    usuariod = '0',
                    extra = '0',
                    nomodifica = '0',
                    precioOriginal = '$total_importe',
                    abitab = '0',
                    id_padron = '0',
                    accion = '1',
                    cedula_titular_gf = NULL";
                    $consulta = mysqli_query($conexion, $sql);
                } catch (\Throwable $error) {
                    registrar_errores($sql, "completar_afiliacion_individual.php", $error);
                    $errores++;
                }
            }
        }
    }

    return $errores > 0 ? false : true;
}


function agregar_padron_productos_grupo_familiar($datos_beneficiario, $array_beneficiarios_servicio, $array_servicio_grupo_familiar, $id_metodo_pago)
{
    $conexion = connection(DB_CALL, false);
    $tabla = TABLA_PADRON_PRODUCTO_SOCIO;

    $cedula = $datos_beneficiario['cedula'];
    $fecha_nacimiento = $datos_beneficiario['fecha_nacimiento'];
    $edad = calcular_edad($fecha_nacimiento);

    $id_vendedor = $_SESSION["mpi_id_vendedor"];
    $nombre_vendedor = $_SESSION["mpi_nombre_vendedor"];
    $numero_vendedor = $_SESSION["mpi_cedula_vendedor"];

    $errores = 0;
    //Recorro los beneficiarios
    foreach ($array_beneficiarios_servicio as $beneficiarios) {
        $cedula_beneficiarios = $beneficiarios['cedula'];
        $empresa_rut = "05";
        $id_relacion = in_array($id_metodo_pago, ["4", "5", "6", "7", "8", "9", "10"]) ? "99-$cedula_beneficiarios" : "$empresa_rut-$cedula_beneficiarios"; // Si es tarjeta 99-cedula

        //Recorro los servicios
        foreach ($array_servicio_grupo_familiar as $array_servicios) {
            $id_servicio = $array_servicios['numero_servicio'];
            $numeros_servicio = obtener_numeros_servicio($id_servicio);
            $cantidad_horas = $array_servicios['cantidad_horas'] != "" ? $array_servicios['cantidad_horas'] : 8;
            $modulos_horas = $cantidad_horas == 8 ? 1 : ($cantidad_horas == 16 ? 2 : 3);
            $promo_estaciones = $array_servicios['promo_estaciones'] != "false" ? $array_servicios['promo_estaciones'] : "";
            $numero_promo = $array_servicios['numero_promo'];
            $numero_promo = !in_array($numero_promo, ["", null]) ? obtener_datos_promocion($numero_promo) : 0;
            $total_importe = $array_servicios['total_importe'];
            $total_importe = $total_importe != "false" ? $total_importe : calcular_precio_servicio($edad, $id_servicio, $cantidad_horas, $promo_estaciones, $total_importe);


            //Recorro los números de servicio
            while ($row = mysqli_fetch_assoc($numeros_servicio)) {
                $servicio = $row["numero_servicio"];
                if ($id_servicio == "13") $servicio = "64";
                if ($id_servicio == "15") $servicio = "66";

                //Registro los productos en módulos de 8 horas
                for ($i = 0; $i < $modulos_horas; $i++) {
                    try {
                        $sql = "INSERT INTO {$tabla} SET
                                id = NULL,
                                cedula = '$cedula_beneficiarios',
                                servicio = '$servicio',
                                hora = '8',
                                importe = '0',
                                cod_promo = '$numero_promo',
                                fecha_registro = NOW(),
                                numero_contrato = '0',
                                fecha_afiliacion = NOW(),
                                nombre_vendedor = '$nombre_vendedor',
                                observaciones = 'Pendiente Revision',
                                lugar_venta = '0',
                                vendedor_independiente = '0',
                                activo = '999',
                                movimiento = 'ALTA',
                                fecha_inicio_derechos = '2015-09-15',
                                numero_vendedor = '$numero_vendedor',
                                keepprice1 = '0',
                                promoactivo = '0',
                                tipo_de_cobro = '0',
                                tipo_iva = '2',
                                idrelacion = '$id_relacion',
                                codigo_precio = '0',
                                aumento = '0',
                                empresa = '$empresa_rut',
                                nactual = '1',
                                servdecod = '$servicio',
                                count = '0',
                                `version` = '1',
                                abm = 'ALTA',
                                abmactual = '1',
                                usuario = '$id_vendedor',
                                usuariod = '0',
                                extra = '0',
                                nomodifica = '0',
                                precioOriginal = '0',
                                abitab = '0',
                                id_padron = '0',
                                accion = '1',
                                cedula_titular_gf = '$cedula'";
                        $consulta = mysqli_query($conexion, $sql);
                    } catch (\Throwable $error) {
                        registrar_errores($sql, "completar_afiliacion_individual.php", $error);
                        $errores++;
                    }
                }
            }
        }
    }

    return $errores > 0 ? false : true;
}
