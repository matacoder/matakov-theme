<?php get_header(); ?>

<div class="col-lg-8">
    <main id="primary" class="site-main">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
                    <header class="entry-header">
                        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="post-meta">
                            <span><i class="far fa-calendar-alt"></i> <?php echo get_the_date(); ?></span>
                            <?php if (has_category()) : ?>
                                <span class="ms-3"><i class="far fa-folder-open"></i> <?php the_category(', '); ?></span>
                            <?php endif; ?>
                            <?php if (comments_open()) : ?>
                                <span class="ms-3"><i class="far fa-comment"></i> <?php comments_number('0', '1', '%'); ?></span>
                            <?php endif; ?>
                        </div>
                    </header>

                    <div class="row post-content-wrapper">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="col-md-6">
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium_large', ['class' => 'img-fluid']); ?>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="post-content">
                                    <?php 
                                    // Используем наш новый метод вывода отрывка
                                    echo matakov_formatted_excerpt(10); // Меньше строк при наличии миниатюры
                                    ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="col-12">
                                <div class="post-content">
                                    <?php echo matakov_formatted_excerpt(20); // Больше строк без миниатюры ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <footer class="entry-footer">
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm"><?php _e('Читать далее →', 'matakov-theme'); ?></a>
                    </footer>
                </article>
                <?php
            endwhile;

            echo '<div class="pagination-container">';
            the_posts_pagination(array(
                'prev_text' => '<i class="fas fa-chevron-left"></i>',
                'next_text' => '<i class="fas fa-chevron-right"></i>',
                'class' => 'pagination justify-content-center',
            ));
            echo '</div>';
            
        else :
            ?>
            <div class="alert alert-info">
                <p><?php _e('Записей не найдено.', 'matakov-theme'); ?></p>
            </div>
            <?php
        endif;
        ?>
    </main>
</div>

<div class="col-lg-4">
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
