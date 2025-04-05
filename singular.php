<?php
/**
 * Шаблон для отображения любого одиночного поста/страницы
 * Используется, когда WordPress не может найти single.php или page.php
 */

get_header();
?>

<div class="col-lg-8">
    <main id="primary" class="site-main">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
                <header class="entry-header">
                    <h1 class="post-title"><?php the_title(); ?></h1>
                    
                    <?php if (is_singular('post')) : ?>
                    <div class="post-meta">
                        <span><i class="far fa-calendar-alt"></i> <?php echo get_the_date(); ?></span>
                        <?php if (has_category()) : ?>
                            <span class="ms-3"><i class="far fa-folder-open"></i> <?php the_category(', '); ?></span>
                        <?php endif; ?>
                        <?php if (comments_open()) : ?>
                            <span class="ms-3"><i class="far fa-comment"></i> <?php comments_number('0', '1', '%'); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail mb-4">
                        <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
                    </div>
                <?php endif; ?>

                <div class="post-content">
                    <?php the_content(); ?>
                </div>

                <footer class="entry-footer">
                    <?php
                    if (is_singular('post')) {
                        // Если есть категории
                        if (has_category()) :
                            ?>
                            <div class="post-categories">
                                <strong><?php _e('Категории: ', 'matakov-theme'); ?></strong><?php echo get_the_category_list(', '); ?>
                            </div>
                            <?php
                        endif;

                        // Если есть теги
                        if (has_tag()) :
                            ?>
                            <div class="post-tags mt-2">
                                <strong><?php _e('Теги: ', 'matakov-theme'); ?></strong><?php echo get_the_tag_list('', ', '); ?>
                            </div>
                            <?php
                        endif;
                        
                        // Навигация по постам
                        ?>
                        <div class="post-navigation mt-4">
                            <div class="row">
                                <div class="col-6">
                                    <?php previous_post_link('%link', '<i class="fas fa-arrow-left me-2"></i>%title'); ?>
                                </div>
                                <div class="col-6 text-end">
                                    <?php next_post_link('%link', '%title<i class="fas fa-arrow-right ms-2"></i>'); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </footer>
                
                <?php 
                // Если комментарии открыты или есть хотя бы один комментарий, загружаем шаблон комментариев.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
            </article>
            <?php
        endwhile;
        ?>
    </main>
</div>

<div class="col-lg-4">
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
