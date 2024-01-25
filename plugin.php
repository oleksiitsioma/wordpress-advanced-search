<?php

/*

Plugin Name: Wordpress Advanced Search
Author: Oleksii Tsioma
Author URI: https://github.com/oleksiitsioma/

*/

// Admin Assets

/**
 * Defining Plugin Constants
 */

define( 'WAS_PLUGIN_DIR' , ABSPATH . 'wp-content/plugins/advanced-search/' );
define( 'WAS_PLUGIN_DIR_URL' , plugin_dir_url( __FILE__ ) );

/**
 * Connecting plugin Resources, Defining Variables, And Creating Database Tables
 */
add_action( 'admin_enqueue_scripts' , '___was_admin_scripts' );

function ___was_admin_scripts(){

    wp_enqueue_script(
        $handle     = 'was',
        $src        = WAS_PLUGIN_DIR_URL . 'build/bundle-script.js',
        $deps       = array('jquery'),
        $ver        = null,
        $in_footer  = true
    );

    wp_enqueue_style(
        $handle     = 'was',
        $src        = WAS_PLUGIN_DIR_URL . 'build/bundle-style.css',
        $deps       = null,
        $ver        = null
    );

}
// Frontend Assets

add_action( 'wp_enqueue_scripts' , '___was_frontend_styles' );

function ___was_frontend_styles(){

    wp_enqueue_style(
        $handle     = 'was-frontend',
        $src        = plugin_dir_url( __FILE__ ) . '/build/frontend-style.css',
        $deps       = null,
        $ver        = null
    );

}

require_once ( __DIR__ . '/inc/db/db.php' );

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

require_once( __DIR__ . '/inc/settings/plugin-vars.php' );

require_once( __DIR__ . '/inc/menu/menu.php');


global $wpdb , $enginesTableName;

$searchEnginesSQL = "SELECT * FROM {$enginesTableName}";

$searchEngines = $wpdb->get_results( $searchEnginesSQL );


function ___was_admin_menu_page(){

    global $wpdb;

    echo '<pre>';

    if( isset( $_POST) ){

        foreach ($_POST as $key => $value) {

            if( strpos( $key , 'queries') ){

                $id = substr( $key, 0, strpos( $key , 'queries' ) - 1 );
                $queries = explode( '; ' , $_POST[$id . '-queries'] );
                $post_reference = $_POST[$id . '-postID'];
                $block_reference = $_POST[$id . '-block'];


                foreach ($queries as $query) {

                    $ifQueryExists = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}advanced_search_pairings WHERE search_query = '{$query}'" );

                    if ( $ifQueryExists ){
                        $wpdb->query("
                            UPDATE {$wpdb->prefix}advanced_search_pairings 
                            SET post_reference  = '{$post_reference}'
                            WHERE search_query  = '{$query}'
                        ");
                    } else {
                        $wpdb->query("
                            INSERT INTO {$wpdb->prefix}advanced_search_pairings ( engine_name , search_query , post_reference , is_block_level , block_id )
                            VALUES ( 'support-search' , '{$query}' , '{$post_reference}' , true , '{$block_reference}' )
                        ");
                    }


                }



            }

        }
    }

    echo '</pre>';

?>


<div class="paSearch">

 

</div>



<?php } ?>
