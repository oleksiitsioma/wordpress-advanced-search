<?php

class PivotAdvancedSearch{

    public function plugin_activation(){

        return 'hello world';

        // global $wpdb;
    
        // $enginesTableName = $wpdb->prefix . 'advanced_search_engines';
        // $charset_collate = $wpdb->get_charset_collate();
        // register_activation_hook( __FILE__, array( 'My_Plugin', 'on_activate_function' ) );
        
        // $sql = "CREATE TABLE $enginesTableName (
        //     id mediumint(9) NOT NULL AUTO_INCREMENT,
        //     name tinytext NOT NULL,
        //     PRIMARY KEY  (id)
        // ) $charset_collate;";
        
        // require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
        // dbDelta( $sql );
    
    }
}