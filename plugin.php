<?php

/*

Plugin Name: Advanced Search

*/

// Create DB Table id doesn't exist

add_action( 'admin_enqueue_scripts' , '___pas_admin_scripts' );

function ___pas_admin_scripts(){

    wp_enqueue_script(
        $handle     = 'pas',
        $src        = plugin_dir_url( __FILE__ ) . 'build/bundle-script.js',
        $deps       = array('jquery'),
        $ver        = null,
        $in_footer  = true
    );

    wp_enqueue_style(
        $handle     = 'pas',
        $src        = plugin_dir_url( __FILE__ ) . 'build/bundle-style.css',
        $deps       = null,
        $ver        = null
    );

}

add_action( 'wp_enqueue_scripts' , '___pas_frontend_styles' );

function ___pas_frontend_styles(){

    wp_enqueue_style(
        $handle     = 'pas-frontend',
        $src        = plugin_dir_url( __FILE__ ) . '/build/frontend-style.css',
        $deps       = null,
        $ver        = null
    );

}

global $wpdb;
$charset_collate = $wpdb->get_charset_collate();

$enginesTableName = $wpdb->prefix . 'advanced_search_engines';
$mappingTableName = $wpdb->prefix . 'advanced_search_mapping';
$pairingsTableName = $wpdb->prefix . 'advanced_search_pairings';

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

$sql = "CREATE TABLE $enginesTableName (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    engine_label TEXT NOT NULL,
    engine_name TEXT, 
    import_url TEXT NOT NULL,
    PRIMARY KEY  (id)
) $charset_collate;";    

dbDelta( $sql );

$mappingSql = "CREATE TABLE $mappingTableName (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    engine_name TEXT NOT NULL,
    import_post_name TEXT NOT NULL,
    post_type TEXT,
    post_reference TEXT,
    PRIMARY KEY  (id)
) $charset_collate;";

dbDelta( $mappingSql );

$pairingsSql = "CREATE TABLE $pairingsTableName (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    engine_name TEXT NOT NULL,
    search_query TEXT NOT NULL,
    post_reference TEXT NOT NULL,
    is_block_level BOOLEAN,
    block_id TEXT, 
    PRIMARY KEY  (id)
) $charset_collate;";

dbDelta( $pairingsSql );


require_once __DIR__ . '/inc/menu/menu.php';


function ___pas_admin_menu_page(){

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
