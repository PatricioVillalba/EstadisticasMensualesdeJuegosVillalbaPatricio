<?php
function main()
{
    $juegoMasVendido = cargarJuegosMasVendidos();
    $tikets = cargarTikets($juegoMasVendido);
    menu($tikets, $juegoMasVendido);
}

function menu($tikets, $juegoMasVendido)
{
    do { 
        $opcion=menuOpciones();
        switch ($opcion) {
            case '1':
                $numeroMes =convierteMesANumero();
                $juego= ingresarString('un Juego.'); 
                $precio= ingresarNumero('Precio Tiket.');
                $cantidad= ingresarNumero('Cantidad de Tikets.');
                $juegoMasVendido = ingresarVenta($numeroMes, $juegoMasVendido, $juego, $precio, $cantidad);
                $tikets = incrementarMonto($numeroMes, $tikets, $precio, $cantidad); 
                break;

            case '2':
                $mesConMayorVentas = mesConMayorVenta($tikets);
                $text= informacionCompleta($tikets, $juegoMasVendido,$mesConMayorVentas);
                echo ($text);
                break;
            case '3':
                $mes = superaMonto($tikets);
                if($mes!= -1){echo "El mes que supera ese monto es ". devuelveMes($mes);}
                else{echo "No hay mes que supere ese monto";}
                break;
            case '4':
                $text = informacionMes($tikets, $juegoMasVendido);
                echo ($text);
                break;
            case '5':
                ordenar($juegoMasVendido);
                break;
            case '6':
                echo verDatos($tikets, $juegoMasVendido);
                break;
        }
        
    } while ($opcion != 0);    
   
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
    return $mes;
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
    $monto = ingresarNumero("un monto.");
    $i = 0;
    $mes = -1;
    $encontro  = false;

    while ($i < 12 && !$encontro) {
        if ($monto < $tikets[$i]) {
            $mes = $i;
            $encontro  = true;
        } else {
            $i++;
        }
    }
    return $mes;
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
    $numeroMes = convierteMesANumero();   
    $text = informacionCompleta($tikets, $juegoMasVendido,$numeroMes);
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

    // print_r imprime representación más entendible de un valor cualquiera.
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
    $datos="";
    for ($numeroMes = 0; $numeroMes < 12; $numeroMes++) {
       $datos=$datos. informacionCompleta($tikets, $juegoMasVendido,$numeroMes)."\n";
    }

    return $datos;
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
 * convierteMesANumero  dado un string que representa el mes devuelve el número que le corresponde (en caso de no encontrar mes vuelve a pedirlo)
 *
 * @return int
 */
function convierteMesANumero()
{
    $encontro = false;
    $meses = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
    do {
        $i = 0;
        $mesbuscado =ingresarString('el nombre de un mes.'); 
        while ($i < count($meses) && !$encontro) {
            if ($meses[$i] == $mesbuscado) {
                $encontro = true;
            } else {
                $i++;
            }
        }
        if (!$encontro) {
            echo "Error mes no valido\n";
        }
    } while (!$encontro);

    return  $i;
}



/**
 * cargarTikets (carga cada mes del arreglo $tikets con  "precioTicket * cantTickets". )
 *
 * @param  array $tikets
 * @param  array $juegoMasVendido
 * @return array
 */
function cargarTikets($juegoMasVendido)
{
    $i = 0;
    $tikets = [];
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
function cargarJuegosMasVendidos()
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

/**
 * menuOpciones muestra las opciones en la pantalla, solicita al usuario una opción válida 
 * (si la opción no es válida vuelva a solicitarla)
 *
 * @return int
 */
function menuOpciones(){
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
    } while ($opcion < 0 || $opcion > 6);

    return $opcion;
}

/**
 * ingresarString pide un string y entra parte del mensaje que muestra en pantalla
 * por paramentro 
 *
 * @param  string  $texto
 * @return int
 */
function ingresarString( $texto){
    echo ("Ingrese ".$texto . "\n");
    $valor = trim(fgets(STDIN));
    return $valor;
}

/**
 * ingresarNumero pide un numero y entra parte del mensaje que muestra en pantalla
 * por paramentro (si el valor ingresado no es numero vuelve a pedirlo)
 * @param  mixed $texto
 * @return int
 */
function ingresarNumero($texto){
    do {
        echo ("Ingrese ".$texto . "\n");
        $valor = trim(fgets(STDIN));
        if(!is_numeric($valor)){
            echo ("ERROR: debe ingresar un numero. \n");
        }  
    } while (!is_numeric($valor));   
    
    return $valor;
}


/**
 * informacionCompleta muestra la informacion completa de un mes
 *
 * @param  array $tikets
 * @param  array $juegoMasVendido
 * @param  int $numeroMes
 * @return string
 */
function  informacionCompleta($tikets, $juegoMasVendido,$numeroMes){
    $ventatotal = $juegoMasVendido[$numeroMes]['cantTickets'] * $juegoMasVendido[$numeroMes]['precioTicket'];
    $text = '<' . devuelveMes($numeroMes). '>' . "\n" .
    '-El juego con mayor monto de venta: ' . $juegoMasVendido[$numeroMes]['juego'] . "\n" .
    '-Numero de Tickets Vendidos: ' . $juegoMasVendido[$numeroMes]['cantTickets'] . "\n" .
    '-Venta total de juego: $' . $ventatotal . "\n" .
    '-Monto total de ventas del mes: $' . $tikets[$numeroMes] . "\n";

    return $text;
}
main();
