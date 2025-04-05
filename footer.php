</div><!-- .row -->
        </div><!-- .container -->
    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="site-info">
                        &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> | 
                        <?php 
                        $made_with_text = get_theme_mod('matakov_footer_made_with', 'Сделано с ❤️');
                        $author_name = get_theme_mod('matakov_theme_author', 'Денис Матаков');
                        echo esc_html($made_with_text) . ' ' . esc_html($author_name);
                        ?>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-links">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'depth'          => 1,
                            'fallback_cb'    => function() {
                                echo '<a href="' . esc_url(home_url('/')) . '">Главная</a>';
                                echo '<a href="' . esc_url(home_url('/about/')) . '">О блоге</a>';
                                echo '<a href="' . esc_url(home_url('/contacts/')) . '">Контакты</a>';
                                echo '<a href="' . esc_url(home_url('/privacy-policy/')) . '">Политика конфиденциальности</a>';
                            }
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div><!-- .site-container -->

<?php wp_footer(); ?>

</body>
</html>
