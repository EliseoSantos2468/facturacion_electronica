<?php

namespace App\Services\Mh\Exceptions;

use RuntimeException;

/**
 * Excepción base para cualquier fallo en la integración con el Ministerio de Hacienda.
 */
abstract class MhException extends RuntimeException
{
}
