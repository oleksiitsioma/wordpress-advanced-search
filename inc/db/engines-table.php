<?php

register_activation_hook( __FILE__ , '___pas_create_engines_table' );

function ___pas_create_engines_table(){

    global $wpdb;

    $enginesTableName = $wpdb->prefix . 'advanced_search_engines';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $enginesTableName (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    dbDelta( $sql );

}