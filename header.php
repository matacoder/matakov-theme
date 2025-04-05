<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-container">
    <header id="masthead" class="site-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <div class="site-branding">
                        <?php
                        if (has_custom_logo()) :
                        ?>
                            <div class="site-logo">
                                <?php the_custom_logo(); ?>
                            </div>
                        <?php
                        else :
                        ?>
                            <div class="site-title-wrapper">
                                <h1 class="site-title">
                                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
                                </h1>
                            
                                <?php
                                $description = get_bloginfo('description', 'display');
                                if ($description || is_customize_preview()) :
                                ?>
                                    <p class="site-description"><?php echo $description; ?></p>
                                <?php endif; ?>
                            </div>
                        <?php
                        endif;
                        
                        // Если есть логотип и описание, выводим отдельно
                        if (has_custom_logo() && (get_bloginfo('description', 'display') || is_customize_preview())) :
                        ?>
                            <div class="site-description-wrapper">
                                <p class="site-description"><?php echo get_bloginfo('description', 'display'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-9">
                    <?php if (is_active_sidebar('header-ad')) : ?>
                        <div class="header-ad-container">
                            <?php dynamic_sidebar('header-ad'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <nav id="site-navigation" class="main-navigation">
                        <nav class="navbar navbar-expand-lg">
                            <div class="container-fluid">
                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarNav">
                                    <?php
                                    wp_nav_menu(array(
                                        'theme_location' => 'primary',
                                        'menu_id' => 'primary-menu',
                                        'container' => false,
                                        'menu_class' => 'navbar-nav',
                                        'fallback_cb' => '__return_false',
                                        'items_wrap' => '<ul id="%1$s" class="%2$s me-auto">%3$s</ul>',
                                        'depth' => 2,
                                        'walker' => new Bootstrap_Nav_Walker()
                                    ));
                                    ?>
                                    <div class="navbar-search">
                                        <?php get_search_form(); ?>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <div id="content" class="site-content">
        <div class="container">
            <div class="row">
