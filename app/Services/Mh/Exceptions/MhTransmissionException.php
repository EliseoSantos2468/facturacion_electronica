<?php

namespace App\Services\Mh\Exceptions;

/**
 * El MH respondió pero rechazó el DTE (esquema inválido, sello duplicado, etc.).
 * `payload` contiene la respuesta completa del MH para diagnóstico.
 */
class MhTransmissionException extends MhException
{
    /**
     * @param  array<string,mixed>  $payload
     */
    public function __construct(
        string $message,
        public readonly array $payload = [],
        public readonly ?int $httpStatus = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
