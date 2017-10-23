<?php

// Add scripts and stylesheets
function startwordpress_scripts() {
	//wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.3.6' );
	//wp_enqueue_style( 'blog', get_template_directory_uri() . '/css/blog.css' );
	//wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '3.3.6', true );
}

add_action( 'wp_enqueue_scripts', 'startwordpress_scripts' );

// Add Google Fonts
function startwordpress_google_fonts() {
				wp_register_style('OpenSans', '//fonts.googleapis.com/css?family=Open+Sans:400,600,700,800');
				wp_enqueue_style( 'OpenSans');
		}

add_action('wp_print_styles', 'startwordpress_google_fonts');

// WordPress Titles
function startwordpress_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() ) {
		return $title;
	}
	// Add the site name.
	$title .= get_bloginfo( 'name' );
	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}
	return $title;
}
add_filter( 'wp_title', 'startwordpress_wp_title', 10, 2 );

add_filter( 'kdmfi_featured_images', function( $featured_images ) {
    $args = array(
        'id' => 'featured-image-mobile',
        'desc' => 'Featured Image for Mobile Screens',
        'label_name' => 'Featured Image Mobile',
        'label_set' => 'Set featured image mobile',
        'label_remove' => 'Remove featured image mobile',
        'label_use' => 'Set featured image mobile',
        'post_type' => array( 'page', 'post' ),
    );

    $featured_images[] = $args;

    return $featured_images;
});


// Custom settings
function custom_settings_add_menu() {
  add_menu_page( 'Custom Settings', 'Custom Settings', 'manage_options', 'custom-settings', 'custom_settings_page', null, 99);
}
add_action( 'admin_menu', 'custom_settings_add_menu' );

// Create Custom Global Settings
function custom_settings_page() { ?>
	<div class="wrap">
		<h1>Custom Settings</h1>
		<form method="post" action="options.php">
			<?php
           settings_fields('section');
           do_settings_sections('theme-options');
           submit_button();
       ?>
		</form>
	</div>
	<?php }

// Twitter
function setting_twitter() { ?>
		<input type="text" name="twitter" id="twitter" value="<?php echo get_option('twitter'); ?>" />
		<?php }

function setting_github() { ?>
			<input type="text" name="github" id="github" value="<?php echo get_option('github'); ?>" />
			<?php }

function custom_settings_page_setup() {
  add_settings_section('section', 'All Settings', null, 'theme-options');
  add_settings_field('twitter', 'Twitter URL', 'setting_twitter', 'theme-options', 'section');
  add_settings_field('github', 'GitHub URL', 'setting_github', 'theme-options', 'section');
  register_setting('section', 'twitter');
  register_setting('section', 'github');
}
add_action( 'admin_init', 'custom_settings_page_setup' );

// Support Featured Images
add_theme_support( 'post-thumbnails' );


function wpdocs_excerpt_more( $more ) {
    return ' ...';
}
add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );

add_filter('excerpt_length', 'my_excerpt_length');
function my_excerpt_length($length) {
return 15; // Or whatever you want the length to be.
}



function display_custom_comments($comment, $args, $depth) {
    $isByAuthor = false;

    if($comment->comment_author_email == get_the_author_meta('email')) {
        $isByAuthor = true;
    }

   $GLOBALS['comment'] = $comment; ?>

   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div style="margin-top:30px;" id="comment-<?php comment_ID(); ?>" <?php if($isByAuthor){ echo 'class="author"';}?>>
      <div class="comment-author vcard valign-wrapper">

         <?php echo get_avatar( $comment->comment_author_email, 52 ); ?>

         <?php printf(__('<span class="comment-name valign">%s</span>'), get_comment_author()) ?><?php //edit_comment_link(__('(Edit)'),'  ','') ?>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <span style="color:#F63"><?php _e('Your comment is awaiting moderation.') ?></span>
         <br />
      <?php endif; ?>
		<div style="font-size:18px; color:#666; text-align:justify; line-height:30px;">
      <?php comment_text() ?>
		</div>
    <!--  <div class="reply">
         <?php //comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>-->
     </div>
    </li>
<?php
}

wp_enqueue_script('jquery');






// Change default WordPress email address
add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');

function new_mail_from($email) {
return 'connect@dhflinsurance.com';
}
function new_mail_from_name($name) {
return 'DHFL General Insurance';
}

//Square Thumbnails

if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'square-large', 500, 280, true); // name, width, height, crop
    add_filter('image_size_names_choose', 'my_image_sizes');
}

function my_image_sizes($sizes) {
    $addsizes = array(
        "square-large" => __( "Large square image")
    );
    $newsizes = array_merge($sizes, $addsizes);
    return $newsizes;
}
