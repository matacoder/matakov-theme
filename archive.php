<?php get_header(); ?>

<div class="col-lg-8">
    <main id="primary" class="site-main">
        <?php if (have_posts()) : ?>
            <header class="page-header mb-4">
                <h1 class="page-title">
                    <?php
                    if (is_category()) {
                        echo '<i class="fas fa-folder-open me-2"></i>' . single_cat_title('', false);
                    } elseif (is_tag()) {
                        echo '<i class="fas fa-tag me-2"></i>' . single_tag_title('', false);
                    } elseif (is_author()) {
                        echo '<i class="fas fa-user me-2"></i>';
                        the_author();
                    } elseif (is_date()) {
                        echo '<i class="fas fa-calendar-alt me-2"></i>';
                        if (is_day()) {
                            printf(__('Архив: %s', 'matakov-theme'), get_the_date());
                        } elseif (is_month()) {
                            printf(__('Архив: %s', 'matakov-theme'), get_the_date(_x('F Y', 'monthly archives date format', 'matakov-theme')));
                        } elseif (is_year()) {
                            printf(__('Архив: %s', 'matakov-theme'), get_the_date(_x('Y', 'yearly archives date format', 'matakov-theme')));
                        }
                    } else {
                        _e('Архив', 'matakov-theme');
                    }
                    ?>
                </h1>
                <?php
                // Если есть описание категории, покажем его
                if (is_category() || is_tag() && term_description()) : 
                ?>
                <div class="archive-description">
                    <?php echo term_description(); ?>
                </div>
                <?php endif; ?>
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
                            <?php if (comments_open()) : ?>
                                <span class="ms-3"><i class="far fa-comment"></i> <?php comments_number('0', '1', '%'); ?></span>
                            <?php endif; ?>
                        </div>
                    </header>

                    <div class="row post-content-wrapper">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-content">
                                <div class="post-thumbnail float-md-start me-md-4 mb-3">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', ['class' => 'img-fluid']); ?>
                                    </a>
                                </div>
                                <?php echo matakov_formatted_excerpt(15); ?>
                            </div>
                        <?php else : ?>
                            <div class="col-12">
                                <div class="post-content">
                                    <?php echo matakov_formatted_excerpt(15); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php
            endwhile;

            // Используем нашу кастомную функцию пагинации
            matakov_custom_pagination();

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