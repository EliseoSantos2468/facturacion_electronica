<?php

namespace App\Services\Dte;

use App\Enums\TipoDte;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use RuntimeException;

/**
 * Valida un payload DTE contra el JSON Schema oficial del MH.
 *
 * Los schemas se cargan desde `resources/schemas/mh/` con la convención
 *   {alias}-v{version}.json
 * donde `alias` depende del tipo de DTE (ver mapa `SCHEMA_ALIAS`) y `version`
 * viene del propio enum `TipoDte::version()`.
 *
 * Si el archivo no existe, se lanza una excepción explícita — nunca se debe
 * transmitir un DTE al MH sin haberlo validado antes contra su schema oficial.
 */
class SchemaValidator
{
    private const SCHEMA_ALIAS = [
        '01' => 'fe',
        '03' => 'ccf',
        '04' => 'nr',
        '05' => 'nc',
        '06' => 'nd',
        '07' => 'cr',
        '08' => 'cl',
        '09' => 'dcl',
        '11' => 'fex',
        '14' => 'fse',
        '15' => 'cd',
    ];

    public function __construct(private readonly string $schemasPath)
    {
    }

    /**
     * @param  array<string,mixed>  $payload
     * @return array<int,string>  Lista de mensajes de error. Vacío = válido.
     */
    public function validar(TipoDte $tipoDte, array $payload): array
    {
        $schemaPath = $this->rutaSchema($tipoDte);

        if (! is_file($schemaPath)) {
            throw new RuntimeException(
                "No se encontró el schema del MH para el DTE tipo {$tipoDte->value} en {$schemaPath}. "
                . 'Ver resources/schemas/mh/README.md para instrucciones de descarga.'
            );
        }

        $schema = json_decode((string) file_get_contents($schemaPath));
        $data = json_decode(json_encode($payload));

        $validator = new Validator();
        $validator->validate($data, $schema, Constraint::CHECK_MODE_TYPE_CAST);

        if ($validator->isValid()) {
            return [];
        }

        return array_map(
            fn (array $error) => sprintf('[%s] %s', $error['property'], $error['message']),
            $validator->getErrors(),
        );
    }

    private function rutaSchema(TipoDte $tipoDte): string
    {
        $alias = self::SCHEMA_ALIAS[$tipoDte->value]
            ?? throw new RuntimeException("Sin alias de schema para el tipo {$tipoDte->value}.");

        return rtrim($this->schemasPath, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . "{$alias}-v{$tipoDte->version()}.json";
    }
}
