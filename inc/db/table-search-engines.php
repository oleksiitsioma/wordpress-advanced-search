<?php 

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once( __DIR__ . '/../settings/plugin-vars.php');

global $wpdb;
$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE $enginesTableName (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    engine_name TEXT, 
    engine_label TEXT NOT NULL,
    import_url TEXT NOT NULL,
    PRIMARY KEY  (id)
) $charset_collate;";    

dbDelta( $sql );