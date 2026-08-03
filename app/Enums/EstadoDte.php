<?php

namespace App\Enums;

/**
 * Estado interno de un DTE en el sistema (no es un catálogo del MH).
 * Los strings coinciden con los registros insertados por EstadoDteSeeder,
 * y sirven para consultar/comparar sin depender del id autoincremental.
 */
enum EstadoDte: string
{
    case Borrador = 'Borrador';
    case Firmado = 'Firmado';
    case Transmitido = 'Transmitido';
    case Rechazado = 'Rechazado';
    case Invalidado = 'Invalidado';
    case Contingencia = 'Contingencia';
}
