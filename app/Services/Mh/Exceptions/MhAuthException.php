<?php

namespace App\Services\Mh\Exceptions;

/**
 * Falla al autenticarse contra el MH (credenciales inválidas, servicio caído).
 */
class MhAuthException extends MhException
{
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
