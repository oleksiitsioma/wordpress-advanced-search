<?php 

// Admin Assets

add_action( 'admin_enqueue_scripts' , '___was_admin_scripts' );

function ___was_admin_scripts(){

    global $args;

    wp_enqueue_script(
        $handle     = 'was',
        $src        = $args['pluginURL'] . 'build/bundle-script.js',
        $deps       = array('jquery'),
        $ver        = null,
        $in_footer  = true
    );

    wp_enqueue_style(
        $handle     = 'was',
        $src        = $___was_constants['pluginURL'] . 'build/bundle-style.css',
        $deps       = null,
        $ver        = null
    );

}

// Frontend Assets

add_action( 'wp_enqueue_scripts' , '___was_frontend_styles' );

function ___was_frontend_styles(){

    wp_enqueue_style(
        $handle     = 'was-frontend',
        $src        = plugin_dir_url( __FILE__ ) . '/build/frontend-style.css',
        $deps       = null,
        $ver        = null
    );

}