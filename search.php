<?php get_header(); ?>

<div class="col-lg-8">
    <main id="primary" class="site-main">
        <?php if (have_posts()) : ?>
            <header class="page-header">
                <h1 class="page-title">
                    <?php
                    printf(
                        /* translators: %s: поисковый запрос */
                        esc_html__('Результаты поиска для: %s', 'matakov-theme'),
                        '<span>' . get_search_query() . '</span>'
                    );
                    ?>
                </h1>
            </header>

            <?php
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
                        </div>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium', ['class' => 'img-fluid']); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="post-content">
                        <?php echo matakov_formatted_excerpt(15); ?>
                    </div>

                    <footer class="entry-footer">
                        <!-- Кнопку "Читать далее" теперь добавляет наша функция matakov_formatted_excerpt() -->
                    </footer>
                </article>
                <?php
            endwhile;

            echo '<div class="pagination-container">';
            the_posts_pagination(array(
                'prev_text' => '<i class="fas fa-chevron-left" aria-hidden="true"></i><span class="screen-reader-text">Предыдущая</span>',
                'next_text' => '<i class="fas fa-chevron-right" aria-hidden="true"></i><span class="screen-reader-text">Следующая</span>',
                'mid_size'  => 2,
                'before_page_number' => '<span class="meta-nav screen-reader-text">Страница </span>',
                'class' => 'pagination justify-content-center',
            ));
            echo '</div>';

        else :
            ?>
            <div class="alert alert-info">
                <p><?php _e('По вашему запросу ничего не найдено.', 'matakov-theme'); ?></p>
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="search-no-results-form">
                        <p class="mb-3"><?php _e('Попробуйте другие ключевые слова:', 'matakov-theme'); ?></p>
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<div class="col-lg-4">
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
