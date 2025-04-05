<?php get_header(); ?>

<div class="col-lg-8">
    <main id="primary" class="site-main">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="post-title"><?php the_title(); ?></h1>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail mb-4">
                        <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
                    </div>
                <?php endif; ?>

                <div class="post-content">
                    <?php the_content(); ?>
                </div>
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
