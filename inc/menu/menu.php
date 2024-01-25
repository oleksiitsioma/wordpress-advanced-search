<?php

add_action( 'admin_menu' , '___was_create_admin_menu' );

function ___was_create_admin_menu(){
    
    global $wpdb , $enginesTableName;
    $searchEnginesSQL = "SELECT * FROM {$enginesTableName}";
    $searchEngines = $wpdb->get_results( $searchEnginesSQL );

    $___was_admin_menu_title    = 'Advanced Search';
    $___was_admin_menu_slug     = 'advanced-search';
    $___was_admin_menu_page     = '___was_admin_menu_page'; 

    add_menu_page(
        $page_title = $___was_admin_menu_title,
        $menu_title = $___was_admin_menu_title,
        $capability = 'administrator',
        $menu_slug  = $___was_admin_menu_slug,
        $function   = '___was_admin_menu_page',
        $position   = 99
    );

    load_template(
        $_template_file = WAS_PLUGIN_DIR . 'inc/menu/search-engines.php',
        $require_once   = true,
        $args           = array(
            'menu-slug' => $___was_admin_menu_slug
        )
    
    );

    foreach ($searchEngines as $engine) {

        load_template(
            $_template_file = WAS_PLUGIN_DIR . 'inc/menu/search-engine-settings.php',
            $require_once   = true,
            $args           = array(
                'menu-slug' => $___was_admin_menu_slug,
                'engine'    => $engine
            )
        );

    }

}