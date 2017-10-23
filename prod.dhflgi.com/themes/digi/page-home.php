<?php
/*
	Template Name: Home
*/
?>
<?php get_header(); ?>
<div class="uk-section-small" id="scroll-point">
        <div class="uk-container">
        
          <div class="uk-child-width-expand" uk-grid>
              <div class="uk-width-2-3@m">

          <?php
    // TO SHOW THE PAGE CONTENTS
    while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
        <div class="para-text">
            <?php the_content(); ?> <!-- Page Content -->
        </div><!-- .entry-content-page -->

    <?php
    endwhile; //resetting the page loop
    wp_reset_query(); //resetting the page query
    ?>

  </div>
  <div class="uk-width-1-3@m">

    <?php get_sidebar(); ?>

  </div>
</div>

        </div>
    </div>
<?php get_footer(); ?>
