<?php
/**
 * Настройки темы для кастомайзера WordPress
 */

function matakov_theme_customizer($wp_customize) {
    // Добавление секции настроек темы
    $wp_customize->add_section('matakov_theme_colors', array(
        'title'    => __('Цвета темы', 'matakov-theme'),
        'priority' => 30,
    ));

    // Настройка основного цвета
    $wp_customize->add_setting('matakov_primary_color', array(
        'default'           => '#0366d6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'matakov_primary_color',
        array(
            'label'    => __('Основной цвет', 'matakov-theme'),
            'description' => __('Цвет используется для кнопок, ссылок и навигации', 'matakov-theme'),
            'section'  => 'matakov_theme_colors',
            'settings' => 'matakov_primary_color',
        )
    ));

    // Настройка hover-цвета
    $wp_customize->add_setting('matakov_primary_color_hover', array(
        'default'           => '#024ea4',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'matakov_primary_color_hover',
        array(
            'label'    => __('Цвет при наведении', 'matakov-theme'),
            'description' => __('Цвет кнопок и ссылок при наведении', 'matakov-theme'),
            'section'  => 'matakov_theme_colors',
            'settings' => 'matakov_primary_color_hover',
        )
    ));

    // Добавление секции настроек футера
    $wp_customize->add_section('matakov_footer_settings', array(
        'title'    => __('Настройки футера', 'matakov-theme'),
        'priority' => 90,
    ));
    
    // Настройка автора темы
    $wp_customize->add_setting('matakov_theme_author', array(
        'default'           => 'Денис Матаков',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('matakov_theme_author', array(
        'label'    => __('Автор темы', 'matakov-theme'),
        'description' => __('Имя автора, отображаемое в футере', 'matakov-theme'),
        'section'  => 'matakov_footer_settings',
        'type'     => 'text',
    ));
    
    // Настройка текста "Сделано с"
    $wp_customize->add_setting('matakov_footer_made_with', array(
        'default'           => 'Сделано с ❤️',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('matakov_footer_made_with', array(
        'label'    => __('Текст "Сделано с"', 'matakov-theme'),
        'description' => __('Текст перед именем автора', 'matakov-theme'),
        'section'  => 'matakov_footer_settings',
        'type'     => 'text',
    ));
}
add_action('customize_register', 'matakov_theme_customizer');

/**
 * Генерирует CSS с пользовательскими настройками.
 */
function matakov_customize_css() {
    $primary_color = get_theme_mod('matakov_primary_color', '#0366d6');
    $primary_color_hover = get_theme_mod('matakov_primary_color_hover', '#024ea4');
    $primary_color_light = hex_to_rgba($primary_color, 0.1);
    ?>
    <style type="text/css">
        :root {
            --primary-color: <?php echo sanitize_hex_color($primary_color); ?>;
            --primary-color-hover: <?php echo sanitize_hex_color($primary_color_hover); ?>;
            --primary-color-light: <?php echo $primary_color_light; ?>;
        }
        
        .main-navigation,
        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-color-hover) !important;
            border-color: var(--primary-color-hover) !important;
        }
        
        a, .site-title a:hover, .post-meta i {
            color: var(--primary-color);
        }
        
        .main-navigation .navbar-nav .nav-link:hover,
        .main-navigation .navbar-nav .nav-item.active .nav-link {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
    <?php
}
add_action('wp_head', 'matakov_customize_css');

/**
 * Добавляем JavaScript для предпросмотра изменений в кастомайзере
 */
function matakov_customize_preview_js() {
    wp_enqueue_script('matakov-customizer', get_template_directory_uri() . '/js/customizer.js', array('jquery', 'customize-preview'), MATAKOV_VERSION, true);
}
add_action('customize_preview_init', 'matakov_customize_preview_js');

/**
 * Преобразование HEX-цвета в RGBA
 */
function hex_to_rgba($color, $opacity = false) {
    $default = 'rgba(0,0,0,0.1)';
    
    // Возврат по умолчанию, если цвет не указан
    if(empty($color)) return $default;
    
    // Удаление символа '#' если он есть
    $color = str_replace('#', '', $color);

    // Проверка длины строки
    if(strlen($color) == 6) {
        $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
    } elseif(strlen($color) == 3) {
        $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
    } else {
        return $default;
    }
    
    // Преобразование hex в rgb
    $rgb = array_map('hexdec', $hex);
    
    // Возвращение RGB или RGBA строки
    if($opacity !== false) {
        return 'rgba(' . implode(',', $rgb) . ',' . $opacity . ')';
    }
    return 'rgb(' . implode(',', $rgb) . ')';
}
