<?php
/**
 * Matakov Theme функции и определения
 */

require get_template_directory() . '/inc/bootstrap-nav-walker.php';
require get_template_directory() . '/inc/customizer.php';

if (!defined('MATAKOV_VERSION')) {
    define('MATAKOV_VERSION', '1.1.7');
}

/**
 * Настройка темы по умолчанию и регистрация поддержки различных функций WordPress.
 */
function matakov_setup() {
    // Добавление поддержки RSS фидов для записей и комментариев
    add_theme_support('automatic-feed-links');
    
    // Позволяем WordPress управлять заголовком документа
    add_theme_support('title-tag');
    
    // Включаем поддержку миниатюр для записей и страниц
    add_theme_support('post-thumbnails');
    
    // Поддержка Full Site Editing и шаблонов блоков (для WP 6.0+)
    add_theme_support('block-templates');
    
    // Поддержка нового формата блоков в виджетах для WP 5.8+
    add_theme_support('widgets-block-editor');
    
    // Поддержка стилей блоков
    add_theme_support('wp-block-styles');
    
    // Поддержка широкого выравнивания в Gutenberg
    add_theme_support('align-wide');
    
    // Поддержка HTML5 разметки для search-form и других элементов
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
        'navigation-widgets',
    ));
    
    // Эта тема использует wp_nav_menu() в одном месте
    register_nav_menus(array(
        'primary' => esc_html__('Основное меню', 'matakov-theme'),
        'footer' => esc_html__('Меню в подвале', 'matakov-theme'),
    ));
    
    // Поддержка административного бара
    add_theme_support('admin-bar', array('callback' => '__return_true'));
    
    // Поддержка избранного изображения для блогов
    add_theme_support('custom-header');
    
    // Установка размеров миниатюр
    set_post_thumbnail_size(1200, 9999);
    add_image_size('matakov-thumbnail', 350, 230, true);
    
    // Поддержка кастомного логотипа
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 320,
        'flex-height' => true,
        'flex-width'  => true,
        'unlink-homepage-logo' => true,
    ));
    
    // Поддержка цветовых настроек в кастомайзере
    add_theme_support('customize-selective-refresh-widgets');
    
    // Поддержка для отзывчивых эмбедов (видео и т.д.)
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'matakov_setup');

/**
 * Подключение скриптов и стилей.
 */
function matakov_scripts() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2');
    
    // Font Awesome для иконок
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
    
    // Основной стиль темы
    wp_enqueue_style('matakov-style', get_stylesheet_uri(), array('bootstrap'), MATAKOV_VERSION);
    
    // Дополнительные стили для меню
    wp_enqueue_style('matakov-menu', get_template_directory_uri() . '/css/custom-menu.css', array('matakov-style'), MATAKOV_VERSION);
    
    // Google шрифты Inter
    wp_enqueue_style('matakov-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap', array(), null);
    
    // jQuery (используем версию из WordPress)
    wp_enqueue_script('jquery');
    
    // Bootstrap JS + Popper
    wp_enqueue_script('bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.2', true);
    
    // Основной скрипт темы
    wp_enqueue_script('matakov-script', get_template_directory_uri() . '/js/script.js', array('jquery', 'bootstrap-bundle'), MATAKOV_VERSION, true);
    
    // Добавляем информацию для JS
    wp_localize_script('matakov-script', 'matakovData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'homeurl' => esc_url(home_url('/')),
        'themeurl' => get_template_directory_uri(),
    ));
    
    // Добавляем скрипт для комментариев если они открыты
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'matakov_scripts');

/**
 * Регистрация боковых колонок.
 */
function matakov_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Сайдбар', 'matakov-theme'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Добавьте виджеты сюда.', 'matakov-theme'),
        'before_widget' => '<section id="%1$s" class="sidebar-widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Рекламный блок в шапке', 'matakov-theme'),
        'id'            => 'header-ad',
        'description'   => esc_html__('Область для рекламы справа от заголовка.', 'matakov-theme'),
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="d-none">',
        'after_title'   => '</span>',
    ));
}
add_action('widgets_init', 'matakov_widgets_init');

/**
 * Фильтр для обрезки длины отрывка
 */
function matakov_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'matakov_excerpt_length');

/**
 * Фильтр для замены [...] на ... в отрывках
 */
function matakov_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'matakov_excerpt_more');

/**
 * Удаление версии WordPress для безопасности
 */
function matakov_remove_version() {
    return '';
}
add_filter('the_generator', 'matakov_remove_version');

/**
 * Добавление дополнительных классов для тегов изображений
 */
function matakov_add_image_class($html) {
    return str_replace('class="', 'class="img-fluid ', $html);
}
add_filter('get_image_tag', 'matakov_add_image_class');

/**
 * Обертка для iframe (видео, карты и т.д.) для адаптивности
 */
function matakov_responsive_embeds($html) {
    if (strpos($html, 'iframe') !== false) {
        return '<div class="ratio ratio-16x9">' . $html . '</div>';
    }
    return $html;
}
add_filter('embed_oembed_html', 'matakov_responsive_embeds', 10, 3);

/**
 * Добавление поддержки SVG загрузок для администраторов
 */
function matakov_allow_svg_upload($mimes) {
    if (current_user_can('administrator')) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
}
add_filter('upload_mimes', 'matakov_allow_svg_upload');

/**
 * Регистрация скриптов для блочного редактора
 */
