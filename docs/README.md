# Documentación Técnica: Batllié - Ritual de Cata

Bienvenido al centro de documentación técnica del plugin **Batllié - Ritual de Cata** (`batllie-cata`).

Aquí encontrarás las especificaciones de arquitectura, guías de desarrollo, referencia del shortcode y detalles sobre la integración con WooCommerce.

---

## 📚 Índice de Documentos

1. [**Arquitectura y Flujo del Plugin**](architecture.md)
   - Organización de directorios y componentes.
   - Ciclo de vida y carga de recursos.
   - Motor interactivo de diapositivas (slides) y máquina de estados.
   - Sistema de resolución híbrido de assets (local offline vs. CDN remoto).

2. [**Guía del Shortcode y Parámetros**](shortcode-guide.md)
   - Referencia completa del shortcode `[batllie_cata]`.
   - Atributos aceptados y valores predeterminados.
   - Controles de interactividad: audio ambiental, atajos de teclado y navegación táctil.
   - Diapositiva condicional del ritual del mate.

3. [**Integración con WooCommerce**](woocommerce-integration.md)
   - Categoría *Cajas Premium* y etiqueta `cata`.
   - Tabla de especificaciones técnicas (pesos individuales, netos y rellenos).
   - Redirección del botón final de compra ("Pedir Selección de Cata").
   - Endpoint automatizado de aprovisionamiento de productos.

4. [**Guía de Desarrollo y Buenas Prácticas**](development-guide.md)
   - Estándares de seguridad de WordPress (sanitización, escapado tardío).
   - Configuración en entorno local XAMPP.
   - Flujo de trabajo con Git y despliegues.

---

## ⚡ Inicio Rápido

Para desplegar la experiencia en cualquier página de WordPress:

```text
[batllie_cata]
```

O con parámetros personalizados:

```text
[batllie_cata shop_url="/etiqueta-producto/cata/" accent_color="#d4af37"]
```
