<?php

namespace App\Services\Dte\Contracts;

use App\Enums\TipoDte;

/**
 * Contrato común de todos los builders de DTE.
 * Cada implementación produce el payload asociativo listo para firmar/transmitir.
 */
interface DteBuilder
{
    public function tipoDte(): TipoDte;

    /**
     * @return array<string,mixed>  Payload conforme al JSON Schema del MH para su tipo/versión.
     */
    public function build(): array;
}
