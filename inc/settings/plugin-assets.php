<?php 

// Admin Assets

add_action( 'admin_enqueue_scripts' , '___pas_admin_scripts' );

function ___pas_admin_scripts(){

    global $args;

    wp_enqueue_script(
        $handle     = 'pas',
        $src        = $args['pluginURL'] . 'build/bundle-script.js',
        $deps       = array('jquery'),
        $ver        = null,
        $in_footer  = true
    );

    wp_enqueue_style(
        $handle     = 'pas',
        $src        = $___pas_constants['pluginURL'] . 'build/bundle-style.css',
        $deps       = null,
        $ver        = null
    );

}

// Frontend Assets

add_action( 'wp_enqueue_scripts' , '___pas_frontend_styles' );

function ___pas_frontend_styles(){

    wp_enqueue_style(
        $handle     = 'pas-frontend',
        $src        = plugin_dir_url( __FILE__ ) . '/build/frontend-style.css',
        $deps       = null,
        $ver        = null
    );

}