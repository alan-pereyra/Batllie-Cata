# Guía del Shortcode y Parámetros

El shortcode `[batllie_cata]` (y su alias `[emp_cata]`) es el punto de entrada principal para desplegar la experiencia interactiva en cualquier página o plantilla de WordPress.

---

## 1. Atributos del Shortcode

| Atributo | Tipo | Valor por Defecto | Descripción |
| :--- | :--- | :--- | :--- |
| `shop_url` | String | Dinámico (`/etiqueta-producto/cata/`) | Destino del botón de compra final "Pedir Selección de Cata". Si no se indica, resuelve automáticamente el enlace de la etiqueta `cata`. |
| `close_url` | String | `home_url('/')` | URL a la que se redirige cuando el usuario hace clic en el botón de salida (✕). |
| `audio_url` | String | Pista acústica ambiental | Enlace directo al archivo MP3 de fondo. |
| `audio_title` | String | `Melodía Ambiental — El Ritual` | Etiqueta de la pista mostrada en la interfaz. |
| `accent_color` | Hex | `#e0be80` (o valor Customizer) | Color de acento para la barra de progreso, bordes activos y botones primarios. |
| `color_acento` | Hex | Alias de `accent_color` | Permite usar la nomenclatura en español. |
| `color` | Hex | Alias de `accent_color` | Soporte para sintaxis simplificada. |
| `logo` / `logo_url` | String | Logo transparente oficial | URL personalizada del logotipo a mostrar en la portada. |

---

## 2. Ejemplos de Implementación

### Ejemplo Básico
```text
[batllie_cata]
```

### Ejemplo con URL de Tienda y Color Dorado
```text
[batllie_cata shop_url="https://empralidad.com.ar/batllie/etiqueta-producto/cata/" accent_color="#d4af37"]
```

### Ejemplo Embebido en PHP (Plantilla de Tema)
```php
<?php echo do_shortcode('[batllie_cata]'); ?>
```

---

## 3. Interactividad y Atajos de Teclado

La experiencia está diseñada tanto para interacción táctil como para navegación de escritorio mediante atajos de teclado:

- **Flecha Derecha (`ArrowRight`) o Barra Espaciadora (`Space`):** Avanza a la siguiente diapositiva.
- **Flecha Izquierda (`ArrowLeft`):** Retrocede a la diapositiva anterior.
- **Salto directo desde Cajas:** Al hacer clic sobre cualquiera de las 3 tarjetas de cajas en la diapositiva *"Experiencia Completa"*, la vista navega inmediatamente al capítulo seleccionado:
  - Tarjeta I $\rightarrow$ Capítulo 1: La Chispa Viva.
  - Tarjeta II $\rightarrow$ Capítulo 2: El Equilibrio Clásico.
  - Tarjeta III $\rightarrow$ Capítulo 3: Intensidad Absoluta.

---

## 4. Diapositivas y Estructura del Ritual

1. **Portada / Bienvenida:**
   - Presentación de la casa y propósito de la experiencia.
   - Conmutador de música ambiental.
   - Conmutador de mate tradicional.
2. **El Ritual del Mate (Opcional):**
   - Protocolo de preparación en 3 pasos: *I. El Lecho*, *II. La Base*, *III. El Disfrute*.
3. **Experiencia Completa (Cajas):**
   - Presentación de la arquitectura de sabores y selección rápida de capítulo.
4. **Capítulo I: La Chispa Viva:**
   - Degustación de alfajores frescos y ligeros (Limón y Batllié Blanco).
5. **Capítulo II: El Equilibrio Clásico:**
   - Las recetas insignia de la casa (Batllié Negro, Chocolate Blanco y Café Suizo).
6. **Capítulo III: Intensidad Absoluta:**
   - Alfajores de autor y perfiles complejos (Chocolate Intenso, Nuez al Whisky y Mousse Nutella).
7. **Cierre y Llamada a la Acción:**
   - Filosofía de micro-lotes de Batllié.
   - Botón directo hacia la selección de productos de cata (`shop_url`).
   - Botón para reiniciar la experiencia.
