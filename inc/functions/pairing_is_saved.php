<?php 

function pairing_is_saved( $import_post_name ){

    global $wpdb , $pairingsTableName;

    $results = $wpdb->get_results("
        SELECT *
        FROM {$pairingsTableName}
        WHERE import_post_name = '{$import_post_name}'
    ");

    return boolval( $results );

}