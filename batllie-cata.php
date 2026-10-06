<?php
/**
 * Plugin Name: Batllié - Ritual de Cata
 * Plugin URI: https://empralidad.com.ar/batllie
 * Description: Experiencia interactiva tipo novela visual y protocolo de cata para los alfajores de autor Batllié. Proporciona el shortcode [batllie_cata].
 * Version: 1.2.0
 * Author: Antigravity & Batllié
 * Author URI: https://empralidad.com.ar/batllie
 * License: GPL-2.0+
 * Text Domain: batllie-cata
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';

/**
 * Register CSS styles and JS script
 */
add_action('wp_enqueue_scripts', function() {
    wp_register_style('batllie-cata-styles', plugins_url('assets/css/styles.css', __FILE__), array(), '1.2.1');
    wp_register_script('batllie-cata-script', plugins_url('assets/js/batllie-cata.js', __FILE__), array(), '1.2.1', true);
});

/**
 * Endpoint to create/update the 3 Tasting Box products in Cajas Premium with tag 'cata'
 */
add_action('init', function() {
    if (isset($_GET['batllie_setup_cata_products']) && $_GET['batllie_setup_cata_products'] === 'batllie2026') {
        header('Content-Type: application/json; charset=utf-8');

        // 1. Ensure tag 'cata' exists
        $tag = get_term_by('slug', 'cata', 'product_tag');
        if (!$tag) {
            $inserted = wp_insert_term('Cata', 'product_tag', array(
                'slug' => 'cata',
                'description' => 'Selecciones exclusivas de nuestro Ritual de Cata Batllié'
            ));
            if (!is_wp_error($inserted)) {
                $tag = get_term($inserted['term_id'], 'product_tag');
            }
        }
        $tag_url = $tag ? get_term_link($tag) : home_url('/etiqueta-producto/cata/');

        // 2. Ensure Category 17 (Cajas Premium) exists
        $cat = get_term_by('id', 17, 'product_cat');
        if (!$cat) {
            $cat = get_term_by('slug', 'cajas-premium', 'product_cat');
        }
        $cat_id = $cat ? $cat->term_id : 17;

        // 3. Find box image attachment ID
        $box_img_id = 0;
        $attachments = get_posts(array(
            'post_type' => 'attachment',
            'posts_per_page' => 1,
            's' => 'box-1790013939'
        ));
        if (!empty($attachments)) {
            $box_img_id = $attachments[0]->ID;
        } else {
            $p35_thumb = get_post_thumbnail_id(35);
            if ($p35_thumb) $box_img_id = $p35_thumb;
        }

        // Table styles matching Batllié theme
        $tableStyle = 'width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem; line-height: 1.5; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden;';
        $thStyle = 'padding: 12px 14px; font-weight: 600; background-color: #162a1f; color: #fdffdd; border-bottom: 1px solid #162a1f;';
        $tdStyle = 'padding: 12px 14px; border-bottom: 1px solid #f1f5f9; color: #475569;';
        $tdBold = 'padding: 12px 14px; border-bottom: 1px solid #f1f5f9; font-weight: 600; color: #162a1f;';
        $tfootTd = 'padding: 12px 14px; font-weight: 600; color: #162a1f; background-color: #f1f5f3; border-top: 2px solid #cbd5e1;';
        $infoBox = 'background: #fdfdf9; border-left: 4px solid #162a1f; padding: 14px 18px; border-radius: 4px; font-size: 0.9rem; color: #4a5568; line-height: 1.6; margin-top: 15px;';

        // 4. Products data
        $products_data = array(
            array(
                'slug' => 'caja-degustacion-seleccion-1-chispa-viva',
                'title' => 'Caja Degustación Selección I: La Chispa Viva',
                'excerpt' => '<p>Selección especial del Ritual de Cata Batllié — Capítulo I. Experiencia sensorial fresca y luminosa con nuestros alfajores de Limón y Batllié Blanco (6 unidades).</p>',
                'content' => '<p>La <strong>Selección I: La Chispa Viva</strong> inaugura el Ritual de Cata Batllié con una propuesta vibrante, fresca y equilibrada. Esta caja ha sido diseñada para despertar las papilas gustativas a través del contrapunto entre notas cítricas luminosas, dulce de leche repostero tradicional y coberturas artesanales de alta gama.</p>
<div class="batllie-product-details" style="margin: 30px 0;">
    <h3 style="color: #162a1f; font-size: 1.25rem; margin-bottom: 14px; font-weight: 700; border-bottom: 2px solid #e0be80; padding-bottom: 6px;">
        Contenido de la Selección &amp; Especificaciones
    </h3>
    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; margin-bottom: 15px;">
        <table class="batllie-table-specs" style="' . $tableStyle . '">
            <thead>
                <tr>
                    <th style="' . $thStyle . '">Variedad / Gusto</th>
                    <th style="' . $thStyle . '">Relleno &amp; Masa</th>
                    <th style="' . $thStyle . '">Cobertura</th>
                    <th style="' . $thStyle . ' text-align: right; white-space: nowrap;">Peso Individual</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background-color: #ffffff;">
                    <td style="' . $tdBold . '">1- Alfajor de Limón (x3)</td>
                    <td style="' . $tdStyle . '">Masa sableé artesanal de textura delicada; curd fresco de limón con notas cítricas balanceadas.</td>
                    <td style="' . $tdStyle . '">Baño envolvente de chocolate semiamargo.</td>
                    <td style="' . $tdBold . ' text-align: right;">~75 g c/u</td>
                </tr>
                <tr style="background-color: #f8fafc;">
                    <td style="' . $tdBold . '">2- Batllié Blanco (x3)</td>
                    <td style="' . $tdStyle . '">Masa de cacao fino con ralladura fresca de naranja; abundante dulce de leche repostero tradicional.</td>
                    <td style="' . $tdStyle . '">Merengue suizo batido a mano, crujiente por fuera y tierno por dentro.</td>
                    <td style="' . $tdBold . ' text-align: right;">~75 g c/u</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="' . $tfootTd . '"><strong>Peso Neto Total Estimado</strong> (6 unidades artesanales)</td>
                    <td style="' . $tfootTd . ' text-align: right;"><strong>~450 g</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div style="' . $infoBox . '">
        <p style="margin: 0 0 6px 0;"><strong>📦 Presentación:</strong> Caja rígida de colección Batllié con 6 unidades seleccionadas en envoltorio individual de máxima frescura.</p>
        <p style="margin: 0;"><strong>🌿 Maridaje sugerido:</strong> Mate amargo tradicional con yerba orgánica Batllié a 75°C - 80°C o infusión de té verde cítrico.</p>
    </div>
</div>',
                'price' => '13500',
                'regular_price' => '15000'
            ),

            array(
                'slug' => 'caja-degustacion-seleccion-2-equilibrio-clasico',
                'title' => 'Caja Degustación Selección II: El Equilibrio Clásico',
                'excerpt' => '<p>Selección especial del Ritual de Cata Batllié — Capítulo II. El corazón y las raíces de la casa: Batllié Negro, Chocolate Blanco y Café Suizo (6 unidades).</p>',
                'content' => '<p>La <strong>Selección II: El Equilibrio Clásico</strong> rinde tributo a las raíces y recetas insignia de la casa Batllié. Un compendio de texturas y perfiles donde el cacao macerado al coñac añejo, el dulce de leche artesanal de punto óptimo y el merengue suizo de 48 horas de reposo logran la máxima armonía alfajorera.</p>
<div class="batllie-product-details" style="margin: 30px 0;">
    <h3 style="color: #162a1f; font-size: 1.25rem; margin-bottom: 14px; font-weight: 700; border-bottom: 2px solid #e0be80; padding-bottom: 6px;">
        Contenido de la Selección &amp; Especificaciones
    </h3>
    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; margin-bottom: 15px;">
        <table class="batllie-table-specs" style="' . $tableStyle . '">
            <thead>
                <tr>
                    <th style="' . $thStyle . '">Variedad / Gusto</th>
                    <th style="' . $thStyle . '">Relleno &amp; Masa</th>
                    <th style="' . $thStyle . '">Cobertura</th>
                    <th style="' . $thStyle . ' text-align: right; white-space: nowrap;">Peso Individual</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background-color: #ffffff;">
                    <td style="' . $tdBold . '">1- Batllié Negro (x2)</td>
                    <td style="' . $tdStyle . '">Masa de cacao seleccionada con sutiles toques de coñac añejo; dulce de leche repostero artesanal.</td>
                    <td style="' . $tdStyle . '">Baño de chocolate con leche refinado y sedoso.</td>
                    <td style="' . $tdBold . ' text-align: right;">~75 g c/u</td>
                </tr>
                <tr style="background-color: #f8fafc;">
                    <td style="' . $tdBold . '">2- Chocolate Blanco (x2)</td>
                    <td style="' . $tdStyle . '">Masa clásica al cacao de textura suave y quebradiza; abundante dulce de leche de punto sedoso.</td>
                    <td style="' . $tdStyle . '">Fino chocolate blanco templado de acabado aterciopelado.</td>
                    <td style="' . $tdBold . ' text-align: right;">~75 g c/u</td>
                </tr>
                <tr style="background-color: #ffffff;">
                    <td style="' . $tdBold . '">3- Café Suizo (x2)</td>
                    <td style="' . $tdStyle . '">Masa perfumada con notas de café de autor tostado a punto medio; corazón cremoso de dulce de leche.</td>
                    <td style="' . $tdStyle . '">Merengue suizo artesanal con 48 horas de reposo para una crocancia sutil.</td>
                    <td style="' . $tdBold . ' text-align: right;">~75 g c/u</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="' . $tfootTd . '"><strong>Peso Neto Total Estimado</strong> (6 unidades artesanales)</td>
                    <td style="' . $tfootTd . ' text-align: right;"><strong>~450 g</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div style="' . $infoBox . '">
        <p style="margin: 0 0 6px 0;"><strong>📦 Presentación:</strong> Caja rígida de colección Batllié con 6 unidades seleccionadas en envoltorio individual de máxima frescura.</p>
        <p style="margin: 0;"><strong>🌿 Maridaje sugerido:</strong> Mate amargo con yerba orgánica Batllié a 75°C - 80°C o café espresso de grano tostado natural.</p>
    </div>
</div>',
                'price' => '13500',
                'regular_price' => '15000'
            ),

            array(
                'slug' => 'caja-degustacion-seleccion-3-intensidad-absoluta',
                'title' => 'Caja Degustación Selección III: Intensidad Absoluta',
                'excerpt' => '<p>Selección especial del Ritual de Cata Batllié — Capítulo III. Para paladares audaces: Chocolate Intenso, Nuez al Whisky y Mousse Nutella (6 unidades).</p>',
                'content' => '<p>La <strong>Selección III: Intensidad Absoluta</strong> representa el punto culminante del Ritual de Cata Batllié. Una propuesta audaz y sofisticada creada para paladares exigentes que disfrutan de perfiles profundos, densos y complejos, desde el cacao amargo más puro hasta la calidez del whisky añejo y el corazón fundente de avellanas.</p>
<div class="batllie-product-details" style="margin: 30px 0;">
    <h3 style="color: #162a1f; font-size: 1.25rem; margin-bottom: 14px; font-weight: 700; border-bottom: 2px solid #e0be80; padding-bottom: 6px;">
        Contenido de la Selección &amp; Especificaciones
    </h3>
    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; margin-bottom: 15px;">
        <table class="batllie-table-specs" style="' . $tableStyle . '">
            <thead>
                <tr>
                    <th style="' . $thStyle . '">Variedad / Gusto</th>
                    <th style="' . $thStyle . '">Relleno &amp; Masa</th>
                    <th style="' . $thStyle . '">Cobertura</th>
                    <th style="' . $thStyle . ' text-align: right; white-space: nowrap;">Peso Individual</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background-color: #ffffff;">
                    <td style="' . $tdBold . '">1- Chocolate Intenso (x2)</td>
                    <td style="' . $tdStyle . '">Masa de cacao amargo profundo macerada al coñac; dulce de leche repostero premium.</td>
                    <td style="' . $tdStyle . '">Baño de chocolate semiamargo 70% de carácter inquebrantable.</td>
                    <td style="' . $tdBold . ' text-align: right;">~80 g c/u</td>
                </tr>
                <tr style="background-color: #f8fafc;">
                    <td style="' . $tdBold . '">2- Nuez &amp; Whisky (x2)</td>
                    <td style="' . $tdStyle . '">Masa rústica de algarroba y abundantes nueces pecán maceradas al whisky; dulce de leche firme.</td>
                    <td style="' . $tdStyle . '">Manta de fino chocolate blanco templado.</td>
                    <td style="' . $tdBold . ' text-align: right;">~80 g c/u</td>
                </tr>
                <tr style="background-color: #ffffff;">
                    <td style="' . $tdBold . '">3- Mousse Nutella (x2)</td>
                    <td style="' . $tdStyle . '">Base noble de cacao al coñac; mousse de chocolate untuosa con corazón desbordante de Nutella pura.</td>
                    <td style="' . $tdStyle . '">Baño de chocolate semiamargo crocante de primera línea.</td>
                    <td style="' . $tdBold . ' text-align: right;">~80 g c/u</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="' . $tfootTd . '"><strong>Peso Neto Total Estimado</strong> (6 unidades artesanales)</td>
                    <td style="' . $tfootTd . ' text-align: right;"><strong>~480 g</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div style="' . $infoBox . '">
        <p style="margin: 0 0 6px 0;"><strong>📦 Presentación:</strong> Caja rígida de colección Batllié con 6 unidades seleccionadas en envoltorio individual de máxima frescura.</p>
        <p style="margin: 0;"><strong>🌿 Maridaje sugerido:</strong> Mate amargo con yerba orgánica Batllié a 75°C - 80°C o café de tueste oscuro / destilados añejos.</p>
    </div>
</div>',
                'price' => '13500',
                'regular_price' => '15000'
            )
        );

        $results = array();
        foreach ($products_data as $data) {
            $existing = get_page_by_path($data['slug'], OBJECT, 'product');
            if (!$existing) {
                $found = get_posts(array(
                    'post_type' => 'product',
                    'title' => $data['title'],
                    'posts_per_page' => 1,
                    'post_status' => 'any'
                ));
                if (!empty($found)) {
                    $existing = $found[0];
                }
            }

            $post_arr = array(
                'post_title'   => $data['title'],
                'post_name'    => $data['slug'],
                'post_content' => $data['content'],
                'post_excerpt' => $data['excerpt'],
                'post_status'  => 'publish',
                'post_type'    => 'product',
            );

            if ($existing) {
                $post_arr['ID'] = $existing->ID;
                $product_id = wp_update_post($post_arr);
                $action = 'updated';
            } else {
                $product_id = wp_insert_post($post_arr);
                $action = 'created';
            }

            if (!is_wp_error($product_id)) {
                // Assign category and tag
                wp_set_object_terms($product_id, array($cat_id), 'product_cat');
                wp_set_object_terms($product_id, array('cata'), 'product_tag', true);

                // Set WooCommerce metadata
                update_post_meta($product_id, '_visibility', 'visible');
                update_post_meta($product_id, '_stock_status', 'instock');
                update_post_meta($product_id, '_regular_price', $data['regular_price']);
                update_post_meta($product_id, '_sale_price', $data['price']);
                update_post_meta($product_id, '_price', $data['price']);

                // Set featured image
                if ($box_img_id > 0) {
                    set_post_thumbnail($product_id, $box_img_id);
                }

                $results[] = array(
                    'id' => $product_id,
                    'title' => $data['title'],
                    'action' => $action,
                    'url' => get_permalink($product_id),
                    'categories' => wp_get_post_terms($product_id, 'product_cat', array('fields' => 'names')),
                    'tags' => wp_get_post_terms($product_id, 'product_tag', array('fields' => 'names')),
                );
            } else {
                $results[] = array(
                    'title' => $data['title'],
                    'error' => $product_id->get_error_message()
                );
            }
        }

        echo json_encode(array(
            'success' => true,
            'tag_url' => $tag_url,
            'products' => $results
        ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }
});

/**
 * Endpoint to create/update the "Experiencia matera" page and add it to the primary navigation menu
 */
add_action('init', function() {
    if (isset($_GET['batllie_setup_matera_page']) && $_GET['batllie_setup_matera_page'] === 'batllie2026') {
        header('Content-Type: application/json; charset=utf-8');

        $page_slug = 'experiencia-matera';
        $page_title = 'Experiencia matera';

        // 1. Check if page already exists
        $existing_page = get_page_by_path($page_slug);
        if (!$existing_page) {
            $found_pages = get_posts(array(
                'name' => $page_slug,
                'post_type' => 'page',
                'post_status' => 'any',
                'posts_per_page' => 1
            ));
            if (!empty($found_pages)) {
                $existing_page = $found_pages[0];
            }
        }

        $page_data = array(
            'post_title'   => $page_title,
            'post_name'    => $page_slug,
            'post_content' => '[batllie_matera]',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        );

        if ($existing_page) {
            $page_data['ID'] = $existing_page->ID;
            $page_id = wp_update_post($page_data);
            $page_action = 'updated';
        } else {
            $page_id = wp_insert_post($page_data);
            $page_action = 'created';
        }

        $page_url = get_permalink($page_id);

        // 2. Add to navigation menus
        // Find candidate menus:
        // - Menu assigned to 'superior' location
        // - Menu 21 ('Menu Principal')
        // - Any menu named 'Menu Principal'
        $menu_results = array();
        $target_menu_ids = array();

        $locations = get_nav_menu_locations();
        if (!empty($locations['superior'])) {
            $target_menu_ids[] = (int)$locations['superior'];
        }

        $all_menus = wp_get_nav_menus();
        if (!empty($all_menus)) {
            foreach ($all_menus as $menu) {
                if (stripos($menu->name, 'Principal') !== false || $menu->slug === 'menu-principal' || $menu->term_id == 21) {
                    $target_menu_ids[] = (int)$menu->term_id;
                }
            }
        }
        $target_menu_ids = array_values(array_unique($target_menu_ids));

        foreach ($target_menu_ids as $m_id) {
            $menu_obj = wp_get_nav_menu_object($m_id);
            if (!$menu_obj) continue;

            $items = wp_get_nav_menu_items($m_id);
            $already_exists = false;
            $existing_item_id = 0;

            if (!empty($items)) {
                foreach ($items as $item) {
                    if ((int)$item->object_id === (int)$page_id && $item->object === 'page') {
                        $already_exists = true;
                        $existing_item_id = $item->ID;
                        break;
                    }
                    if (trim(mb_strtolower($item->title)) === 'experiencia matera') {
                        $already_exists = true;
                        $existing_item_id = $item->ID;
                        break;
                    }
                }
            }

            if ($already_exists) {
                $menu_results[] = array(
                    'menu_id' => $m_id,
                    'menu_name' => $menu_obj->name,
                    'status' => 'already_present',
                    'item_id' => $existing_item_id
                );
            } else {
                // Determine position: place right after 'El Ritual de Cata' if present
                $menu_order = 0;
                if (!empty($items)) {
                    foreach ($items as $item) {
                        if (stripos($item->title, 'Cata') !== false) {
                            $menu_order = $item->menu_order + 1;
                        }
                    }
                }

                $item_data = array(
                    'menu-item-title'     => $page_title,
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $page_id,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                );
                if ($menu_order > 0) {
                    $item_data['menu-item-position'] = $menu_order;
                }

                $new_item_id = wp_update_nav_menu_item($m_id, 0, $item_data);
                $menu_results[] = array(
                    'menu_id' => $m_id,
                    'menu_name' => $menu_obj->name,
                    'status' => is_wp_error($new_item_id) ? 'error' : 'added',
                    'item_id' => is_wp_error($new_item_id) ? $new_item_id->get_error_message() : $new_item_id
                );
            }
        }

        echo json_encode(array(
            'success' => true,
            'page' => array(
                'id' => $page_id,
                'title' => $page_title,
                'slug' => $page_slug,
                'action' => $page_action,
                'url' => $page_url
            ),
            'menus' => $menu_results
        ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }
});

