<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once( __DIR__ . '/../settings/plugin-vars.php');

global $wpdb;
$charset_collate = $wpdb->get_charset_collate();

$mappingSql = "CREATE TABLE $mappingTableName (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    engine_name TEXT NOT NULL,
    import_post_name TEXT NOT NULL,
    post_type TEXT,
    post_reference TEXT,
    PRIMARY KEY  (id)
) $charset_collate;";

dbDelta( $mappingSql );