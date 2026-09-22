# Guía de Desarrollo y Buenas Prácticas

Esta guía está destinada a desarrolladores que mantengan, extiendan o desplieguen el plugin **Batllié - Ritual de Cata**.

---

## 1. Estándares de Seguridad de WordPress

### A. Escapado Tardío en Salida (Late Escaping)
Todo dato impreso en las vistas debe pasar por la función de escape apropiada justo en el momento de la salida:
- **URLs:** `<?php echo esc_url($url); ?>`
- **Atributos HTML:** `<?php echo esc_attr($uid); ?>`
- **Textos para scripts JavaScript:** `<?php echo esc_js($uid); ?>`
- **Textos HTML:** `<?php echo esc_html($texto); ?>`

### B. Sanitización en Entrada
Todos los atributos pasados a través del shortcode se validan con `shortcode_atts()` y funciones de saneamiento de colores:
```php
$custom_accent = sanitize_hex_color($atts['accent_color']) ?: $atts['accent_color'];
```

---

## 2. Desarrollo en Entorno Local (XAMPP)

Para trabajar localmente en XAMPP:

1. **Ubicación del repositorio:**
   ```text
   C:\xampp\htdocs\wp-content\plugins\batllie-cata\
   ```
2. **Activación de Apache y MySQL:**
   - Abre el **XAMPP Control Panel** e inicia ambos servicios.
3. **Carga de recursos locales:**
   - Todas las imágenes se sirven desde `assets/images/` mediante `plugins_url()`, eliminando cualquier dependencia de red externa.

---

## 3. Control de Versiones con Git y GitHub

El plugin está conectado al repositorio oficial en GitHub:
👉 **`https://github.com/alan-pereyra/Batllie-Cata.git`**

### Flujo para Publicar Cambios:
```bash
# 1. Comprobar estado
git status

# 2. Agregar cambios
git add .

# 3. Crear commit descriptivo
git commit -m "feat: descripción del cambio"

# 4. Enviar a GitHub
git push origin master
```

---

## 4. Despliegue en Servidores de Producción

Para instalar o actualizar el plugin en un servidor remoto de WordPress:
1. Generar un archivo comprimido `.zip` del directorio `batllie-cata/`.
2. En el panel de administración de WordPress, ir a **Plugins $\rightarrow$ Añadir nuevo $\rightarrow$ Subir plugin**.
3. Seleccionar el archivo `batllie-cata.zip` y hacer clic en **Instalar ahora** (y confirmar la sustitución si ya existe una versión previa).