function matakov_block_editor_assets() {
    // Стили для блочного редактора
    wp_enqueue_style(
        'matakov-block-editor-styles',
        get_theme_file_uri('/css/editor-styles.css'),
        array(),
        MATAKOV_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'matakov_block_editor_assets');

/**
 * Создает превью поста с сохранением форматирования и отступов
 * 
 * @param int $max_lines Максимальное количество строк для отображения
 * @param string $more_text Текст для кнопки "Читать далее"
 * @return string Отформатированное превью поста
 */
function matakov_formatted_excerpt($max_lines = 20, $more_text = 'Читать далее') {
    global $post;
    $content = $post->post_content;
    
    // Сохраняем шорткоды и форматирование
    $content = apply_filters('the_content', $content);
    
    // Удаляем лишние обертки, которые могут добавлять блоки Gutenberg
    $content = preg_replace('/<div class="wp-block[^>]*>(.*?)<\/div>/s', '$1', $content);
    
    // Разбиваем контент на параграфы
    $paragraphs = preg_split('/<\/p>|<br\s*\/?>/i', $content);
    
    $output = '';
    $lines_count = 0;
    $reached_limit = false;
    
    foreach ($paragraphs as $paragraph) {
        // Пропускаем пустые параграфы
        if (trim(strip_tags($paragraph)) === '') {
            continue;
        }
        
        // Очищаем от лишних тегов, чтобы правильно посчитать строки
        $clean_text = strip_tags($paragraph);
        
        // Грубая оценка количества строк (в среднем 100 символов на строку)
        $estimated_lines = ceil(mb_strlen($clean_text) / 100);
        
        // Если после добавления этого параграфа превысим лимит строк
        if ($lines_count + $estimated_lines > $max_lines) {
            // Добавляем часть текста до лимита строк
            $words = explode(' ', $clean_text);
            $words_limit = min(count($words), floor(($max_lines - $lines_count) * 100 / 5)); // ~5 символов на слово
            
            if ($words_limit > 0) {
                $partial_text = implode(' ', array_slice($words, 0, $words_limit));
                // Восстанавливаем HTML-теги в начале параграфа
                $pattern = preg_quote($clean_text, '/');
                $paragraph = preg_replace('/^(.*)' . $pattern . '/s', '$1' . $partial_text, $paragraph, 1);
                $output .= $paragraph . '...';
            }
            
            $reached_limit = true;
            break;
        }
        
        // Иначе добавляем весь параграф
        $output .= $paragraph . '</p>';
        $lines_count += $estimated_lines;
        
        // Если достигли лимита после добавления этого параграфа
        if ($lines_count >= $max_lines) {
            $reached_limit = true;
            break;
        }
    }
    
    // Восстанавливаем закрывающие теги
    $output = force_balance_tags($output);
    
    // Добавляем кнопку "Читать далее", если контент был обрезан
    if ($reached_limit) {
        $more_link = sprintf(
            '<p class="more-link-wrapper"><a href="%s" class="btn btn-primary btn-sm">%s &raquo;</a></p>',
            esc_url(get_permalink()),
            esc_html($more_text)
        );
        $output .= $more_link;
    }
    
    return $output;
}

/**
 * Заменяет стандартный вывод превью на форматированный
 */
function matakov_custom_excerpt() {
    echo matakov_formatted_excerpt();
}

/**
 * Изменяет стандартный вывод превью в теме
 */
function matakov_excerpt_filter($excerpt) {
    if (is_single() || is_page()) {
        return $excerpt;
    }
    
    if (has_excerpt()) {
        // Если есть отрывок, заданный вручную, оставляем его
        return $excerpt . sprintf(
            '<p class="more-link-wrapper"><a href="%s" class="btn btn-primary btn-sm">%s &raquo;</a></p>',
            esc_url(get_permalink()),
            esc_html__('Читать далее', 'matakov-theme')
        );
    } else {
        // Используем наш форматированный отрывок
        return matakov_formatted_excerpt();
    }
}
add_filter('the_excerpt', 'matakov_excerpt_filter');

/**
 * Отключает автоматическое добавление абзацев в короткие описания
 */
remove_filter('the_excerpt', 'wpautop');

/**
 * Создает продвинутую пагинацию для кастомных запросов
 * 
 * @param object $query Объект WP_Query (если null, используется глобальный)
 * @param array $args Дополнительные аргументы пагинации
 */
function matakov_custom_pagination($query = null, $args = array()) {
    global $wp_query;
    
    // Если запрос не указан, используем глобальный
    if ($query === null) {
        $query = $wp_query;
    }
    
    // Получаем общее количество страниц
    $total = $query->max_num_pages;
    
    // Если только одна страница, не показываем пагинацию
    if ($total <= 1) {
        return;
    }
    
    // Текущая страница
    $current = max(1, get_query_var('paged'));
    
    // Базовый URL для страниц
    $base = get_pagenum_link(1);
    
    // Настройки по умолчанию
    $defaults = array(
        'total' => $total,
        'current' => $current,
        'show_all' => false,
        'prev_next' => true,
        'prev_text' => '<i class="fas fa-chevron-left" aria-hidden="true"></i><span class="screen-reader-text">Предыдущая</span>',
        'next_text' => '<i class="fas fa-chevron-right" aria-hidden="true"></i><span class="screen-reader-text">Следующая</span>',
        'mid_size' => 2,
        'end_size' => 1,
        'type' => 'list',
        'add_args' => array(),
        'add_fragment' => '',
        'before_page_number' => '<span class="meta-nav screen-reader-text">Страница </span>',
        'after_page_number' => '',
    );
    
    // Объединяем с пользовательскими настройками
    $args = wp_parse_args($args, $defaults);
    
    // Выводим пагинацию в контейнере
    echo '<div class="pagination-container">';
    echo paginate_links($args);
    echo '</div>';
}
