<?php get_header(); ?>

<div class="uk-section-small" id="scroll-point">
        <div class="uk-container">

          <div class="uk-child-width-expand" uk-grid>
              <div class="uk-width-2-3@m">

			<?php
			if ( have_posts() ) : while ( have_posts() ) : the_post();

				get_template_part( 'content', get_post_format() );

			endwhile; endif;
			?>

    </div>
    <div class="uk-width-1-3@m">

		<?php get_sidebar(); ?>

  </div>
</div>

        </div>
    </div>

<?php get_footer(); ?>
