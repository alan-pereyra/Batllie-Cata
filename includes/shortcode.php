<?php
/**
 * Shortcode: El Ritual de Cata / Experiencia Matera (Batllié)
 * Standalone Plugin Implementation
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('batllie_cata_shortcode')) {
    function batllie_cata_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'mode'             => 'cata',
            'audio_url'        => 'https://cdn.pixabay.com/download/audio/2022/05/27/audio_1808fbf07a.mp3?filename=acoustic-guitar-ambient-relax-112191.mp3',
            'audio_title'      => '',
            'shop_url'         => '',
            'close_url'        => home_url('/'),
            'logo'             => '',
            'logo_url'         => '',
            'logo_clean_url'   => 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/batllie-logo-transparent.png',
            'title'            => '',
            'accent_color'     => '',
            'color_acento'     => '',
            'color'            => '',
        ), $atts, 'batllie_cata');

        if (isset($_GET['mode']) && $_GET['mode'] === 'matera') {
            $atts['mode'] = 'matera';
        } elseif (isset($_GET['experiencia']) && $_GET['experiencia'] === 'matera') {
            $atts['mode'] = 'matera';
        }

        $is_matera = ($atts['mode'] === 'matera');

        if (empty($atts['title'])) {
            $atts['title'] = $is_matera ? 'Experiencia matera' : 'El Ritual de Cata';
        }

        if (empty($atts['audio_title'])) {
            $atts['audio_title'] = $is_matera ? 'Melodía Ambiental — Experiencia Matera' : 'Melodía Ambiental — El Ritual';
        }

        // Dynamic shop_url: resolve to yerba product for matera, or 'cata' tag for classic cata
        if (empty($atts['shop_url'])) {
            if ($is_matera) {
                $atts['shop_url'] = 'https://batllie.com.ar/producto/yerba-mate-organica/';
            } else {
                $tag = get_term_by('slug', 'cata', 'product_tag');
                if ($tag && !is_wp_error($tag)) {
                    $atts['shop_url'] = get_term_link($tag);
                } else {
                    $atts['shop_url'] = home_url('/etiqueta-producto/cata/');
                }
            }
        }

        // Accent color resolution
        $custom_accent = '';
        if (!empty($atts['accent_color'])) {
            $custom_accent = sanitize_hex_color($atts['accent_color']) ?: $atts['accent_color'];
        } elseif (!empty($atts['color_acento'])) {
            $custom_accent = sanitize_hex_color($atts['color_acento']) ?: $atts['color_acento'];
        } elseif (!empty($atts['color'])) {
            $custom_accent = sanitize_hex_color($atts['color']) ?: $atts['color'];
        } elseif (function_exists('get_theme_mod') && get_theme_mod('emp_cata_accent_color')) {
            $custom_accent = get_theme_mod('emp_cata_accent_color');
        }

        // Local vs Remote Asset Resolver
        $asset = function($filename, $remote_url = '') {
            if (function_exists('get_template_directory') && file_exists(get_template_directory() . '/img/cata/' . $filename)) {
                return get_template_directory_uri() . '/img/cata/' . $filename;
            }
            if (file_exists(plugin_dir_path(__FILE__) . '../assets/images/' . $filename)) {
                return plugins_url('../assets/images/' . $filename, __FILE__);
            }
            if (file_exists(plugin_dir_path(__FILE__) . 'assets/images/' . $filename)) {
                return plugins_url('assets/images/' . $filename, __FILE__);
            }
            if (defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/batllie-cata/assets/images/' . $filename)) {
                return plugins_url('batllie-cata/assets/images/' . $filename);
            }
            return $remote_url;
        };

        // Logo resolution
        $logo_clean_local = '';
        if (function_exists('get_template_directory') && file_exists(get_template_directory() . '/img/cata/batllie-logo-transparent.png')) {
            $logo_clean_local = get_template_directory_uri() . '/img/cata/batllie-logo-transparent.png';
        } elseif (file_exists(plugin_dir_path(__FILE__) . '../batllie-logo-transparent.png')) {
            $logo_clean_local = plugins_url('../batllie-logo-transparent.png', __FILE__);
        } elseif (defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/batllie-cata/batllie-logo-transparent.png')) {
            $logo_clean_local = plugins_url('batllie-cata/batllie-logo-transparent.png');
        } else {
            $logo_clean_local = $atts['logo_clean_url'];
        }
        $logo_url = !empty($atts['logo']) ? $atts['logo'] : (!empty($atts['logo_url']) ? $atts['logo_url'] : $logo_clean_local);

        // Experiencia Matera: Reemplazar por la imagen del navbar configurada en el Customizer del tema (emp_components_nav_logo)
        if ($is_matera && empty($atts['logo']) && empty($atts['logo_url'])) {
            $navbar_logo_url = '';
            if (function_exists('get_theme_mod')) {
                $nav_logo_id = get_theme_mod('emp_components_nav_logo');
                if ($nav_logo_id) {
                    $navbar_logo_url = is_numeric($nav_logo_id) ? wp_get_attachment_url($nav_logo_id) : $nav_logo_id;
                }
                if (!$navbar_logo_url) {
                    $custom_logo_id = get_theme_mod('custom_logo');
                    if ($custom_logo_id) {
                        $navbar_logo_url = is_numeric($custom_logo_id) ? wp_get_attachment_url($custom_logo_id) : $custom_logo_id;
                    }
                }
            }
            if (!$navbar_logo_url) {
                $navbar_logo_url = 'https://batllie.com.ar/wp-content/uploads/2026/10/1791251556643.jpg';
            }
            if (!empty($navbar_logo_url)) {
                $logo_url = $navbar_logo_url;
            }
        }

        // CSS and JS URLs
        $css_url = '';
        if (file_exists(plugin_dir_path(__FILE__) . '../assets/css/styles.css')) {
            $css_url = plugins_url('../assets/css/styles.css', __FILE__);
        } elseif (file_exists(plugin_dir_path(__FILE__) . 'assets/css/styles.css')) {
            $css_url = plugins_url('assets/css/styles.css', __FILE__);
        } elseif (function_exists('get_template_directory') && file_exists(get_template_directory() . '/plugins/batllie-cata/assets/css/styles.css')) {
            $css_url = get_template_directory_uri() . '/plugins/batllie-cata/assets/css/styles.css';
        } elseif (defined('WP_PLUGIN_URL')) {
            $css_url = plugins_url('batllie-cata/assets/css/styles.css');
        }

        $js_url = '';
        if (file_exists(plugin_dir_path(__FILE__) . '../assets/js/batllie-cata.js')) {
            $js_url = plugins_url('../assets/js/batllie-cata.js', __FILE__);
        } elseif (file_exists(plugin_dir_path(__FILE__) . 'assets/js/batllie-cata.js')) {
            $js_url = plugins_url('assets/js/batllie-cata.js', __FILE__);
        } elseif (function_exists('get_template_directory') && file_exists(get_template_directory() . '/plugins/batllie-cata/assets/js/batllie-cata.js')) {
            $js_url = get_template_directory_uri() . '/plugins/batllie-cata/assets/js/batllie-cata.js';
        } elseif (defined('WP_PLUGIN_URL')) {
            $js_url = plugins_url('batllie-cata/assets/js/batllie-cata.js');
        }

        // Enqueue styles and scripts
        if ($css_url) {
            wp_enqueue_style('batllie-cata-styles', $css_url, array(), '1.3.2');
        }
        if ($js_url) {
            wp_enqueue_script('batllie-cata-script', $js_url, array(), '1.3.2', true);
        }

        $uid = 'batllie_cata_' . wp_rand(1000, 9999);

        ob_start();
        ?>
        <?php if ($css_url): ?>
            <!-- External Stylesheet (styles.css) -->
            <link rel="stylesheet" id="<?php echo esc_attr($uid); ?>-css" href="<?php echo esc_url($css_url); ?>?ver=1.3.2" type="text/css" media="all" />
        <?php endif; ?>

        <?php if (!empty($custom_accent)): ?>
            <!-- Custom Accent Variables -->
            <style>
                #<?php echo esc_attr($uid); ?>.batllie-cata-root {
                    --batllie-accent: <?php echo esc_attr($custom_accent); ?>;
                    --batllie-accent-hover: <?php echo esc_attr($custom_accent); ?>;
                    --batllie-btn-shop-text: #ffffff;
                    --batllie-btn-shop-text-hover: #ffffff;
                }
            </style>
        <?php endif; ?>

        <div id="<?php echo esc_attr($uid); ?>" class="batllie-cata-root" data-mode="<?php echo esc_attr($atts['mode']); ?>" data-audio="<?php echo esc_url($atts['audio_url']); ?>" data-audio-title="<?php echo esc_attr($atts['audio_title']); ?>">
            <!-- Header -->
            <div class="batllie-cata-header">
                <div class="batllie-cata-header-title">
                    <?php echo esc_html($atts['title']); ?>
                </div>
                <div class="batllie-cata-top-actions">
                    <button type="button" class="batllie-cata-audio-btn" aria-label="Control de Audio">
                        <span class="batllie-cata-audio-waves">
                            <span class="batllie-cata-wave-bar"></span>
                            <span class="batllie-cata-wave-bar"></span>
                            <span class="batllie-cata-wave-bar"></span>
                        </span>
                        <span class="batllie-cata-audio-text">Música</span>
                    </button>
                    <a href="<?php echo esc_url($atts['close_url']); ?>" class="batllie-cata-close-btn" aria-label="Cerrar y volver">
                        <span>✕</span>
                    </a>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="batllie-cata-progress-bar">
                <div class="batllie-cata-progress-fill"></div>
            </div>

            <!-- Viewport -->
            <div class="batllie-cata-viewport">
                <?php if ($is_matera): ?>
                    <!-- ========================================== -->
                    <!-- MODO EXPERIENCIA MATERA                    -->
                    <!-- ========================================== -->

                    <!-- Slide 0: Intro Matera -->
                    <div class="batllie-cata-slide active" data-slide-id="intro">
                        <div class="batllie-cata-intro">
                            <?php if (!empty($logo_url)): ?>
                                <img src="<?php echo esc_url($logo_url); ?>" alt="Batllié" class="batllie-cata-intro-logo-clean" />
                            <?php endif; ?>
                            <h3 class="batllie-cata-intro-subtitle">Experiencia matera</h3>
                            <p class="batllie-cata-intro-desc">Viví la tradición y el sentimiento argentino, eso que nos conecta en armonía y nos hace vivir el plenitud</p>

                            <div class="batllie-cata-toggles-group">
                                <div class="batllie-cata-toggle-box">
                                    <span class="batllie-cata-toggle-label">Música ambiental durante la experiencia:</span>
                                    <label class="batllie-cata-switch" aria-label="Activar música ambiental">
                                        <input type="checkbox" class="batllie-cata-sound-toggle" checked />
                                        <span class="batllie-cata-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <button type="button" class="batllie-cata-btn-start">Iniciar Experiencia</button>
                        </div>
                    </div>

                    <!-- Slide 1: Experiencia Completa con Scroll hacia abajo de Moliendas -->
                    <div class="batllie-cata-slide" data-slide-id="yerba">
                        <div class="batllie-cata-boxes-container">
                            <h2 class="batllie-cata-section-title">Experiencia completa</h2>
                            <div class="batllie-cata-section-subtitle">NUESTRAS MOLIENDAS Y BLENDS DE YERBA AGROECOLÓGICA</div>

                            <!-- 4 Cards Grid (Sin texto abajo) -->
                            <div class="batllie-cata-boxes-grid is-4-cols">
                                <div class="batllie-cata-box-card" data-scroll-to="molienda-1">
                                    <div class="batllie-cata-box-num">I</div>
                                    <img src="<?php echo esc_url($asset('yerba-bag.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/watermarked_img_11158224245304405509.jpg')); ?>" alt="Tradicional fina" class="batllie-cata-box-img" />
                                    <div class="batllie-cata-box-name">Tradicional<br>fina</div>
                                    <div class="batllie-cata-box-sub">(con palo y molienda equilibrada)</div>
                                    <button type="button" class="batllie-cata-card-scroll-btn" data-scroll-to="molienda-1">Ver molienda ↓</button>
                                </div>
                                <div class="batllie-cata-box-card" data-scroll-to="molienda-2">
                                    <div class="batllie-cata-box-num">II</div>
                                    <img src="<?php echo esc_url($asset('yerba-bag.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/watermarked_img_11158224245304405509.jpg')); ?>" alt="Despalada gruesa" class="batllie-cata-box-img" />
                                    <div class="batllie-cata-box-name">Despalada<br>gruesa</div>
                                    <div class="batllie-cata-box-sub">(pura hoja e intensidad profunda)</div>
                                    <button type="button" class="batllie-cata-card-scroll-btn" data-scroll-to="molienda-2">Ver molienda ↓</button>
                                </div>
                                <div class="batllie-cata-box-card" data-scroll-to="molienda-3">
                                    <div class="batllie-cata-box-num">III</div>
                                    <img src="<?php echo esc_url($asset('yerba-bag.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/watermarked_img_11158224245304405509.jpg')); ?>" alt="Fina barbacuá" class="batllie-cata-box-img" />
                                    <div class="batllie-cata-box-name">Fina<br>barbacuá</div>
                                    <div class="batllie-cata-box-sub">(secado a leña y notas ahumadas)</div>
                                    <button type="button" class="batllie-cata-card-scroll-btn" data-scroll-to="molienda-3">Ver molienda ↓</button>
                                </div>
                                <div class="batllie-cata-box-card" data-scroll-to="molienda-4">
                                    <div class="batllie-cata-box-num">IV</div>
                                    <img src="<?php echo esc_url($asset('yerba-bag.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/watermarked_img_11158224245304405509.jpg')); ?>" alt="Canchada" class="batllie-cata-box-img" />
                                    <div class="batllie-cata-box-name">Canchada</div>
                                    <div class="batllie-cata-box-sub">(hoja gruesa y corte rústico)</div>
                                    <button type="button" class="batllie-cata-card-scroll-btn" data-scroll-to="molienda-4">Ver molienda ↓</button>
                                </div>
                            </div>

                            <!-- Secciones detalladas en scroll hacia abajo con título previo -->
                            <div class="batllie-cata-moliendas-scroll-section">
                                <h2 class="batllie-cata-section-title" style="margin-bottom: 24px;">Conocé más moliendas</h2>

                                <!-- Sección I -->
                                <div id="molienda-1" class="batllie-cata-single-step-container batllie-cata-molienda-section-item">
                                    <h3 class="batllie-cata-section-title" style="font-size: 1.5rem;">Variación I: Tradicional fina</h3>
                                    <div class="batllie-cata-section-subtitle">CON PALO Y MOLIENDA EQUILIBRADA</div>

                                    <div class="batllie-cata-step-detail-card">
                                        <div class="batllie-cata-step-detail-img-wrap">
                                            <img src="<?php echo esc_url($asset('yerba-bag.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/watermarked_img_11158224245304405509.jpg')); ?>" alt="Tradicional fina" class="batllie-cata-step-detail-img" />
                                        </div>
                                        <div class="batllie-cata-step-detail-body">
                                            <h4 class="batllie-cata-step-detail-heading">Características de la molienda</h4>
                                            <p class="batllie-cata-step-detail-p">
                                                Elaborada con una proporción balanceada de hojas seleccionadas y palo noble, con bajo porcentaje de polvo. Entrega un amargor amable y equilibrado, notas herbáceas frescas y una infusión ligera y noble.
                                            </p>
                                            <div class="batllie-cata-step-detail-divider"></div>
                                            <h4 class="batllie-cata-step-detail-heading">Momento ideal</h4>
                                            <p class="batllie-cata-step-detail-p">
                                                El ritual cotidiano por excelencia: ideal para encender las mañanas, acompañar las horas de trabajo continuo o compartir rondas extensas donde se prioriza un sabor armonioso y parejo.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección II -->
                                <div id="molienda-2" class="batllie-cata-single-step-container batllie-cata-molienda-section-item">
                                    <h3 class="batllie-cata-section-title" style="font-size: 1.5rem;">Variación II: Despalada gruesa</h3>
                                    <div class="batllie-cata-section-subtitle">PURA HOJA E INTENSIDAD PROFUNDA</div>

                                    <div class="batllie-cata-step-detail-card">
                                        <div class="batllie-cata-step-detail-img-wrap">
                                            <img src="<?php echo esc_url($asset('yerba-bag.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/watermarked_img_11158224245304405509.jpg')); ?>" alt="Despalada gruesa" class="batllie-cata-step-detail-img" />
                                        </div>
                                        <div class="batllie-cata-step-detail-body">
                                            <h4 class="batllie-cata-step-detail-heading">Características de la molienda</h4>
                                            <p class="batllie-cata-step-detail-p">
                                                Compuesta en un 100% por hojas puras seleccionadas de corte grueso, libre de palo. Se distingue por su gran densidad en taza, astringencia elegante y un carácter más marcado y persistente en cada cebada.
                                            </p>
                                            <div class="batllie-cata-step-detail-divider"></div>
                                            <h4 class="batllie-cata-step-detail-heading">Momento ideal</h4>
                                            <p class="batllie-cata-step-detail-p">
                                                Para instancias de profunda concentración, estudio o largas jornadas. La elección predilecta para quienes disfrutan de un mate con cuerpo contundente, estimulante y de carácter firme.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección III -->
                                <div id="molienda-3" class="batllie-cata-single-step-container batllie-cata-molienda-section-item">
                                    <h3 class="batllie-cata-section-title" style="font-size: 1.5rem;">Variación III: Fina barbacuá</h3>
                                    <div class="batllie-cata-section-subtitle">SECADO A LEÑA Y NOTAS AHUMADAS</div>

                                    <div class="batllie-cata-step-detail-card">
                                        <div class="batllie-cata-step-detail-img-wrap">
                                            <img src="<?php echo esc_url($asset('yerba-bag.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/watermarked_img_11158224245304405509.jpg')); ?>" alt="Fina barbacuá" class="batllie-cata-step-detail-img" />
                                        </div>
                                        <div class="batllie-cata-step-detail-body">
                                            <h4 class="batllie-cata-step-detail-heading">Características de la molienda</h4>
                                            <p class="batllie-cata-step-detail-p">
                                                Nacida del ancestral proceso barbacuá: secado lento a leña durante 24 horas y molienda fina. Desarrolla aromas ahumados envolventes, tonos tostados amaderados y una singular calidez en boca.
                                            </p>
                                            <div class="batllie-cata-step-detail-divider"></div>
                                            <h4 class="batllie-cata-step-detail-heading">Momento ideal</h4>
                                            <p class="batllie-cata-step-detail-p">
                                                Tardes templadas, sobremesas tranquilas y días frescos o lluviosos. Un mate reflexivo para saborear con calma, evocando los orígenes más rústicos y profundos de la selva misionera.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección IV -->
                                <div id="molienda-4" class="batllie-cata-single-step-container batllie-cata-molienda-section-item">
                                    <h3 class="batllie-cata-section-title" style="font-size: 1.5rem;">Variación IV: Canchada</h3>
                                    <div class="batllie-cata-section-subtitle">HOJA GRUESA Y CORTE RÚSTICO</div>

                                    <div class="batllie-cata-step-detail-card">
                                        <div class="batllie-cata-step-detail-img-wrap">
                                            <img src="<?php echo esc_url($asset('yerba-bag.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/watermarked_img_11158224245304405509.jpg')); ?>" alt="Canchada" class="batllie-cata-step-detail-img" />
                                        </div>
                                        <div class="batllie-cata-step-detail-body">
                                            <h4 class="batllie-cata-step-detail-heading">Características de la molienda</h4>
                                            <p class="batllie-cata-step-detail-p">
                                                Hojas deshidratadas partidas en fracciones amplias de corte rústico, prácticamente sin polvo. Da como resultado una infusión sumamente limpia y ligera, que no satura el lecho y tiene un lavado excepcionalmente pausado.
                                            </p>
                                            <div class="batllie-cata-step-detail-divider"></div>
                                            <h4 class="batllie-cata-step-detail-heading">Momento ideal</h4>
                                            <p class="batllie-cata-step-detail-p">
                                                La compañera insustituible para tereré con agua helada o jugos cítricos en días cálidos. También idónea para quienes prefieren un mate muy liviano, digestivo y fluido para paseos al aire libre.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2: Paso I: El Lecho -->
                    <div class="batllie-cata-slide" data-slide-id="mate-step1">
                        <div class="batllie-cata-single-step-container">
                            <h2 class="batllie-cata-section-title">Paso I: El Lecho</h2>
                            <div class="batllie-cata-section-subtitle">LA PREPARACIÓN Y ARMONÍA INICIAL</div>

                            <div class="batllie-cata-step-detail-card">
                                <div class="batllie-cata-step-detail-img-wrap">
                                    <img src="<?php echo esc_url($asset('mate-1-cream.png', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/mate-1-cream-1790013939.png')); ?>" alt="Paso I: El Lecho" class="batllie-cata-step-detail-img" />
                                </div>
                                <div class="batllie-cata-step-detail-body">
                                    <h4 class="batllie-cata-step-detail-heading">Carga y oxigenación</h4>
                                    <p class="batllie-cata-step-detail-p">
                                        Llenar tres cuartas partes del recipiente con tu variedad predilecta de yerba Batllié agroecológica. Cubrir la boca del mate con la palma de la mano, invertirlo con cuidado y agitarlo suavemente para que los componentes más finos se acomoden en la superficie.
                                    </p>
                                    <div class="batllie-cata-step-detail-divider"></div>
                                    <h4 class="batllie-cata-step-detail-heading">La inclinación a 45°</h4>
                                    <p class="batllie-cata-step-detail-p">
                                        Volver el mate a su posición recostando la yerba a 45° sobre uno de los laterales. Esto creará una pendiente firme, liberando un espacio en la base donde se iniciará el templado y la colocación de la bombilla.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3: Paso II: La Base -->
                    <div class="batllie-cata-slide" data-slide-id="mate-step2">
                        <div class="batllie-cata-single-step-container">
                            <h2 class="batllie-cata-section-title">Paso II: La Base</h2>
                            <div class="batllie-cata-section-subtitle">EL TEMPLE DEL AGUA Y COLOCACIÓN DE LA BOMBILLA</div>

                            <div class="batllie-cata-step-detail-card">
                                <div class="batllie-cata-step-detail-img-wrap">
                                    <img src="<?php echo esc_url($asset('mate-2-cream.png', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/mate-2-cream-1790013939.png')); ?>" alt="Paso II: La Base" class="batllie-cata-step-detail-img" />
                                </div>
                                <div class="batllie-cata-step-detail-body">
                                    <h4 class="batllie-cata-step-detail-heading">El temple y asentamiento</h4>
                                    <p class="batllie-cata-step-detail-p">
                                        Humedecer suavemente la parte baja del lecho con un primer chorro de agua tibia (40°C - 50°C). Dejar reposar unos instantes para que las hojas absorban la humedad y se hinchen de forma natural antes de introducir la bombilla.
                                    </p>
                                    <div class="batllie-cata-step-detail-divider"></div>
                                    <h4 class="batllie-cata-step-detail-heading">Firmeza de la bombilla</h4>
                                    <p class="batllie-cata-step-detail-p">
                                        Tapar el orificio de la bombilla con la yema del pulgar para impedir que ingrese polvo o restos de yerba. Introducirla en el sector húmedo con un movimiento firme hasta hacer tope en el fondo del recipiente. Una vez ubicada, evitar moverla para no desarmar la estructura del lecho.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 4: Paso III: El Disfrute -->
                    <div class="batllie-cata-slide" data-slide-id="mate-step3">
                        <div class="batllie-cata-single-step-container">
                            <h2 class="batllie-cata-section-title">Paso III: El Disfrute</h2>
                            <div class="batllie-cata-section-subtitle">TEMPERATURA ÓPTIMA Y ARTE DE CEBAR</div>

                            <div class="batllie-cata-step-detail-card">
                                <div class="batllie-cata-step-detail-img-wrap">
                                    <img src="<?php echo esc_url($asset('mate-3-cream.png', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/mate-3-cream-1790013939.png')); ?>" alt="Paso III: El Disfrute" class="batllie-cata-step-detail-img" />
                                </div>
                                <div class="batllie-cata-step-detail-body">
                                    <h4 class="batllie-cata-step-detail-heading">Temperatura sugerida: 75°C – 80°C</h4>
                                    <p class="batllie-cata-step-detail-p">
                                        Cebar siempre con agua a la temperatura justa. Es primordial evitar que el agua hierva, ya que el exceso térmico quema las hojas nobles de la yerba, desvanece las notas aromáticas y satura el paladar de amargor.
                                    </p>
                                    <div class="batllie-cata-step-detail-divider"></div>
                                    <h4 class="batllie-cata-step-detail-heading">La técnica de cebado</h4>
                                    <p class="batllie-cata-step-detail-p">
                                        Verter el agua en un hilo fino y continuo junto a la bombilla, sin mojar todo el mate. Conservar siempre la mitad de la yerba seca en la parte superior permitirá ir rotando la cebada y disfrutar de una ronda prolongada con abundante espuma y sabor parejo.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 5: Cierre Matera -->
                    <div class="batllie-cata-slide" data-slide-id="closing">
                        <div class="batllie-cata-closing">
                            <h2>Yerba Mate Agroecológica</h2>
                            <p style="font-style: italic; color: var(--batllie-text);">En Batllié concebimos el mate como un ritual de pausa, encuentro y conexión con lo esencial. Nos alegra que formes parte de esta experiencia única.</p>

                            <div class="batllie-cata-closing-actions">
                                <a href="<?php echo esc_url($atts['shop_url']); ?>" class="batllie-cata-btn-shop">Pedir Yerba Mate Agroecológica</a>
                                <button type="button" class="batllie-cata-btn-restart">Reiniciar Experiencia</button>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- ========================================== -->
                    <!-- MODO CLÁSICO: EL RITUAL DE CATA            -->
                    <!-- ========================================== -->

                    <!-- Slide 0: Intro -->
                    <div class="batllie-cata-slide active" data-slide-id="intro">
                        <div class="batllie-cata-intro">
                            <?php if (!empty($logo_url)): ?>
                                <img src="<?php echo esc_url($logo_url); ?>" alt="Batllié" class="batllie-cata-intro-logo-clean" />
                            <?php endif; ?>
                            <h3 class="batllie-cata-intro-subtitle">El ritual de cata</h3>
                            <p class="batllie-cata-intro-desc">Te invitamos a detener el tiempo y vivir un recorrido sensorial único. Para apreciar la arquitectura de sabores en su máxima expresión y evitar que los matices se anulen, sugerimos transitar la experiencia en este orden</p>

                            <div class="batllie-cata-toggles-group">
                                <div class="batllie-cata-toggle-box">
                                    <span class="batllie-cata-toggle-label">Música ambiental durante la cata:</span>
                                    <label class="batllie-cata-switch" aria-label="Activar música ambiental">
                                        <input type="checkbox" class="batllie-cata-sound-toggle" checked />
                                        <span class="batllie-cata-slider"></span>
                                    </label>
                                </div>
                                <div class="batllie-cata-toggle-box">
                                    <span class="batllie-cata-toggle-label">¿Acompañas con mate tradicional?:</span>
                                    <label class="batllie-cata-switch" aria-label="Acompañar la experiencia con mate">
                                        <input type="checkbox" class="batllie-cata-mate-toggle" checked />
                                        <span class="batllie-cata-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <button type="button" class="batllie-cata-btn-start">Iniciar Experiencia</button>
                        </div>
                    </div>

                    <!-- Slide: El Ritual del Mate (Condicional) -->
                    <div class="batllie-cata-slide" data-slide-id="mate">
                        <div class="batllie-cata-mate-steps">
                            <h2 class="batllie-cata-section-title">El Ritual del Mate</h2>
                            <div class="batllie-cata-section-subtitle">Una pausa consciente para disfrutar de nuestros blends.</div>

                            <div class="batllie-cata-mate-item">
                                <img src="<?php echo esc_url($asset('mate-1-cream.png', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/mate-1-cream-1790013939.png')); ?>" alt="I. El Lecho" class="batllie-cata-mate-img" />
                                <div class="batllie-cata-mate-text">
                                    <div class="batllie-cata-mate-step-title">I. EL LECHO</div>
                                    <p class="batllie-cata-mate-step-desc">Llenar tres cuartas partes del recipiente con yerba Batllié agroecológica. Cubrir la boca con la palma de la mano, agitar suavemente y volver a inclinar a 45° para lograr la armonía perfecta.</p>
                                </div>
                            </div>

                            <div class="batllie-cata-mate-item">
                                <img src="<?php echo esc_url($asset('mate-2-cream.png', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/mate-2-cream-1790013939.png')); ?>" alt="II. La Base" class="batllie-cata-mate-img" />
                                <div class="batllie-cata-mate-text">
                                    <div class="batllie-cata-mate-step-title">II. LA BASE</div>
                                    <p class="batllie-cata-mate-step-desc">Humedecer suavemente la parte baja con agua tibia e introducir la bombilla en ese mismo sector con firmeza.</p>
                                </div>
                            </div>

                            <div class="batllie-cata-mate-item">
                                <img src="<?php echo esc_url($asset('mate-3-cream.png', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/mate-3-cream-1790013939.png')); ?>" alt="III. El Disfrute" class="batllie-cata-mate-img" />
                                <div class="batllie-cata-mate-text">
                                    <div class="batllie-cata-mate-step-title">III. EL DISFRUTE</div>
                                    <p class="batllie-cata-mate-step-desc">Cebar con agua a una temperatura sugerida de 75°C, evitando el hervor para preservar las notas y matices únicos de la yerba.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide: Experiencia Completa (Boxes) -->
                    <div class="batllie-cata-slide" data-slide-id="boxes">
                        <div class="batllie-cata-boxes-container">
                            <h2 class="batllie-cata-section-title">Experiencia completa</h2>
                            <div class="batllie-cata-section-subtitle">Nuestra arquitectura de sabores dividida por cajas</div>

                            <div class="batllie-cata-boxes-grid">
                                <div class="batllie-cata-box-card" data-goto-chapter="chap1">
                                    <div class="batllie-cata-box-num">I</div>
                                    <img src="<?php echo esc_url($asset('box.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/box-1790013939.jpg')); ?>" alt="Box I" class="batllie-cata-box-img" />
                                    <div class="batllie-cata-box-name">La chispa<br>viva</div>
                                    <div class="batllie-cata-box-sub">(los cítricos y ligeros)</div>
                                </div>
                                <div class="batllie-cata-box-card" data-goto-chapter="chap2">
                                    <div class="batllie-cata-box-num">II</div>
                                    <img src="<?php echo esc_url($asset('box.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/box-1790013939.jpg')); ?>" alt="Box II" class="batllie-cata-box-img" />
                                    <div class="batllie-cata-box-name">El equilibrio<br>clásico</div>
                                    <div class="batllie-cata-box-sub">(las raices de la casa)</div>
                                </div>
                                <div class="batllie-cata-box-card" data-goto-chapter="chap3">
                                    <div class="batllie-cata-box-num">III</div>
                                    <img src="<?php echo esc_url($asset('box.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/box-1790013939.jpg')); ?>" alt="Box III" class="batllie-cata-box-img" />
                                    <div class="batllie-cata-box-name">Intensidad<br>absoluta</div>
                                    <div class="batllie-cata-box-sub">(los de autor y complejos)</div>
                                </div>
                            </div>

                            <p class="batllie-cata-boxes-desc">
                                Cada box representa un capítulo de nuestra experiencia sensorial. Diseñamos este orden para transitar de forma armónica desde las notas cítricas y ligeras, pasando por las raíces clásicas de nuestra casa, hasta culminar en la máxima intensidad y complejidad de autor.
                            </p>
                        </div>
                    </div>

                    <!-- Slide: Capítulo 1 -->
                    <div class="batllie-cata-slide" data-slide-id="chap1">
                        <div class="batllie-cata-chapter-container">
                            <h2 class="batllie-cata-section-title">Capítulo I: La chispa viva</h2>
                            <div class="batllie-cata-section-subtitle">Los cítricos y ligeros</div>

                            <div class="batllie-cata-alfajor-card">
                                <div class="batllie-cata-alfajor-name">1- LIMÓN: UNA RUPTURA SENSORIAL</div>
                                <p class="batllie-cata-alfajor-text">MASA SABLEÉ DE QUIEBRE PERFECTO CON NOTAS DE CÍTRICOS VIVOS, ABRAZANDO UN CURD ARTESANAL DE ACIDEZ PRECISA Y UNA COBERTURA DE CHOCOLATE SEMI AMARGO QUE DESAFÍA EL PALADAR.</p>
                                <div class="batllie-cata-alfajor-img-wrap">
                                    <img src="<?php echo esc_url($asset('limon.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/limon-1790013939.jpg')); ?>" alt="Alfajor Limón" class="batllie-cata-alfajor-img" />
                                </div>
                            </div>

                            <div class="batllie-cata-alfajor-card">
                                <div class="batllie-cata-alfajor-name">2- BATLLIÉ BLANCO: UNA OBRA DE LUZ</div>
                                <p class="batllie-cata-alfajor-text">NUESTRA ESTRUCTURA CLÁSICA DE CACAO SE ENCIENDE AQUÍ CON UN DESTELLO FRESCO DE RALLADURA DE NARANJA NATURAL, FUNDIÉNDOSE CON EL DULCE DE LECHE Y LA CARICIA ETÉREA DE UN MERENGUE SUIZO SOMETIDO A UN REPOSO PACIENTE DE 24 A 48 HORAS PARA LOGRAR SU PUNTO ÓPTIMO DE SECADO.</p>
                                <div class="batllie-cata-alfajor-img-wrap">
                                    <img src="<?php echo esc_url($asset('batllie-blanco.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/batllie-blanco-1790013939.jpg')); ?>" alt="Alfajor Batllié Blanco" class="batllie-cata-alfajor-img" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide: Capítulo 2 -->
                    <div class="batllie-cata-slide" data-slide-id="chap2">
                        <div class="batllie-cata-chapter-container">
                            <h2 class="batllie-cata-section-title">Capítulo II: El equilibrio clásico</h2>
                            <div class="batllie-cata-section-subtitle">Las raices de la casa</div>

                            <div class="batllie-cata-alfajor-card">
                                <div class="batllie-cata-alfajor-name">1- BATLLIÉ NEGRO: EL LATIDO ORIGINAL DE LA CASA</div>
                                <p class="batllie-cata-alfajor-text">NUESTRA FÓRMULA MADRE DE CACAO PROFUNDO CON MATICES DE COÑAC ABRAZA EL DULCE DE LECHE TRADICIONAL BAJO UNA COBERTURA ENVOLVENTE DE CHOCOLATE CON LECHE.</p>
                                <div class="batllie-cata-alfajor-img-wrap">
                                    <img src="<?php echo esc_url($asset('batllie-negro.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/batllie-negro-1790013939.jpg')); ?>" alt="Alfajor Batllié Negro" class="batllie-cata-alfajor-img" />
                                </div>
                            </div>

                            <div class="batllie-cata-alfajor-card">
                                <div class="batllie-cata-alfajor-name">2- CHOCOLATE BLANCO: EL CONTRASTE DEFINITIVO</div>
                                <p class="batllie-cata-alfajor-text">EL CUERPO EMBLEMÁTICO DE CACAO Y COÑAC SE UNE AL DULCE DE LECHE, ENCONTRANDO SU CONTRAPARTE PERFECTA EN UNA NOBLE CAPA DE CHOCOLATE BLANCO DE PUREZA ABSOLUTA.</p>
                                <div class="batllie-cata-alfajor-img-wrap">
                                    <img src="<?php echo esc_url($asset('chocolate-blanco.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/chocolate-blanco-1790013939.jpg')); ?>" alt="Alfajor Chocolate Blanco" class="batllie-cata-alfajor-img" />
                                </div>
                            </div>

                            <div class="batllie-cata-alfajor-card">
                                <div class="batllie-cata-alfajor-name">3- CAFÉ SUIZO: UN HOMENAJE AL TIEMPO</div>
                                <p class="batllie-cata-alfajor-text">UN PERFIL DONDE EL PROTAGONISMO SE DESPLAZA HACIA NOTAS ACENTUADAS DE CAFÉ DE AUTOR EN LAS TAPAS, UN NÚCLEO CREMOSO DE DULCE DE LECHE Y LA DENSIDAD CELESTIAL DE UN MERENGUE SUIZO ESTACIONADO DURANTE 48 HORAS PARA ALCANZAR SU TEXTURA IDEAL.</p>
                                <div class="batllie-cata-alfajor-img-wrap">
                                    <img src="<?php echo esc_url($asset('cafe-suizo.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/cafe-suizo-1790013939.jpg')); ?>" alt="Alfajor Café Suizo" class="batllie-cata-alfajor-img" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide: Capítulo 3 -->
                    <div class="batllie-cata-slide" data-slide-id="chap3">
                        <div class="batllie-cata-chapter-container">
                            <h2 class="batllie-cata-section-title">Capítulo III: Intensidad absoluta</h2>
                            <div class="batllie-cata-section-subtitle">Los de autor y complejos</div>

                            <div class="batllie-cata-alfajor-card">
                                <div class="batllie-cata-alfajor-name">1- CHOCOLATE INTENSO: LA OSCURIDAD ELEGANTE</div>
                                <p class="batllie-cata-alfajor-text">LA MÁXIMA EXPRESIÓN DE NUESTRO CUERPO DE CACAO IMPREGNADO EN COÑAC, LLEVADO AL LÍMITE CON UN BAÑO SEMI AMARGO DE CARÁCTER INQUEBRANTABLE QUE PROFUNDIZA EL SABOR.</p>
                                <div class="batllie-cata-alfajor-img-wrap">
                                    <img src="<?php echo esc_url($asset('chocolate-intenso.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/chocolate-intenso-1790013939.jpg')); ?>" alt="Alfajor Chocolate Intenso" class="batllie-cata-alfajor-img" />
                                </div>
                            </div>

                            <div class="batllie-cata-alfajor-card">
                                <div class="batllie-cata-alfajor-name">2- NUEZ: LA FUERZA DE LA TIERRA</div>
                                <p class="batllie-cata-alfajor-text">UNA PIEZA QUE ROMPE ESQUEMAS CON SU MASA RÚSTICA DE ALGARROBA, ABUNDANTES TROZOS DE NUEZ Y UN SUTIL ESPÍRITU DE WHISKY, UNIDA AL DULCE DE LECHE Y PROTEGIDA POR UNA MANTA DE CHOCOLATE BLANCO.</p>
                                <div class="batllie-cata-alfajor-img-wrap">
                                    <img src="<?php echo esc_url($asset('nuez.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/nuez-1790013939.jpg')); ?>" alt="Alfajor Nuez" class="batllie-cata-alfajor-img" />
                                </div>
                            </div>

                            <div class="batllie-cata-alfajor-card">
                                <div class="batllie-cata-alfajor-name">3- MOUSSE NUTELLA: EL EXCESO SOFISTICADO</div>
                                <p class="batllie-cata-alfajor-text">SOBRE NUESTRA BASE DE CACAO Y COÑAC SE DESPLIEGA UNA MOUSSE DE CHOCOLATE DE TEXTURA IMPOSIBLE, QUE ESCONDE EN SU CENTRO UN CORAZÓN DESBORDANTE DE NUTELLA BAJO UN MANTO SEMI AMARGO.</p>
                                <div class="batllie-cata-alfajor-img-wrap">
                                    <img src="<?php echo esc_url($asset('mousse-nutella.jpg', 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/mousse-nutella-1790013939.jpg')); ?>" alt="Alfajor Mousse Nutella" class="batllie-cata-alfajor-img" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slide Final: Cierre -->
                    <div class="batllie-cata-slide" data-slide-id="closing">
                        <div class="batllie-cata-closing">
                            <h2>Alfajorería de autor</h2>
                            <p>Elaboración artesanal en micro-lotes con manteca 100% y materias primas de primera línea, sin réplica industrial. Edición del día.</p>
                            <p style="font-style: italic; color: var(--batllie-text);">Cada alfajor de Batllié es fruto del respeto por los tiempos de reposo y la pasión por los detalles. Nos alegra que seas parte de esta experiencia única.</p>

                            <div class="batllie-cata-closing-actions">
                                <a href="<?php echo esc_url($atts['shop_url']); ?>" class="batllie-cata-btn-shop">Pedir Selección de Cata</a>
                                <button type="button" class="batllie-cata-btn-restart">Reiniciar Ritual</button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer Navigation (Hidden on Intro) -->
            <div class="batllie-cata-nav-footer" style="display: none;">
                <button type="button" class="batllie-cata-nav-btn batllie-cata-nav-btn-prev">
                    ← Anterior
                </button>
                <div class="batllie-cata-dots"></div>
                <button type="button" class="batllie-cata-nav-btn batllie-cata-nav-btn-next">
                    Siguiente →
                </button>
            </div>

            <!-- Audio Element -->
            <audio class="batllie-cata-audio-element" preload="auto" loop>
                <source src="<?php echo esc_url($atts['audio_url']); ?>" type="audio/mpeg" />
            </audio>
        </div>

        <?php if ($js_url): ?>
            <!-- External Script (batllie-cata.js) Fallback -->
            <script src="<?php echo esc_url($js_url); ?>?ver=1.3.2" defer></script>
        <?php endif; ?>
        <?php
        return ob_get_clean();
    }

    add_shortcode('batllie_cata', 'batllie_cata_shortcode');
    add_shortcode('emp_cata', 'batllie_cata_shortcode');
    add_shortcode('batllie_matera', function($atts = array()) {
        $atts = (array)$atts;
        $atts['mode'] = 'matera';
        return batllie_cata_shortcode($atts);
    });
    add_shortcode('emp_matera', function($atts = array()) {
        $atts = (array)$atts;
        $atts['mode'] = 'matera';
        return batllie_cata_shortcode($atts);
    });
}
