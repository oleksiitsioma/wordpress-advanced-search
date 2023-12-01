<?php 

add_submenu_page(
    $parent_slug    = $args['menu-slug'],
    $page_title     = 'Posts Mapping',
    $menu_title     = 'Posts Mapping',
    $capability     = 'administrator',
    $menu_slug      = 'posts-mapping',
    $function       = '___pas_admin_menu_posts_mapping'
);

function ___pas_admin_menu_posts_mapping(){ ?>


<?php 
    global $wpdb;

    $searchEnginesTable = $wpdb->prefix . 'advanced_search_engines';

    $importSQL = "SELECT * FROM {$searchEnginesTable} WHERE engine_name = 'support-search'";

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

    <?php if( isset($_POST['submit']) ){

        $post_name  = $_POST['mapping-post-name'];
        $post_type  = $_POST['mapping-post-type'];
        $post_id    = $_POST['mapping-post-id'];

        $mappings = $wpdb->get_results("
            SELECT *
            FROM {$wpdb->prefix}advanced_search_mapping
            WHERE import_post_name = '{$post_name}'
        ");

        if ( sizeof($mappings) ){
            $wpdb->query("
                UPDATE {$wpdb->prefix}advanced_search_mapping
                SET engine_name     = 'support-search',
                post_type   = '{$post_type}',
                post_reference  = '{$post_id}'
                WHERE import_post_name = '{$post_name}'
            ");
        } else {
            $wpdb->query("
                INSERT INTO {$wpdb->prefix}advanced_search_mapping ( engine_name, post_type, import_post_name, post_reference)
                VALUES ( 'support-search' , '{$post_type}', '{$post_name}' , '{$post_id}' )
            ");
        }
    
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        // dbDelta( $sqlRequest );

    }

?>

<div class="___pas">

    <div class="container">
        <h1 class="___pas__title">Search Mappings</h1>
    </div>

    <div class="container">

        <div class="___pasTable">

            <div class="___pasTable__entry ___pasTable__header colGr">
                <div class="colGr__col_3 ___pasTable__column ___pasTable__column_header">Post name</div>
                <div class="colGr__col_3 ___pasTable__column ___pasTable__column_header">Post Type</div>
                <div class="colGr__col_4 ___pasTable__column ___pasTable__column_header">Post Reference</div>
                <div class="colGr__col_2 ___pasTable__column ___pasTable__column_header"></div>
            </div>

            <?php
            
            foreach ($posts as $item) {
                
            ?>

                <form class="___pasTable__entry" action="#" method="POST">
                    <div class="___pasTable__entryHeader colGr">
                        <div class="colGr__col_3 ___pasTable__column post-name">
                            <h2 class="___pasTable__entryTitle"><?php echo $item; ?></h2>
                            <input type="text" name="mapping-post-name" value="<?php echo $item; ?>">
                        </div>
                        <div class="colGr__col_3 ___pasTable__column">
                            <?php

                            ?>
                            <select class="___pasInputUnit__input" name="mapping-post-type" data-dropdown-content="post-types">

                                <?php
                                
                                $postTypes = [ 'document' , 'post' , 'page' ];

                                foreach ($postTypes as $postType) {
                                    
                                    echo '<option value="' . $postType . '">' . $postType . '</option>';

                                };

                                ?>
                            </select>
                        </div>
                        <div class="colGr__col_4 ___pasTable__column post-type-select-container" data-column-content="post-dropdowns">


                            <?php 

                                $postsQueryArgs = array(
                                    'post_type'         => array( 'document' ),
                                    'posts_per_page'    => -1
                                );

                                $postsQuery = new WP_Query( $postsQueryArgs );

                                if( $postsQuery->have_posts() ) {

                            ?>

                                <select class="___pasInputUnit__input" name="mapping-post-id" data-dropdown-content="document-posts">

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

                                <select class="___pasInputUnit__input" name="mapping-post-id" data-dropdown-content="post-posts">

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

                            <select class="___pasInputUnit__input" name="mapping-post-id" data-dropdown-content="page-posts">

                                <option selected disabled>Choose page</option>

                                <?php while( $postsQuery->have_posts()) : $postsQuery->the_post(); ?>
                                    
                                    <option value="<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></option>

                                <?php endwhile; ?>
                                    
                            </select>

                        <?php wp_reset_postdata(  ); } ?>
                        </div>
                        <div class="colGr__col_2 ___pasTable__column ___pasTable__column_header">
                            <input type="submit" name="submit" value="submit">
                        </div>
                    </div>
                </form>

                    <!-- <form class="colGr" action="#" method="POST" style="margin-bottom: 30px">
                        <div class="colGr__col_3 post-name">
                            <span><?php// echo $item; ?></span>
                            <br>
                            <input type="text" name="post-name" value="<?php //echo $item; ?>" style="display: none">
                        </div>
                        <div class="colGr__col_3 relation-type">
                            

                            <select name="mapping-post-type" id="">
                                <option value="document" selected>Support Document</option>
                                <option value="post">Post</option>
                                <option value="page">Page</option>
                            </select>
                        </div>
                        <div class="colGr__col_6 post-select-container">
                            <?php // if( $postsQuery->have_posts() ) { ?>

                                <select name="post-id" id="post-mapping" style="width: 100%">

                                    <option disabled selected>Choose document</option>

                                    <?php
                                    
                                    // while( $postsQuery->have_posts() ) : $postsQuery->the_post();
                                    
                                    // echo '<option value=' . get_the_ID() . '>' . get_the_title() . '</option>';
                                    
                                    // endwhile; wp_reset_postdata();

                                    ?>


                                </select>

                                <input type="submit" value="submit" name="submit">
                            <?php // } ?>
                        </div>
                    </form> -->
                <?php } ?>


                
            </form>

        </div>
        
    </div>

</div>




<?php }

?>
