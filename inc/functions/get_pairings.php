<?php 

require_once ( __DIR__ . '/../settings/plugin-vars.php');

function get_pairings( ) {

    global $wpdb , $pairingsTableName , $mappingTableName;

    $savedMappings = $wpdb->get_results("
        SELECT *
        FROM {$mappingTableName}
    ");

    if( sizeof( $savedMappings ) == 0 ){

        return false;
        
    }

    foreach ($savedMappings as $mapping){

        $blocks = [];

        $savedQueries = $wpdb->get_results("
            SELECT *
            FROM {$pairingsTableName}
            WHERE import_post_name = '{$mapping->import_post_name}'
        ");

        foreach ($savedQueries as $query) {

            if( !in_array( $query->block_id , $blocks) ){

                array_push( $blocks , $query );

            }

        };

    }

    if( sizeof( $blocks ) > 0 ){

        return $blocks;
        
    } else {

        return false;

    }

}