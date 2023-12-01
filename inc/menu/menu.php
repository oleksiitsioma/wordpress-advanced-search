<?php

add_action( 'admin_menu' , '___pas_create_admin_menu' );

function ___pas_create_admin_menu(){

    $___pas_admin_menu_title    = 'Advanced Search';
    $___pas_admin_menu_slug     = 'advanced-search';
    $___pas_admin_menu_page     = '___pas_admin_menu_page'; 

    add_menu_page(
        $page_title = $___pas_admin_menu_title,
        $menu_title = $___pas_admin_menu_title,
        $capability = 'administrator',
        $menu_slug  = $___pas_admin_menu_slug,
        $function   = '___pas_admin_menu_page',
        $position   = 99
    );

    load_template(
        $_template_file = __DIR__ . '/search-engines.php',
        $require_once   = true,
        $args           = array(
            'menu-slug' => $___pas_admin_menu_slug
        )
    
    );

    load_template(
        $_template_file = __DIR__ . '/search-mapping.php',
        $require_once   = true,
        $args           = array(
            'menu-slug' => $___pas_admin_menu_slug
        )
    
    );

    load_template(
        $_template_file = __DIR__ . '/blocks-mapping.php',
        $require_once   = true,
        $args           = array(
            'menu-slug' => $___pas_admin_menu_slug
        )
    
    );

    load_template(
        $_template_file = __DIR__ . '/support-search.php',
        $require_once   = true,
        $args           = array(
            'menu-slug' => $___pas_admin_menu_slug
        )
    
    );

}