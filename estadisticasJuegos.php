<?php
function main()
{
    //creo y cargo los arreglos
    $tikets = [];
    $juegoMasVendido = [];
    $juegoMasVendido = cargarJuegosMasVendidos($juegoMasVendido);
    $tikets = cargarTikets($tikets, $juegoMasVendido);
    menu($tikets, $juegoMasVendido);
}

function menu($tikets, $juegoMasVendido)
{
    do {
        echo "\n ------------------------------------------- \n";
        echo " ELIJA UNA OPCION: \n";
        echo "1) Ingresar una venta \n";
        echo "2) Mes con mayor monto de ventas \n";
        echo "3) Primer mes que supera un monto de ventas \n";
        echo "4) Información de un mes \n";
        echo "5) Juegos más vendidos Ordenados \n";
        echo "6) Ver datos completos \n";
        echo "0): SALIR" . "\n";
        echo "----------------------------------------------\n";
        echo "Opcion: ";
        $opcion = trim(fgets(STDIN));
        switch ($opcion) {
            case '1':
                echo ("Ingrese un Mes." . "\n");
                $mes = trim(fgets(STDIN));
                $numeroMes = devuelveNumero($mes);
                if ($numeroMes != -1) {
                    echo ("Ingrese un Juego." . "\n");
                    $juego = trim(fgets(STDIN));
                    echo ("Ingrese Precio Tiket." . "\n");
                    $precio = trim(fgets(STDIN));
                    echo ("Ingrese Cantidad de Tickets Vendidos." . "\n");
                    $cantidad = trim(fgets(STDIN));
                    if(is_numeric($precio)&& is_numeric($cantidad)){
                        $juegoMasVendido = ingresarVenta($numeroMes, $juegoMasVendido, $juego, $precio, $cantidad);
                        $tikets = incrementarMonto($numeroMes, $tikets, $precio, $cantidad);
                    }else{
                        echo ("ERROR: debe ingresar numeros para precio y cantidad. \n");
                    };
                } else {
                    echo ("ERROR: el valor ingresado no es un mes. \n");
                }
                break;

            case '2':
                $text = mesConMayorVenta($tikets);
                echo ($text);
                break;
            case '3':
                $text = superaMonto($tikets);
                echo ($text);
                break;
            case '4':
                $text = informacionMes($tikets, $juegoMasVendido);
                echo ($text);
                break;
            case '5':
                ordenar($juegoMasVendido);
                break;
            case '6':
                verDatos($tikets, $juegoMasVendido);
                break;
        }
    } while ($opcion <> 0);
}

// 1
/**
 * ingresarVenta una venta de tickets de un juego en un mes determinado (Mes, juego, precio ticket y cantidad de tickets vendidos)
 *
 * @param  integer $numeroMes
 * @param  array $juegoMasVendido
 * @param  string $juego
 * @param  integer $precio
 * @param  integer $cantidad
 * @return array
 */
function ingresarVenta($numeroMes, $juegoMasVendido, $juego, $precio, $cantidad)
{
    $montoNuevo = $cantidad * $precio; //dinero recolectado por el nuevo juego en ese mes
    $montoExistente = $juegoMasVendido[$numeroMes]['precioTicket'] * $juegoMasVendido[$numeroMes]['cantTickets']; //dinero recolectado por el juego mas vendido en ese mes

    //      Actualiza la información en la estructura juegoMasVendido sólo si la venta ingresada tiene mayor monto de venta que el juego ya existente en ese mes.
    if ($montoExistente < $montoNuevo) {
        $juegoMasVendido[$numeroMes]['juego'] = $juego;
        $juegoMasVendido[$numeroMes]['precioTicket'] = $precio;
        $juegoMasVendido[$numeroMes]['cantTickets'] = $cantidad;
    }

    return $juegoMasVendido;
}


/**
 * incrementarMonto (incrementa el monto total de las ventas en el mes ingresado )
 *
 * @param  integer $numeroMes
 * @param  array $tikets
 * @param  integer $precio
 * @param  integer $cantidad
 * @return array
 */
function incrementarMonto($numeroMes, $tikets, $precio, $cantidad)
{
    $montoNuevo = $cantidad * $precio; //dinero recolectado por el nuevo juego en ese mes
    $tikets[$numeroMes] = $tikets[$numeroMes] + $montoNuevo;
    return $tikets;
}

// 2 
/**
 * mesConMayorVenta
 *
 * @param  array $tikets
 * @return string
 */
function mesConMayorVenta($tikets)
{
    $sale = 0;
    $mes = 0;
    $i = 0;
    foreach ($tikets as $tiket) {
        if ($sale < $tiket) {
            $sale = $tiket;
            $mes = $i;
        }
        $i++;
    }
    $text = "El mes con mayor ventas es " . devuelveMes($mes) . " (" . $sale . ")" . "\n";
    return $text;
}

// 3 
/**
 * superaMonto
 *
 * @param  array $tikets
 * @return string
 */
function superaMonto($tikets)
{
    echo ("Ingrese un monto." . "\n");
    $text = "";
    $monto = trim(fgets(STDIN));
    $mes = 0;
    if (is_numeric($monto)) {

        foreach ($tikets as $tiket) {
            if ($monto < $tiket) {
                break;
            } else {
                $mes++;
            }
        }
    } else {
        $text = "El valor ingresado no es un numero." . "\n";
    }

    if ($mes < 11) {
        $text = "El mes que supera el monto $" . $monto . " es el mes de " . devuelveMes($mes) . " con $" . $tiket;
    } else {
        $text = "Ningun mes supera ese valor";
    }
    return $text;
}


