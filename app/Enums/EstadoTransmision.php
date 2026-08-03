<?php

namespace App\Enums;

/**
 * Estado de un intento de transmisión hacia el MH (no es un catálogo del MH).
 * Los strings coinciden con los registros insertados por EstadoTransmisionSeeder.
 */
enum EstadoTransmision: string
{
    case Pendiente = 'Pendiente';
    case Enviado = 'Enviado';
    case Aceptado = 'Aceptado';
    case Rechazado = 'Rechazado';
    case ErrorConexion = 'Error de conexión';
}
