# Batllié - Ritual de Cata (WordPress Plugin)

**Batllié - Ritual de Cata** es un plugin para WordPress que ofrece una experiencia interactiva inmersiva tipo novela visual y protocolo de cata sensorial para los alfajores de autor y yerba mate orgánica de Batllié.

Cuenta con diseño responsive enfocado a móviles y escritorio, arquitectura de audio ambiental relajante, navegación fluida por capítulos sensoriales, integración nativa con WooCommerce y compatibilidad con el personalizador de temas de WordPress.

---

## Características Principales

- **Experiencia Inmersiva:** Formato interactivo por diapositivas (slides) guiadas con barra de progreso y navegación por teclado o táctil.
- **Audio Ambiental:** Reproductor de sonido ambiental integrado con controles de reproducción/silencio y selector de activación en la pantalla de bienvenida.
- **Paso Condicional de Mate:** Diapositiva interactiva con el protocolo paso a paso para cebar el mate tradicional con yerba orgánica Batllié (activable/desactivable en el inicio).
- **Navegación Rápida por Cajas (Capítulos):** Menú interactivo de selección de cajas que permite saltar directamente al capítulo deseado:
  - **Capítulo I: La Chispa Viva** (Cítricos y ligeros: Limón y Batllié Blanco).
  - **Capítulo II: El Equilibrio Clásico** (Las raíces de la casa: Batllié Negro, Chocolate Blanco y Café Suizo).
  - **Capítulo III: Intensidad Absoluta** (Los de autor y complejos: Chocolate Intenso, Nuez al Whisky y Mousse Nutella).
- **Integración con WooCommerce:** Vinculación directa al catálogo de productos bajo la categoría *Cajas Premium* y la etiqueta `cata`.
- **Modo Offline y Carga Local:** Detección automática de recursos locales en la carpeta del plugin (`assets/images/`) para funcionamiento 100% autónomo y rápido en entornos locales (XAMPP).
- **Soporte de Personalización:** Integración con el Customizer de WordPress para cambiar el color de acento y destacados (`emp_cata_accent_color`).

---

## Instalación

1. Descarga el repositorio o clónalo dentro del directorio de plugins de WordPress:
   ```bash
   cd wp-content/plugins/
   git clone https://github.com/alan-pereyra/Batllie-Cata.git batllie-cata
   ```
2. Accede al panel de administración de WordPress (`/wp-admin/plugins.php`).
3. Busca **Batllié - Ritual de Cata** y haz clic en **Activar**.

---

## Uso del Shortcode

Para insertar la experiencia en cualquier página o entrada, utiliza el shortcode:

```text
[batllie_cata]
```

### Parámetros Opcionales

| Parámetro | Valor por defecto | Descripción |
| :--- | :--- | :--- |
| `shop_url` | URL de la etiqueta `/etiqueta-producto/cata/` | URL a la que redirige el botón final "Pedir Selección de Cata". |
| `close_url` | `home_url('/')` | Enlace del botón de cierre (✕). |
| `audio_url` | URL de audio ambiental MP3 | Pista de música ambiental para la experiencia. |
| `audio_title` | `Melodía Ambiental — El Ritual` | Título que describe la pista de audio. |
| `accent_color` | Valor del Customizer o `#e0be80` | Color de acento para la barra de progreso, botones y destacados. |
| `logo_url` | Logo transparente de Batllié | URL del logotipo a mostrar en la diapositiva de inicio. |

**Ejemplo con parámetros personalizados:**
```text
[batllie_cata shop_url="/tienda/" accent_color="#d4af37"]
```

---

## Estructura del Plugin

```text
batllie-cata/
├── assets/
│   └── images/                     # Imágenes locales de alta resolución
│       ├── box.jpg                 # Imagen de las cajas premium
│       ├── mate-1-cream.png        # Paso 1: El Lecho
│       ├── mate-2-cream.png        # Paso 2: La Base
│       ├── mate-3-cream.png        # Paso 3: El Disfrute
│       ├── limon.jpg               # Alfajor Limón
│       ├── batllie-blanco.jpg       # Alfajor Batllié Blanco
│       ├── batllie-negro.jpg        # Alfajor Batllié Negro
│       ├── chocolate-blanco.jpg     # Alfajor Chocolate Blanco
│       ├── cafe-suizo.jpg          # Alfajor Café Suizo
│       ├── chocolate-intenso.jpg    # Alfajor Chocolate Intenso
│       ├── nuez.jpg                # Alfajor Nuez
│       └── mousse-nutella.jpg      # Alfajor Mousse Nutella
├── includes/
│   └── shortcode.php               # Lógica del shortcode, HTML, CSS y JS interactivo
├── batllie-cata.php                # Archivo principal del plugin y endpoints de configuración
├── batllie-logo-transparent.png    # Logotipo oficial Batllié
└── README.md                       # Documentación del proyecto
```

---

## Atribuciones y Licencia

- **Desarrollador:** Alan Pereyra & Batllié
- **Licencia:** GPL-2.0+
- **Sitio Web:** [https://empralidad.com.ar/batllie](https://empralidad.com.ar/batllie)