// 4
/**
 * informacionMes Imprime la información completa de un mes (elegido por el usuario).
 *
 * @param  array $tikets
 * @param  array $juegoMasVendido
 * @return string
 */
function informacionMes($tikets, $juegoMasVendido)
{
    echo ("Ingrese un mes." . "\n");
    $text = "";
    $mes = trim(fgets(STDIN));
    $numeroMes = devuelveNumero($mes);
    if ($numeroMes != -1) {
        $ventatotal = $juegoMasVendido[$numeroMes]['cantTickets'] * $juegoMasVendido[$numeroMes]['precioTicket'];
        $text = '<' . $mes . '>' . "\n" .
            '-El juego con mayor monto de venta: ' . $juegoMasVendido[$numeroMes]['juego'] . "\n" .
            '-Numero de Tickets Vendidos: ' . $juegoMasVendido[$numeroMes]['cantTickets'] . "\n" .
            '-Venta total de juego: $' . $ventatotal . "\n" .
            '-Monto total de ventas del mes: $' . $tikets[$numeroMes] . "\n";
    } else {
        $text = "ERROR: el valor ingresado no es un mes. \n";
    }
    return $text;
}

// 5

// Función de comparación
function cmp($a, $b)
{
    $montoA = $a['precioTicket'] * $a['cantTickets'];
    $montoB = $b['precioTicket'] * $b['cantTickets'];
    if ($montoA == $montoB) {
        return 0;
    }
    return ($montoA < $montoB) ? -1 : 1;
}

/**
 * ordenar  (ordena el arreglo de juegoMasVendidos de menor a mayor por monto de venta)
 *
 * @param  array $arreglo
 * @return void
 */
function ordenar($arreglo)
{
    // Ordenar e imprimir el array resultante
    uasort($arreglo, 'cmp');
    print_r($arreglo);

    // imprime representación más entendible de un valor cualquiera.
    // Si es un arreglo imprime los detalles de cada elemento formateándolos para ser visualizados de una forma más entendible
    // Puede devolver su valor de salida como un valor de retorno si le pasa true como su segundo argumento
    // Útil para la depuración
}

// 6 
/**
 * verDatos
 *
 * @param  array $tikets
 * @param  array $juegoMasVendido
 * @return void
 */
function verDatos($tikets, $juegoMasVendido)
{

    for ($x = 0; $x < 12; $x++) {
        echo ("\n <" . devuelveMes($x) . "> ($" . $tikets[$x] . ") Juego Mas vendido: " . $juegoMasVendido[$x]['juego'] .
            ", precio tiket: " . $juegoMasVendido[$x]['precioTicket'] .
            ", cantidad tikets: " . $juegoMasVendido[$x]['cantTickets'] . ". \n");
    }
}



/**
 * devuelveMes (dado un número entre 0 y 11 devuelva el nombre del mes que le corresponde)
 *
 * @param  mixed $x
 * @return void
 */
function devuelveMes($x)
{
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    return $meses[$x];
}

/**
 * devuelveNumero
 *
 * @param  string $x
 * @return integer
 */
function devuelveNumero($x)
{
    $x = strtolower($x);
    $i = 0;
    $sale = -1;
    $meses = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
    foreach ($meses as $mes) {
        if ($mes == $x) {
            $sale = $i;
            break;
        }
        $i++;
    }
    return $sale;
}


function solicitarMes()
{
    echo ("Ingrese el nombre de un mes." . "\n");
    $mes = trim(fgets(STDIN));
    $sale = devuelveNumero($mes);
    return $sale;
}


/**
 * cargarTikets (carga cada mes del arreglo $tikets con  "precioTicket * cantTickets". )
 *
 * @param  array $tikets
 * @param  array $juegoMasVendido
 * @return array
 */
function cargarTikets($tikets, $juegoMasVendido)
{
    $i = 0;
    foreach ($juegoMasVendido as $juego) {
        $tikets[$i] = $juego['precioTicket'] * $juego['cantTickets'];
        $i++;
    }
    return $tikets;
}

/**
 * cargarJuegosMasVendidos carga datos al arreglo de juego mas vendido de cada mes.
 *
 * @param  arrat $arreglo
 * @return array
 */
function cargarJuegosMasVendidos($arreglo)
{
    $arreglo = array(
        ["juego" => 'Autitos Chocadores', "precioTicket" => 2, "cantTickets" => 1],
        ["juego" => 'Montaña Rusa', "precioTicket" => 5, "cantTickets" => 1],
        ["juego" => 'Gusano Loco', "precioTicket" => 20, "cantTickets" => 1],
        ["juego" => 'samba', "precioTicket" => 7, "cantTickets" => 1],
        ["juego" => 'Montaña Rusa', "precioTicket" => 1, "cantTickets" => 1],
        ["juego" => 'Gusano Loco', "precioTicket" => 4, "cantTickets" => 1],
        ["juego" => 'Autitos Chocadores', "precioTicket" => 6, "cantTickets" => 1],
        ["juego" => 'Montaña Rusa', "precioTicket" => 9, "cantTickets" => 1],
        ["juego" => 'samba', "precioTicket" => 8, "cantTickets" => 1],
        ["juego" => 'Autitos Chocadores', "precioTicket" => 11, "cantTickets" => 1],
        ["juego" => 'Montaña Rusa', "precioTicket" => 12, "cantTickets" => 1],
        ["juego" => 'samba', "precioTicket" => 10, "cantTickets" => 1]
    );
    return $arreglo;
}




main();
