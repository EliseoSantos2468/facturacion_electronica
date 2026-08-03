<?php

namespace App\Services\Mh\Exceptions;

/**
 * Falla al firmar un DTE: firmador inaccesible, clave privada errónea,
 * certificado vencido, o respuesta con status != OK.
 */
class FirmadorException extends MhException
{
    /**
     * @param  array<string,mixed>  $payload
     */
    public function __construct(
        string $message,
        public readonly array $payload = [],
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
