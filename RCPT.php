<?php
/**
 * Plugin Name: Rapid Custom Post Types
 * Plugin URI: http://getabsolute.com
 * Description: Custom Post Types Quickly
 * Version: 1.0
 * Author: Logan Henson
 * Author URI: http://getabsolute.com
 * License: GPL2
 */

//includes
include 'shortcodes.php';
//router for custom admin styles
include 'custom_post_admin_styles.php';
//define fields
include 'custom_post_types.php';

/*-----------------------------------------------------------------------------------*/
/*	Plugin Stuff
/*-----------------------------------------------------------------------------------*/
#Install/Uninstall
register_activation_hook( __FILE__, array( 'Freeman_Mills', 'install' ) );
register_uninstall_hook( __FILE__, array('Freeman_Mills', 'uninstall') );
#Acticate/Deactivate
register_activation_hook( __FILE__, array( 'Freeman_Mills', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Freeman_Mills', 'deactivate' ) );

abstract Class Freeman_Mills{

    static function install() {

    }

    static function uninstall() {

    }

    static function activate(){

    }

    static function deactivate(){

    }

}
?>