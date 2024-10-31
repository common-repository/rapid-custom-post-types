<?php

/*-----------------------------------------------------------------------------------*/
/*	Load assets for custom post type creation screens
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_print_scripts-post-new.php', 'load_assets', 11 );
add_action( 'admin_print_scripts-post.php', 'load_assets', 11 );
function load_assets() {
        //color picker wordpress stuff
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'custom-post-type-js', plugins_url( '/js/custom_post_type_js.js', __FILE__), array('jquery', 'wp-color-picker'),false, true);
        wp_enqueue_style( 'custom-post-type-css', plugins_url( '/css/custom_post_type_css.css', __FILE__));
        wp_enqueue_style( 'font-awesome', "//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" );

}

?>