<?php

namespace App\Enums;

/**
 * Tipo de Documento Tributario Electrónico (CAT-002).
 * El valor coincide con el código MH usado en `numeroControl` y `tipoDte`.
 */
enum TipoDte: string
{
    case Factura = '01';
    case ComprobanteCreditoFiscal = '03';
    case NotaRemision = '04';
    case NotaCredito = '05';
    case NotaDebito = '06';
    case ComprobanteRetencion = '07';
    case ComprobanteLiquidacion = '08';
    case DocumentoContableLiquidacion = '09';
    case FacturaExportacion = '11';
    case FacturaSujetoExcluido = '14';
    case ComprobanteDonacion = '15';

    public function label(): string
    {
        return match ($this) {
            self::Factura => 'Factura',
            self::ComprobanteCreditoFiscal => 'Comprobante de Crédito Fiscal',
            self::NotaRemision => 'Nota de Remisión',
            self::NotaCredito => 'Nota de Crédito',
            self::NotaDebito => 'Nota de Débito',
            self::ComprobanteRetencion => 'Comprobante de Retención',
            self::ComprobanteLiquidacion => 'Comprobante de Liquidación',
            self::DocumentoContableLiquidacion => 'Documento Contable de Liquidación',
            self::FacturaExportacion => 'Factura de Exportación',
            self::FacturaSujetoExcluido => 'Factura de Sujeto Excluido',
            self::ComprobanteDonacion => 'Comprobante de Donación',
        };
    }

    /**
     * Versión del esquema JSON que exige el MH por tipo de DTE (a la fecha del Manual v3).
     */
    public function version(): int
    {
        return match ($this) {
            self::NotaRemision, self::NotaCredito, self::NotaDebito => 3,
            default => 1,
        };
    }
}
