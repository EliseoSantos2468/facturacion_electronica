# Catálogos grandes del MH (pendientes de completar)

Los archivos JSON de esta carpeta alimentan los seeders de `cat_municipios`,
`cat_unidad_medida` y `cat_actividad_economica`. Son catálogos de cientos de
filas (municipios ~262, unidades de medida ~99, actividades económicas
varios miles según CIIU) publicados por el Ministerio de Hacienda en el
Anexo de Catálogos vigente del Manual de Programador DTE.

**No se fabricaron códigos de memoria para estos tres catálogos** porque un
código fiscal incorrecto puede invalidar un documento real ante el MH. Cada
JSON solo trae las filas que se pudieron verificar con alta confianza (p.ej.
`0614 San Salvador Centro`, muy citado en los ejemplos oficiales de DTE), o
viene vacío como plantilla.

## Cómo completarlos

1. Descargar el Anexo de Catálogos vigente desde el portal de Factura
   Electrónica del MH (sección "Documentación técnica").
2. Convertir cada hoja al formato que espera el seeder correspondiente:
   - `municipios.json`: `{"municipio_codigo": "DDMM", "municipio_nombre": "...", "departamento_codigo": "DD"}`
   - `unidades_medida.json`: `{"unidad_codigo": "NN", "nombre_unidad": "..."}`
   - `actividades_economicas.json`: `{"codigo_actividad": "NNNN", "descripcion_actividad": "..."}`
3. Reemplazar el archivo aquí y correr:
   ```bash
   php artisan db:seed --class=Database\\Seeders\\CatMunicipioSeeder
   php artisan db:seed --class=Database\\Seeders\\CatUnidadMedidaSeeder
   php artisan db:seed --class=Database\\Seeders\\CatActividadEconomicaSeeder
   ```
   Los seeders son idempotentes (`updateOrCreate`), se pueden re-ejecutar sin duplicar filas.
