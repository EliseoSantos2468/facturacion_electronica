# Esquemas JSON oficiales del Ministerio de Hacienda (SV)

Este directorio contiene los JSON Schemas publicados por el MH que definen
la estructura válida de cada tipo de DTE. `SchemaValidator` los carga desde
disco por convención de nombre:

```
{tipo_dte}-v{version}.json
```

Por ejemplo:

- `fe-v1.json`  → Factura (CAT-002 código 01), versión 1
- `ccf-v3.json` → Comprobante de Crédito Fiscal (03), versión 3
- `nr-v3.json`  → Nota de Remisión (04), versión 3
- `nc-v3.json`  → Nota de Crédito (05), versión 3
- `nd-v3.json`  → Nota de Débito (06), versión 3
- `cr-v1.json`  → Comprobante de Retención (07)
- `cl-v1.json`  → Comprobante de Liquidación (08)
- `dcl-v1.json` → Documento Contable de Liquidación (09)
- `fex-v1.json` → Factura de Exportación (11)
- `fse-v1.json` → Factura de Sujeto Excluido (14)
- `cd-v1.json`  → Comprobante de Donación (15)

También:

- `invalidacion-v2.json` → Evento de invalidación
- `contingencia-v3.json` → Evento de contingencia

## Cómo obtenerlos

1. Ir al portal DTE del MH (Factura Electrónica → Documentación técnica).
2. Descargar el paquete "Esquemas JSON DTE v3" (o la versión vigente).
3. Copiar cada `.json` a este directorio manteniendo la convención de nombre de arriba.

**No se incluyen aquí para evitar redistribuir versiones desactualizadas** y
porque cualquier cambio publicado por el MH debería reemplazarlos sin tocar
código. Si el archivo no existe, `SchemaValidator::validate()` lanzará una
excepción explícita indicando qué archivo falta.
