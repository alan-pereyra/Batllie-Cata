# Integración con WooCommerce

Este documento detalla cómo se conectan las 3 selecciones de cata con el catálogo de WooCommerce, la estructura de las tablas de especificaciones técnicas y la configuración de categorías y etiquetas.

---

## 1. Clasificación en el Catálogo

Los 3 productos se organizan bajo las siguientes taxonomías de WooCommerce:

- **Categoría:** `Cajas Premium` (slug: `cajas-premium`, term_id: `17`).
- **Etiqueta:** `Cata` (slug: `cata`).

Al asignar la etiqueta `cata`, el archivo de la etiqueta (`/etiqueta-producto/cata/`) actúa como la página de aterrizaje (*landing page*) exclusiva para los usuarios que completan el ritual de cata.

---

## 2. Productos Creados

### A. Caja Degustación Selección I: La Chispa Viva
- **Slug:** `caja-degustacion-seleccion-1-chispa-viva`
- **Contenido:** 6 alfajores (3 Limón + 3 Batllié Blanco).
- **Peso Neto Total:** ~450 g.
- **Precio Regular / Oferta:** \$15.000 / \$13.500.

### B. Caja Degustación Selección II: El Equilibrio Clásico
- **Slug:** `caja-degustacion-seleccion-2-equilibrio-clasico`
- **Contenido:** 6 alfajores (2 Batllié Negro + 2 Chocolate Blanco + 2 Café Suizo).
- **Peso Neto Total:** ~450 g.
- **Precio Regular / Oferta:** \$15.000 / \$13.500.

### C. Caja Degustación Selección III: Intensidad Absoluta
- **Slug:** `caja-degustacion-seleccion-3-intensidad-absoluta`
- **Contenido:** 6 alfajores (2 Chocolate Intenso + 2 Nuez al Whisky + 2 Mousse Nutella).
- **Peso Neto Total:** ~480 g.
- **Precio Regular / Oferta:** \$15.000 / \$13.500.

---

## 3. Tablas de Especificaciones Técnicas

Cada producto incluye en su descripción una tabla responsive con estilos inline y clases semánticas (`batllie-table-specs`):

```html
<table class="batllie-table-specs" style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem; border: 1px solid #e2e8f0; border-radius: 6px;">
    <thead>
        <tr style="background-color: #162a1f; color: #fdffdd;">
            <th style="padding: 12px 14px;">Variedad / Gusto</th>
            <th style="padding: 12px 14px;">Relleno &amp; Masa</th>
            <th style="padding: 12px 14px;">Cobertura</th>
            <th style="padding: 12px 14px; text-align: right;">Peso Individual</th>
        </tr>
    </thead>
    <tbody>
        <!-- Filas de cada alfajor con ingredientes, masa y peso individual (~75g / ~80g) -->
    </tbody>
    <tfoot>
        <tr style="background-color: #f1f5f3; font-weight: 600; color: #162a1f; border-top: 2px solid #cbd5e1;">
            <td colspan="3" style="padding: 12px 14px;">Peso Neto Total Estimado (6 unidades)</td>
            <td style="padding: 12px 14px; text-align: right;">~450 g / ~480 g</td>
        </tr>
    </tfoot>
</table>
```

---

## 4. Endpoint de Aprovisionamiento Automatizado

El plugin incluye un hook en `batllie-cata.php` para crear o actualizar automáticamente los productos, categorías, etiquetas y tablas con un único llamado autenticado:

```text
GET https://tudominio.com/?batllie_setup_cata_products=batllie2026
```

Este endpoint verifica si los productos ya existen para evitar duplicaciones, actualiza precios, asigna la categoría `Cajas Premium`, la etiqueta `cata` y asocia la imagen destacada de la caja.
