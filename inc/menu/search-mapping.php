<?php 

add_submenu_page(
    $parent_slug    = $args['menu-slug'],
    $page_title     = 'Posts Mapping',
    $menu_title     = 'Posts Mapping',
    $capability     = 'administrator',
    $menu_slug      = 'posts-mapping',
    $function       = '___was_admin_menu_posts_mapping'
);

require_once( WAS_PLUGIN_DIR . 'inc/classes/class-searchMapping.php');

function ___was_admin_menu_posts_mapping(){ ?>

<?php

require_once( WAS_PLUGIN_DIR . 'inc/settings/plugin-vars.php' );

global $wpdb , $enginesTableName;

// if( isset($_POST['submit']) ){

// $post_name  = $_POST['mapping-post-name'];
// $post_type  = $_POST['mapping-post-type'];
// $post_id    = $_POST['mapping-post-id'];

// $mappings = $wpdb->get_results("
//     SELECT *
//     FROM {$wpdb->prefix}advanced_search_mapping
//     WHERE import_post_name = '{$post_name}'
// ");

// if ( sizeof($mappings) ){
//     $wpdb->query("
//         UPDATE {$wpdb->prefix}advanced_search_mapping
//         SET engine_name     = 'support-search',
//         post_type   = '{$post_type}',
//         post_reference  = '{$post_id}'
//         WHERE import_post_name = '{$post_name}'
//     ");
// } else {
//     $wpdb->query("
//         INSERT INTO {$wpdb->prefix}advanced_search_mapping ( engine_name, post_type, import_post_name, post_reference)
//         VALUES ( 'support-search' , '{$post_type}', '{$post_name}' , '{$post_id}' )
//     ");
// }

// require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

// // dbDelta( $sqlRequest );

// } ?>

<?php 

    $importSQL = "SELECT * FROM {$enginesTableName} WHERE engine_name = 'support_  search'";

    $importData = $wpdb->get_results( $importSQL );

    $importURL = $importData[0]->import_url;

    $file = fopen($importURL, "r");  

    $posts = [];

    while (($data = fgetcsv($file)) !== false) {

        if( !array_search( $data[0] , $posts ) ){

            array_push( $posts , $data[0] );

        }
    
    }

    fclose( $file );

    unset( $posts[0] );

    // GET MAPPINGS

    $mappingsSQL = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}advanced_search_mapping" );

    $mappingsJSON = json_encode( $mappingsSQL );
    
    ?>



