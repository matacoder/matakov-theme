<?php
/**
 * Bootstrap 5 Nav Walker для WordPress
 */

if (!class_exists('Bootstrap_Nav_Walker')):

class Bootstrap_Nav_Walker extends Walker_Nav_Menu {
    /**
     * Starts the element output.
     *
     * @since 3.0.0
     * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
     * @since 5.9.0 Renamed `$item` to `$data_object` and `$id` to `$current_object_id`
     *              to match parent class for PHP 8 named parameter support.
     *
     * @param string   $output            Used to append additional content (passed by reference).
     * @param WP_Post  $data_object       Menu item data object.
     * @param int      $depth             Depth of menu item. Used for padding.
     * @param stdClass $args              An object of wp_nav_menu() arguments.
     * @param int      $current_object_id Optional. ID of the current menu item. Default 0.
     */
    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
        // Restores the more descriptive, specific name for use within this method.
        $menu_item = $data_object;

        if (!is_object($args)) {
            return;
        }
        
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $classes = empty($menu_item->classes) ? array() : (array) $menu_item->classes;
        $classes[] = 'menu-item-' . $menu_item->ID;
        $classes[] = 'nav-item';
        
        // Добавляем active класс к текущему элементу
        if (in_array('current-menu-item', $classes, true) || in_array('current-menu-parent', $classes, true)) {
            $classes[] = 'active';
        }
        
        // Проверяем, есть ли у элемента дочерние элементы
        $has_children = in_array('menu-item-has-children', $classes, true);
        if ($has_children && $depth === 0) {
            $classes[] = 'dropdown';
        }
        
        /**
         * Filters the arguments for a single nav menu item.
         *
         * @since 4.4.0
         *
         * @param stdClass $args      An object of wp_nav_menu() arguments.
         * @param WP_Post  $menu_item Menu item data object.
         * @param int      $depth     Depth of menu item. Used for padding.
         */
        $args = apply_filters('nav_menu_item_args', $args, $menu_item, $depth);
        
        /**
         * Filters the CSS classes applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string[] $classes   Array of the CSS classes that are applied to the menu item's `<li>` element.
         * @param WP_Post  $menu_item The current menu item object.
         * @param stdClass $args      An object of wp_nav_menu() arguments.
         * @param int      $depth     Depth of menu item. Used for padding.
         */
        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $menu_item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string   $menu_id   The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post  $menu_item The current menu item.
         * @param stdClass $args      An object of wp_nav_menu() arguments.
         * @param int      $depth     Depth of menu item. Used for padding.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names . '>';
        
        $atts = array();
        $atts['title']  = !empty($menu_item->attr_title) ? $menu_item->attr_title : '';
        $atts['target'] = !empty($menu_item->target) ? $menu_item->target : '';
        if ('_blank' === $menu_item->target && empty($menu_item->xfn)) {
            $atts['rel'] = 'noopener';
        } else {
            $atts['rel'] = $menu_item->xfn;
        }

        // Если это элемент с дочерними элементами на верхнем уровне, сделаем его выпадающим меню
        if ($has_children && $depth === 0) {
            // Url элемента с подменю
            $atts['href'] = !empty($menu_item->url) && $menu_item->url !== '#' ? $menu_item->url : '#';
            $atts['class'] = 'nav-link dropdown-toggle';
            $atts['data-bs-toggle'] = 'dropdown';
            $atts['data-bs-auto-close'] = 'outside';
            $atts['aria-expanded'] = 'false';
            $atts['role'] = 'button';
            $atts['id'] = 'navbarDropdown' . $menu_item->ID;
        }
        // Если это подэлемент выпадающего меню
        else if ($depth >= 1) {
            $atts['href'] = !empty($menu_item->url) ? $menu_item->url : '#';
            $atts['class'] = 'dropdown-item';
            
            // Добавляем active класс к текущему элементу
            if (in_array('current-menu-item', $classes, true) || in_array('current-menu-parent', $classes, true)) {
                $atts['class'] .= ' active';
            }
        }
        // Обычный элемент меню верхнего уровня
        else {
            $atts['href'] = !empty($menu_item->url) ? $menu_item->url : '#';
            $atts['class'] = 'nav-link';
            
            // Добавляем active класс к текущему элементу
            if (in_array('current-menu-item', $classes, true) || in_array('current-menu-parent', $classes, true)) {
                $atts['class'] .= ' active';
                $atts['aria-current'] = 'page';
            }
        }
        
        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         */
        $atts = apply_filters('nav_menu_link_attributes', $atts, $menu_item, $args, $depth);
        
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters('the_title', $menu_item->title, $menu_item->ID);
        
        /**
         * Filters a menu item's title.
         */
        $title = apply_filters('nav_menu_item_title', $title, $menu_item, $args, $depth);
        
        $item_output  = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        /**
         * Filters a menu item's starting output.
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args);
    }
    
    /**
     * Starts the list before the elements are added.
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        
        // Добавим атрибут к родительскому dropdown menu
        $dropdown_id = '';
        if (preg_match('/id="navbarDropdown(\d+)"/', $output, $matches)) {
            $dropdown_id = $matches[1];
        }
        
        $classes = array('dropdown-menu');
        $class_names = implode(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $output .= "{$n}{$indent}<ul$class_names aria-labelledby=\"navbarDropdown$dropdown_id\">{$n}";
    }
    
    /**
     * Adds element for dropdown items
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        
        $output .= "{$n}";
        $output .= "</li>{$n}";
    }
}

endif;
