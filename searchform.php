<?php
/**
 * Форма поиска для темы
 *
 * @package Matakov_Theme
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="s"><?php _e('Поиск:', 'matakov-theme'); ?></label>
    <input type="search" class="search-field" placeholder="<?php echo esc_attr_x('Поиск...', 'placeholder', 'matakov-theme'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <button type="submit" class="search-submit">
        <i class="fas fa-search"></i>
        <span class="screen-reader-text"><?php echo _x('Искать', 'submit button', 'matakov-theme'); ?></span>
    </button>
</form>
