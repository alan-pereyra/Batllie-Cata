<?php
/**
 * Shortcode: El Ritual de Cata (Batllié)
 * Standalone Plugin Implementation
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('batllie_cata_shortcode')) {
    function batllie_cata_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'audio_url'        => 'https://cdn.pixabay.com/download/audio/2022/05/27/audio_1808fbf07a.mp3?filename=acoustic-guitar-ambient-relax-112191.mp3',
            'audio_title'      => 'Melodía Ambiental — El Ritual',
            'shop_url'         => '',
            'close_url'        => home_url('/'),
            'logo'             => '',
            'logo_url'         => '',
            'logo_clean_url'   => 'https://empralidad.com.ar/batllie/wp-content/uploads/2026/09/batllie-logo-transparent.png',
            'title'            => 'El Ritual de Cata',
            'accent_color'     => '',
            'color_acento'     => '',
            'color'            => '',
        ), $atts, 'batllie_cata');

        if (empty($atts['shop_url'])) {
            $tag = get_term_by('slug', 'cata', 'product_tag');
            if ($tag && !is_wp_error($tag)) {
                $atts['shop_url'] = get_term_link($tag);
            } else {
                $atts['shop_url'] = home_url('/etiqueta-producto/cata/');
            }
        }

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

        $logo_url = !empty($atts['logo']) ? $atts['logo'] : (!empty($atts['logo_url']) ? $atts['logo_url'] : $atts['logo_clean_url']);

        // Determine assets URL
        if (function_exists('get_template_directory') && file_exists(get_template_directory() . '/img/cata/box.jpg')) {
            $assets_url = get_template_directory_uri() . '/img/cata/';
        } elseif (defined('WP_CONTENT_URL') && file_exists(WP_CONTENT_DIR . '/uploads/batllie-cata/box.jpg')) {
            $assets_url = content_url('/uploads/batllie-cata/');
        } else {
            $assets_url = plugins_url('../assets/images/', __FILE__);
        }

                $asset = function($filename, $remote_url = '') use ($assets_url) {
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

        // Check local logo
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

        $uid = 'batllie_cata_' . wp_rand(1000, 9999);

        ob_start();
        ?>
        <div id="<?php echo esc_attr($uid); ?>" class="batllie-cata-root" data-audio="<?php echo esc_url($atts['audio_url']); ?>" data-audio-title="<?php echo esc_attr($atts['audio_title']); ?>">
            <style>
                #<?php echo esc_attr($uid); ?>.batllie-cata-root {
                    --batllie-bg: #162a1f;
                    --batllie-text: #fdffdd;
                    <?php if (!empty($custom_accent)): ?>
                    --batllie-accent: <?php echo esc_attr($custom_accent); ?>;
                    --batllie-accent-hover: <?php echo esc_attr($custom_accent); ?>;
                    --batllie-btn-shop-text: #ffffff;
                    --batllie-btn-shop-text-hover: #ffffff;
                    <?php else: ?>
                    --batllie-accent: var(--batllie-text);
                    --batllie-accent-hover: #ffffff;
                    --batllie-btn-shop-text: var(--batllie-bg);
                    --batllie-btn-shop-text-hover: var(--batllie-bg);
                    <?php endif; ?>
                }
                html.has-batllie-cata,
                body.has-batllie-cata {
                    height: 100% !important;
                    overflow: hidden !important;
                }
                #<?php echo esc_attr($uid); ?>.batllie-cata-root {
                    background-color: var(--batllie-bg);
                    bottom: 0;
                    box-sizing: border-box;
                    color: var(--batllie-text);
                    display: flex;
                    flex-direction: column;
                    font-family: 'Cormorant Garamond', Georgia, serif;
                    height: 100vh;
                    height: 100dvh;
                    justify-content: space-between;
                    left: 0;
                    margin: 0;
                    overflow: hidden;
                    padding: 0;
                    position: fixed;
                    right: 0;
                    top: 0;
                    width: 100vw;
                    z-index: 999999;
                }
                #<?php echo esc_attr($uid); ?>.batllie-cata-root * {
                    box-sizing: border-box;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-header {
                    align-items: center;
                    border-bottom: 1px solid rgba(253, 255, 221, 0.12);
                    display: flex;
                    flex-shrink: 0;
                    height: 52px;
                    justify-content: space-between;
                    padding: 0 20px;
                    position: relative;
                    width: 100%;
                    z-index: 10;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-header-title {
                    color: var(--batllie-text);
                    flex: 1;
                    font-family: 'Cormorant Garamond', Georgia, serif;
                    font-size: 1.15rem;
                    font-weight: 600;
                    letter-spacing: 3px;
                    text-align: left;
                    text-transform: uppercase;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-top-actions {
                    align-items: center;
                    display: flex;
                    gap: 10px;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-audio-btn,
                #<?php echo esc_attr($uid); ?> .batllie-cata-close-btn {
                    align-items: center;
                    background-color: rgba(253, 255, 221, 0.08);
                    border: 1px solid rgba(253, 255, 221, 0.2);
                    border-radius: 20px;
                    color: var(--batllie-text);
                    cursor: pointer;
                    display: inline-flex;
                    font-family: inherit;
                    font-size: 0.82rem;
                    gap: 6px;
                    height: 30px;
                    justify-content: center;
                    line-height: 1;
                    padding: 0 12px;
                    text-decoration: none;
                    transition: all 0.25s ease;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-close-btn {
                    font-size: 1rem;
                    padding: 0;
                    width: 30px;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-audio-btn:hover,
                #<?php echo esc_attr($uid); ?> .batllie-cata-close-btn:hover {
                    background-color: rgba(253, 255, 221, 0.2);
                    border-color: var(--batllie-text);
                    color: #ffffff;
                    transform: translateY(-1px);
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-audio-waves {
                    display: inline-flex;
                    gap: 3px;
                    height: 12px;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-wave-bar {
                    animation: none;
                    background-color: var(--batllie-accent);
                    border-radius: 2px;
                    height: 100%;
                    width: 2px;
                }
                #<?php echo esc_attr($uid); ?>.is-playing .batllie-cata-wave-bar:nth-child(1) {
                    animation: batllieWave 0.8s ease-in-out infinite alternate;
                }
                #<?php echo esc_attr($uid); ?>.is-playing .batllie-cata-wave-bar:nth-child(2) {
                    animation: batllieWave 0.6s ease-in-out infinite alternate 0.2s;
                }
                #<?php echo esc_attr($uid); ?>.is-playing .batllie-cata-wave-bar:nth-child(3) {
                    animation: batllieWave 0.9s ease-in-out infinite alternate 0.4s;
                }
                @keyframes batllieWave {
                    0% { transform: scaleY(0.3); }
                    100% { transform: scaleY(1); }
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-progress-bar {
                    background-color: rgba(253, 255, 221, 0.08);
                    display: flex;
                    flex-shrink: 0;
                    height: 3px;
                    width: 100%;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-progress-fill {
                    background-color: var(--batllie-accent);
                    height: 100%;
                    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                    width: 0%;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-viewport {
                    align-items: center;
                    display: flex;
                    flex: 1;
                    justify-content: center;
                    overflow: hidden;
                    padding: 8px 16px;
                    position: relative;
                    width: 100%;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-slide {
                    align-items: center;
                    display: none;
                    flex-direction: column;
                    justify-content: flex-start;
                    max-height: 100%;
                    max-width: 820px;
                    opacity: 0;
                    overflow-y: auto;
                    padding: 10px 8px 24px 8px;
                    scrollbar-color: var(--batllie-accent) rgba(253, 255, 221, 0.05);
                    scrollbar-width: thin;
                    transform: translateY(6px);
                    transition: opacity 0.3s ease, transform 0.3s ease;
                    -webkit-overflow-scrolling: touch;
                    width: 100%;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-slide::-webkit-scrollbar {
                    width: 4px;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-slide::-webkit-scrollbar-thumb {
                    background-color: var(--batllie-accent);
                    border-radius: 4px;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-slide.active {
                    animation: batllieFadeIn 0.3s forwards;
                    display: flex;
                }
                @keyframes batllieFadeIn {
                    0% { opacity: 0; transform: translateY(6px); }
                    100% { opacity: 1; transform: translateY(0); }
                }

                /* Slide Intro */
                #<?php echo esc_attr($uid); ?> .batllie-cata-intro {
                    align-items: center;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    margin: auto;
                    max-width: 620px;
                    text-align: center;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-intro-logo-clean {
                    background: transparent;
                    display: block;
                    height: auto;
                    margin: 0 auto 16px;
                    max-height: 75px;
                    max-width: 250px;
                    object-fit: contain;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-intro-subtitle {
                    color: var(--batllie-accent);
                    font-size: 1.15rem;
                    font-weight: 600;
                    letter-spacing: 3px;
                    margin: 0 0 16px 0;
                    text-transform: uppercase;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-intro-desc {
                    color: rgba(253, 255, 221, 0.92);
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                    font-size: 0.95rem;
                    line-height: 1.65;
                    margin: 0 0 24px 0;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-toggles-group {
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    margin-bottom: 24px;
                    width: 100%;
                    max-width: 360px;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-toggle-box {
                    align-items: center;
                    background-color: rgba(253, 255, 221, 0.05);
                    border: 1px solid rgba(253, 255, 221, 0.15);
                    border-radius: 30px;
                    display: flex;
                    justify-content: space-between;
                    padding: 8px 18px;
                    width: 100%;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-toggle-label {
                    color: var(--batllie-text);
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                    font-size: 0.88rem;
                    text-align: left;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-switch {
                    cursor: pointer;
                    display: inline-block;
                    flex-shrink: 0;
                    height: 24px;
                    margin-left: 10px;
                    position: relative;
                    width: 44px;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-switch input {
                    height: 0;
                    opacity: 0;
                    width: 0;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-slider {
                    background-color: #4a5d52;
                    border-radius: 24px;
                    bottom: 0;
                    left: 0;
                    position: absolute;
                    right: 0;
                    top: 0;
                    transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-slider:before {
                    background-color: var(--batllie-text);
                    border-radius: 50%;
                    bottom: 3px;
                    content: "";
                    height: 18px;
                    left: 3px;
                    position: absolute;
                    transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    width: 18px;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-switch input:checked + .batllie-cata-slider {
                    background-color: var(--batllie-accent);
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-switch input:checked + .batllie-cata-slider:before {
                    transform: translateX(20px);
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-btn-start {
                    background-color: var(--batllie-text);
                    border: none;
                    border-radius: 30px;
                    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
                    color: var(--batllie-bg);
                    cursor: pointer;
                    font-family: 'Cormorant Garamond', Georgia, serif;
                    font-size: 1.15rem;
                    font-weight: 700;
                    letter-spacing: 1.5px;
                    padding: 12px 36px;
                    text-transform: uppercase;
                    transition: all 0.3s ease;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-btn-start:hover {
                    background-color: #ffffff;
                    box-shadow: 0 10px 26px rgba(196, 121, 44, 0.35);
                    transform: translateY(-2px);
                }

                /* Section Header */
                #<?php echo esc_attr($uid); ?> .batllie-cata-section-title {
                    color: var(--batllie-text);
                    font-size: 1.85rem;
                    font-style: italic;
                    font-weight: 600;
                    letter-spacing: 1px;
                    line-height: 1.2;
                    margin: 0 0 4px 0;
                    text-align: center;
                    width: 100%;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-section-subtitle {
                    color: #fdffdd;
                    opacity: 0.95;
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                    font-size: 0.9rem;
                    font-weight: 600;
                    letter-spacing: 1.5px;
                    margin: 0 0 16px 0;
                    text-align: center;
                    text-transform: uppercase;
                }

                /* Slide Mate */
                #<?php echo esc_attr($uid); ?> .batllie-cata-mate-steps {
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    margin: 0 auto;
                    max-width: 680px;
                    width: 100%;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-mate-item {
                    align-items: center;
                    background-color: rgba(253, 255, 221, 0.05);
                    border: 1px solid rgba(253, 255, 221, 0.12);
                    border-radius: 12px;
                    display: flex;
                    gap: 16px;
                    padding: 14px 18px;
                    text-align: left;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-mate-img {
                    background-color: rgba(253, 255, 221, 0.04);
                    border: 1px solid rgba(253, 255, 221, 0.1);
                    border-radius: 8px;
                    flex-shrink: 0;
                    height: 72px;
                    object-fit: contain;
                    padding: 4px;
                    width: 72px;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-mate-text {
                    flex: 1;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-mate-step-title {
                    color: var(--batllie-accent);
                    font-family: 'Cormorant Garamond', Georgia, serif;
                    font-size: 1.4rem;
                    font-weight: 700;
                    letter-spacing: 1.5px;
                    line-height: 1.2;
                    margin-bottom: 6px;
                    text-transform: uppercase;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-mate-step-desc {
                    color: rgba(253, 255, 221, 0.9);
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                    font-size: 0.88rem;
                    line-height: 1.45;
                    margin: 0;
                }

                /* Slide Experiencia Completa (Boxes) */
                #<?php echo esc_attr($uid); ?> .batllie-cata-boxes-container {
                    display: flex;
                    flex-direction: column;
                    margin: 0 auto;
                    max-width: 740px;
                    width: 100%;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-boxes-grid {
                    align-items: stretch;
                    display: grid;
                    gap: 12px;
                    grid-template-columns: repeat(3, 1fr);
                    margin: 8px 0 16px 0;
                    width: 100%;
                }
                @media (max-width: 680px) {
                    #<?php echo esc_attr($uid); ?> .batllie-cata-boxes-grid {
                        gap: 8px;
                        grid-template-columns: repeat(3, 1fr);
                    }
                    #<?php echo esc_attr($uid); ?> .batllie-cata-box-card {
                        padding: 10px 4px !important;
                    }
                    #<?php echo esc_attr($uid); ?> .batllie-cata-box-num {
                        font-size: 1.05rem !important;
                        margin-bottom: 4px !important;
                    }
                    #<?php echo esc_attr($uid); ?> .batllie-cata-box-img {
                        max-height: 90px !important;
                        margin-bottom: 6px !important;
                    }
                    #<?php echo esc_attr($uid); ?> .batllie-cata-box-name {
                        font-size: 0.85rem !important;
                        min-height: 2.4em !important;
                        line-height: 1.2 !important;
                    }
                    #<?php echo esc_attr($uid); ?> .batllie-cata-box-sub {
                        font-size: 0.72rem !important;
                        min-height: 2.6em !important;
                    }
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-box-card {
                    align-items: center;
                    background-color: rgba(253, 255, 221, 0.05);
                    border: 1px solid rgba(253, 255, 221, 0.12);
                    border-radius: 12px;
                    cursor: pointer;
                    display: flex;
                    flex-direction: column;
                    height: 100%;
                    justify-content: flex-start;
                    padding: 14px 8px;
                    text-align: center;
                    transition: transform 0.25s ease, border-color 0.25s ease, background-color 0.25s ease;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-box-card:hover {
                    background-color: rgba(253, 255, 221, 0.1);
                    border-color: rgba(253, 255, 221, 0.35);
                    transform: translateY(-3px);
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-box-card:active {
                    transform: translateY(-1px);
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-box-num {
                    color: #ffffff;
                    font-family: 'Cormorant Garamond', Georgia, serif;
                    font-size: 1.3rem;
                    font-weight: 700;
                    letter-spacing: 2px;
                    line-height: 1;
                    margin-bottom: 8px;
                    text-align: center;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-box-img {
                    border-radius: 8px;
                    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35);
                    height: auto;
                    margin-bottom: 10px;
                    max-height: 140px;
                    max-width: 100%;
                    object-fit: cover;
                    width: 100%;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-box-name {
                    align-items: center;
                    color: #ffffff;
                    display: flex;
                    font-family: 'Cormorant Garamond', Georgia, serif;
                    font-size: 1.15rem;
                    font-weight: 700;
                    justify-content: center;
                    letter-spacing: 0.5px;
                    line-height: 1.25;
                    margin-bottom: 4px;
                    min-height: 2.5em;
                    text-align: center;
                    text-transform: uppercase;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-box-sub {
                    color: #fdffdd;
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                    font-size: 0.8rem;
                    font-weight: 600;
                    line-height: 1.3;
                    min-height: 2.6em;
                    opacity: 0.9;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-boxes-desc {
                    color: rgba(253, 255, 221, 0.92);
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                    font-size: 0.92rem;
                    line-height: 1.6;
                    margin: 0 auto;
                    max-width: 640px;
                    text-align: center;
                }

                /* Slide Capítulo (Alfajores agrupados con scroll) */
                #<?php echo esc_attr($uid); ?> .batllie-cata-chapter-container {
                    display: flex;
                    flex-direction: column;
                    margin: 0 auto;
                    max-width: 680px;
                    width: 100%;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-alfajor-card {
                    background-color: rgba(253, 255, 221, 0.05);
                    border: 1px solid rgba(253, 255, 221, 0.12);
                    border-radius: 14px;
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    margin-bottom: 16px;
                    padding: 16px 18px;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-alfajor-card:last-child {
                    margin-bottom: 0;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-alfajor-name {
                    color: #ffffff !important;
                    font-family: 'Cormorant Garamond', Georgia, serif;
                    font-size: 1.35rem;
                    font-weight: 700;
                    letter-spacing: 1.5px;
                    line-height: 1.2;
                    margin-bottom: 6px;
                    text-transform: uppercase;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-alfajor-text {
                    color: var(--batllie-text);
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                    font-size: 0.92rem;
                    line-height: 1.55;
                    margin: 0;
                    text-align: left;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-alfajor-img-wrap {
                    align-items: center;
                    display: flex;
                    justify-content: center;
                    margin-top: 4px;
                    width: 100%;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-alfajor-img {
                    border-radius: 10px;
                    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.45);
                    height: auto;
                    max-height: 220px;
                    max-width: 100%;
                    object-fit: contain;
                }

                /* Slide Closing */
                #<?php echo esc_attr($uid); ?> .batllie-cata-closing {
                    align-items: center;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    margin: auto;
                    max-width: 600px;
                    text-align: center;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-closing h2 {
                    color: var(--batllie-text);
                    font-size: 2.2rem;
                    font-weight: 500;
                    letter-spacing: 2px;
                    line-height: 1.15;
                    margin: 0 0 16px 0;
                    text-transform: uppercase;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-closing p {
                    color: rgba(253, 255, 221, 0.88);
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                    font-size: 0.95rem;
                    line-height: 1.5;
                    margin: 0 0 10px 0;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-closing-actions {
                    align-items: center;
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    justify-content: center;
                    margin-top: 16px;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-btn-shop {
                    align-items: center;
                    background-color: var(--batllie-accent);
                    border: none;
                    border-radius: 30px;
                    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
                    color: var(--batllie-btn-shop-text, var(--batllie-bg));
                    cursor: pointer;
                    display: inline-flex;
                    font-family: 'Cormorant Garamond', Georgia, serif;
                    font-size: 1.2rem;
                    font-weight: 700;
                    justify-content: center;
                    letter-spacing: 2px;
                    min-height: 52px;
                    padding: 14px 40px;
                    text-decoration: none;
                    text-transform: uppercase;
                    transition: all 0.3s ease;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-btn-shop:hover {
                    background-color: var(--batllie-accent-hover, #ffffff);
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
                    color: var(--batllie-btn-shop-text-hover, var(--batllie-bg));
                    transform: translateY(-2px);
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-btn-restart {
                    align-items: center;
                    background-color: transparent;
                    border: 1px solid rgba(253, 255, 221, 0.35);
                    border-radius: 30px;
                    color: var(--batllie-text);
                    cursor: pointer;
                    display: inline-flex;
                    font-family: 'Cormorant Garamond', Georgia, serif;
                    font-size: 0.95rem;
                    justify-content: center;
                    letter-spacing: 1.5px;
                    min-height: 38px;
                    padding: 8px 24px;
                    text-transform: uppercase;
                    transition: all 0.3s ease;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-btn-restart:hover {
                    background-color: rgba(253, 255, 221, 0.1);
                    border-color: var(--batllie-text);
                }

                /* Navigation Footer */
                #<?php echo esc_attr($uid); ?> .batllie-cata-nav-footer {
                    align-items: center;
                    border-top: 1px solid rgba(253, 255, 221, 0.12);
                    display: flex;
                    flex-shrink: 0;
                    height: 48px;
                    justify-content: space-between;
                    padding: 0 20px;
                    position: relative;
                    width: 100%;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-nav-btn {
                    align-items: center;
                    background-color: rgba(253, 255, 221, 0.08);
                    border: 1px solid rgba(253, 255, 221, 0.2);
                    border-radius: 20px;
                    color: var(--batllie-text);
                    cursor: pointer;
                    display: inline-flex;
                    font-family: inherit;
                    font-size: 0.85rem;
                    gap: 6px;
                    height: 30px;
                    line-height: 1;
                    padding: 0 14px;
                    transition: all 0.25s ease;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-nav-btn:hover:not(:disabled) {
                    background-color: rgba(253, 255, 221, 0.2);
                    border-color: var(--batllie-text);
                    transform: translateY(-1px);
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-nav-btn:disabled {
                    cursor: not-allowed;
                    opacity: 0.3;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-nav-btn-next {
                    background-color: var(--batllie-text);
                    border-color: var(--batllie-text);
                    color: var(--batllie-bg);
                    font-weight: 700;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-nav-btn-next:hover:not(:disabled) {
                    background-color: #ffffff;
                    box-shadow: 0 3px 12px rgba(253, 255, 221, 0.3);
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-dots {
                    align-items: center;
                    display: flex;
                    gap: 6px;
                    left: 50%;
                    position: absolute;
                    transform: translateX(-50%);
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-dot {
                    background-color: rgba(253, 255, 221, 0.2);
                    border-radius: 50%;
                    cursor: pointer;
                    height: 6px;
                    transition: all 0.3s ease;
                    width: 6px;
                }
                #<?php echo esc_attr($uid); ?> .batllie-cata-dot.active {
                    background-color: var(--batllie-accent);
                    transform: scale(1.3);
                }
            </style>

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
                                <p class="batllie-cata-mate-step-desc">Llenar tres cuartas partes del recipiente con yerba Batllié orgánica. Cubrir la boca con la palma de la mano, agitar suavemente y volver a inclinar a 45° para lograr la armonía perfecta.</p>
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

        <script>
        (function() {
            var root = document.getElementById('<?php echo esc_js($uid); ?>');
            if (!root) return;

            // Lock body and html scroll
            document.documentElement.classList.add('has-batllie-cata');
            document.body.classList.add('has-batllie-cata');

            var allSlides = Array.prototype.slice.call(root.querySelectorAll('.batllie-cata-slide'));
            var currentIndex = 0;

            var progressBar = root.querySelector('.batllie-cata-progress-fill');
            var navFooter = root.querySelector('.batllie-cata-nav-footer');
            var btnPrev = root.querySelector('.batllie-cata-nav-btn-prev');
            var btnNext = root.querySelector('.batllie-cata-nav-btn-next');
            var dotsContainer = root.querySelector('.batllie-cata-dots');

            var btnStart = root.querySelector('.batllie-cata-btn-start');
            var btnRestart = root.querySelector('.batllie-cata-btn-restart');
            var soundToggle = root.querySelector('.batllie-cata-sound-toggle');
            var mateToggle = root.querySelector('.batllie-cata-mate-toggle');
            var audioBtn = root.querySelector('.batllie-cata-audio-btn');
            var audioText = root.querySelector('.batllie-cata-audio-text');
            var audio = root.querySelector('.batllie-cata-audio-element');

            var isAudioPlaying = false;

            function getActiveSlides() {
                var wantsMate = mateToggle ? mateToggle.checked : true;
                return allSlides.filter(function(slide) {
                    if (slide.getAttribute('data-slide-id') === 'mate' && !wantsMate) {
                        return false;
                    }
                    return true;
                });
            }

            function renderDots(activeSlides) {
                if (!dotsContainer) return;
                dotsContainer.innerHTML = '';
                activeSlides.forEach(function(s, idx) {
                    var dot = document.createElement('span');
                    dot.className = 'batllie-cata-dot' + (idx === currentIndex ? ' active' : '');
                    dot.setAttribute('data-dot', idx);
                    dot.addEventListener('click', function() {
                        goToSlide(idx);
                    });
                    dotsContainer.appendChild(dot);
                });
            }

            function updateUI() {
                var activeSlides = getActiveSlides();
                var totalSlides = activeSlides.length;

                if (currentIndex >= totalSlides) {
                    currentIndex = totalSlides - 1;
                }
                if (currentIndex < 0) {
                    currentIndex = 0;
                }

                allSlides.forEach(function(s) {
                    s.classList.remove('active');
                });

                var currentSlide = activeSlides[currentIndex];
                if (currentSlide) {
                    currentSlide.classList.add('active');
                    currentSlide.scrollTop = 0;
                }

                renderDots(activeSlides);

                var percent = totalSlides > 1 ? (currentIndex / (totalSlides - 1)) * 100 : 0;
                if (progressBar) {
                    progressBar.style.width = percent + '%';
                }

                if (currentIndex === 0) {
                    navFooter.style.display = 'none';
                } else {
                    navFooter.style.display = 'flex';
                }

                if (btnPrev) {
                    btnPrev.disabled = (currentIndex <= 1);
                }
                if (btnNext) {
                    btnNext.disabled = (currentIndex >= totalSlides - 1);
                    btnNext.style.display = 'inline-flex';
                    btnNext.textContent = (currentIndex === totalSlides - 2) ? 'Finalizar Cata →' : 'Siguiente →';
                }
            }

            function goToSlide(idx) {
                var activeSlides = getActiveSlides();
                var totalSlides = activeSlides.length;
                if (idx < 0) idx = 0;
                if (idx >= totalSlides) idx = totalSlides - 1;
                currentIndex = idx;
                updateUI();
            }

            function goToSlideById(slideId) {
                var activeSlides = getActiveSlides();
                for (var i = 0; i < activeSlides.length; i++) {
                    if (activeSlides[i].getAttribute('data-slide-id') === slideId) {
                        goToSlide(i);
                        return;
                    }
                }
            }

            function playAudio() {
                if (!audio) return;
                audio.play().then(function() {
                    isAudioPlaying = true;
                    root.classList.add('is-playing');
                    if (audioText) audioText.textContent = 'Silenciar';
                }).catch(function(err) {
                    console.warn('Audio play was prevented:', err);
                });
            }

            function pauseAudio() {
                if (!audio) return;
                audio.pause();
                isAudioPlaying = false;
                root.classList.remove('is-playing');
                if (audioText) audioText.textContent = 'Música';
            }

            function toggleAudio() {
                if (isAudioPlaying) {
                    pauseAudio();
                } else {
                    playAudio();
                }
            }

            if (btnStart) {
                btnStart.addEventListener('click', function() {
                    if (soundToggle && soundToggle.checked) {
                        playAudio();
                    } else {
                        pauseAudio();
                    }
                    goToSlide(1);
                });
            }

            if (btnRestart) {
                btnRestart.addEventListener('click', function() {
                    goToSlide(0);
                });
            }

            if (btnPrev) {
                btnPrev.addEventListener('click', function() {
                    if (currentIndex > 1) {
                        goToSlide(currentIndex - 1);
                    }
                });
            }

            if (btnNext) {
                btnNext.addEventListener('click', function() {
                    var activeSlides = getActiveSlides();
                    if (currentIndex < activeSlides.length - 1) {
                        goToSlide(currentIndex + 1);
                    }
                });
            }

            if (audioBtn) {
                audioBtn.addEventListener('click', function() {
                    toggleAudio();
                });
            }

            if (mateToggle) {
                mateToggle.addEventListener('change', function() {
                    updateUI();
                });
            }

            var boxCards = root.querySelectorAll('.batllie-cata-box-card[data-goto-chapter]');
            boxCards.forEach(function(card) {
                card.addEventListener('click', function() {
                    var targetChapter = card.getAttribute('data-goto-chapter');
                    if (targetChapter) {
                        goToSlideById(targetChapter);
                    }
                });
            });

            window.addEventListener('keydown', function(e) {
                var activeSlides = getActiveSlides();
                if (e.key === 'ArrowRight' || e.key === ' ') {
                    if (currentIndex < activeSlides.length - 1) {
                        e.preventDefault();
                        goToSlide(currentIndex + 1);
                    }
                } else if (e.key === 'ArrowLeft') {
                    if (currentIndex > 1) {
                        e.preventDefault();
                        goToSlide(currentIndex - 1);
                    }
                }
            });

            updateUI();
        })();
        </script>
        <?php
        return ob_get_clean();
    }

    add_shortcode('batllie_cata', 'batllie_cata_shortcode');
    add_shortcode('emp_cata', 'batllie_cata_shortcode');
}