<div class="___was">

    <div class="container">
        <h1 class="___was__title">Search Mappings</h1>
    </div>

    <div class="container">

        <div class="___wasTable">

            <div class="___wasTable__entry ___wasTable__header colGr">
                <div class="colGr__col_3 ___wasTable__column ___wasTable__column_header">Post name</div>
                <div class="colGr__col_3 ___wasTable__column ___wasTable__column_header">Post Type</div>
                <div class="colGr__col_4 ___wasTable__column ___wasTable__column_header">Post Reference</div>
                <div class="colGr__col_2 ___wasTable__column ___wasTable__column_header"></div>
            </div>
            mapped posts
            <?php
            
            foreach ($mappingsSQL as $item) {
              

            ?>
                <form class="___wasTable__entry" action="#" method="POST">
                    <div class="___wasTable__entryHeader colGr">
                        <div class="colGr__col_3 ___wasTable__column post-name">
                            <h2 class="___wasTable__entryTitle"><?php echo $item->import_post_name; ?></h2>
                            <input type="text" name="mapping-post-name" value="<?php echo $item->import_post_name; ?>">
                        </div>
                        <div class="colGr__col_3 ___wasTable__column">
                            <?php

                            ?>
                            <select class="___wasInputUnit__input" name="mapping-post-type" data-dropdown-content="post-types">

                                <?php
                                
                                $postTypes = [ 'document' , 'post' , 'page' ];

                                foreach ($postTypes as $postType) {

                                    if( $postType == $item->post_type ) {

                                        echo '<option value="' . $postType . '" selected="selected">' . $postType . '</option>';

                                    } else {

                                        echo '<option value="' . $postType . '">' . $postType . '</option>';

                                    }

                                };

                                ?>
                            </select>
                        </div>
                        <div class="colGr__col_4 ___wasTable__column post-type-select-container" data-column-content="post-dropdowns">


                            <?php 

                                $postsQueryArgs = array(
                                    'post_type'         => array( 'document' ),
                                    'posts_per_page'    => -1
                                );

                                $postsQuery = new WP_Query( $postsQueryArgs );

                                if( $postsQuery->have_posts() ) {

                            ?>

                                <select class="___wasInputUnit__input" name="mapping-post-id" data-dropdown-content="document-posts">

                                    <option selected disabled>Choose Support Document</option>

                                    <?php while( $postsQuery->have_posts()) : $postsQuery->the_post(); ?>
                                        
                                        <option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option>

                                    <?php endwhile; ?>
                                        
                                </select>

                            <?php wp_reset_postdata(  ); } ?>


                        
                            <?php 

                                $postsQueryArgs = array(
                                    'post_type'         => array( 'post' ),
                                    'posts_per_page'    => -1
                                );

                                $postsQuery = new WP_Query( $postsQueryArgs );

                                if( $postsQuery->have_posts() ) {

                            ?>

                                <select class="___wasInputUnit__input" name="mapping-post-id" data-dropdown-content="post-posts">

                                    <option selected disabled>Choose post</option>

                                    <?php while( $postsQuery->have_posts()) : $postsQuery->the_post(); ?>
                                        
                                        <option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option>

                                    <?php endwhile; ?>
                                        
                                </select>

                            <?php wp_reset_postdata(  ); } ?>
                        
                            <?php 

                                $postsQueryArgs = array(
                                    'post_type'         => array( 'page' ),
                                    'posts_per_page'    => -1
                                );

                                $postsQuery = new WP_Query( $postsQueryArgs );

                                if( $postsQuery->have_posts() ) {

                            ?>

                                <select class="___wasInputUnit__input" name="mapping-post-id" data-dropdown-content="page-posts">

                                    <option selected disabled>Choose page</option>

                                    <?php while( $postsQuery->have_posts()) : $postsQuery->the_post(); ?>
                                        
                                        <option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option>

                                    <?php endwhile; ?>
                                        
                                </select>

                            <?php wp_reset_postdata(  ); } ?>

                        </div>
                        <div class="colGr__col_2 ___wasTable__column ___wasTable__column_header">
                            <input type="submit" name="submit" value="submit">
                        </div>
                    </div>
                </form>
                
            <?php } ?>

            unmapped posts

            <?php
            
            foreach ($posts as $item) {
                
            ?>

                <form class="___wasTable__entry" action="#" method="POST">
                    <div class="___wasTable__entryHeader colGr">
                        <div class="colGr__col_3 ___wasTable__column post-name">
                            <h2 class="___wasTable__entryTitle"><?php echo $item; ?></h2>
                            <input type="text" name="mapping-post-name" value="<?php echo $item; ?>">
                        </div>
                        <div class="colGr__col_3 ___wasTable__column">
                            <select class="___wasInputUnit__input" name="mapping-post-type" data-dropdown-content="post-types">

                                <?php
                                
                                $postTypes = [ 'document' , 'post' , 'page' ];

                                foreach ($postTypes as $postType) {
                                    
                                    echo '<option value="' . $postType . '">' . $postType . '</option>';

                                };

                                ?>
                            </select>
                        </div>
                        <div class="colGr__col_4 ___wasTable__column post-type-select-container" data-column-content="post-dropdowns">


                            <?php 

                                $postsQueryArgs = array(
                                    'post_type'         => array( 'document' ),
                                    'posts_per_page'    => -1
                                );

                                $postsQuery = new WP_Query( $postsQueryArgs );

                                if( $postsQuery->have_posts() ) {

                            ?>

                                <select class="___wasInputUnit__input" name="mapping-post-id" data-dropdown-content="document-posts">

                                    <option selected disabled>Choose Support Document</option>

                                    <?php while( $postsQuery->have_posts()) : $postsQuery->the_post(); ?>
                                        
                                        <option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option>

                                    <?php endwhile; ?>
                                        
                                </select>

                            <?php wp_reset_postdata(  ); } ?>


                        
                            <?php 

                                $postsQueryArgs = array(
                                    'post_type'         => array( 'post' ),
                                    'posts_per_page'    => -1
                                );

                                $postsQuery = new WP_Query( $postsQueryArgs );

                                if( $postsQuery->have_posts() ) {

                            ?>

                                <select class="___wasInputUnit__input" name="mapping-post-id" data-dropdown-content="post-posts">

                                    <option selected disabled>Choose post</option>

                                    <?php while( $postsQuery->have_posts()) : $postsQuery->the_post(); ?>
                                        
                                        <option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option>

                                    <?php endwhile; ?>
                                        
                                </select>

                            <?php wp_reset_postdata(  ); } ?>
                        
                            <?php 

                                $postsQueryArgs = array(
                                    'post_type'         => array( 'page' ),
                                    'posts_per_page'    => -1
                                );

                                $postsQuery = new WP_Query( $postsQueryArgs );

                                if( $postsQuery->have_posts() ) {

                            ?>

                                <select class="___wasInputUnit__input" name="mapping-post-id" data-dropdown-content="page-posts">

                                    <option selected disabled>Choose page</option>

                                    <?php while( $postsQuery->have_posts()) : $postsQuery->the_post(); ?>
                                        
                                        <option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option>

                                    <?php endwhile; ?>
                                        
                                </select>

                            <?php wp_reset_postdata(  ); } ?>

                        </div>
                        <div class="colGr__col_2 ___wasTable__column ___wasTable__column_header">
                            <input type="submit" name="submit" value="submit">
                        </div>
                    </div>
                </form>
                
            <?php } ?>

        </div>
        
    </div>

</div>




<?php }

?>
