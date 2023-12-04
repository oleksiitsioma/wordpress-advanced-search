<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once( __DIR__ . '/../settings/plugin-vars.php');

global $wpdb;
$charset_collate = $wpdb->get_charset_collate();

$pairingsSql = "CREATE TABLE $pairingsTableName (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    engine_name TEXT NOT NULL,
    search_queries TEXT NOT NULL,
    import_post_name TEXT NOT NULL,
    post_reference TEXT NOT NULL,
    is_block_level BOOLEAN,
    import_block_name TEXT,
    block_id TEXT, 
    PRIMARY KEY  (id)
) $charset_collate;";

dbDelta( $pairingsSql );