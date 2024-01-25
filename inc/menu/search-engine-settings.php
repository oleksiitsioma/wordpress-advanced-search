<?php 

global $pluginDIR;

require_once( ABSPATH . 'wp-content/plugins/advanced-search/inc/classes/class-SearchEngine.php' );
require_once( ABSPATH . 'wp-content/plugins/advanced-search/inc/classes/class-searchMapping.php' );
require_once( ABSPATH . 'wp-content/plugins/advanced-search/inc/classes/class-searchPairing.php' );
require_once( ABSPATH . 'wp-content/plugins/advanced-search/inc/settings/plugin-vars.php' );
require_once( ABSPATH . 'wp-content/plugins/advanced-search/inc/functions/functions.php' );

$engine = $args['engine'];

add_submenu_page(

    $parent_slug    = $args['menu-slug'],
    $page_title     = $engine->engine_label,
    $menu_title     = $engine->engine_label,
    $capability     = 'administrator',
    $menu_slug      = $engine->engine_name,
    $function       = '___was_admin_menu_search_settings'

);

function check_queries_for_duplicates(){

    global $wpdb, $pairingsTableName;
    
    // Get pairings


    $pairings = $wpdb->get_results("
        SELECT *
        FROM {$pairingsTableName};
    ");

    $queriesArrayOfArrays   = [];
    $queriesArrayOfElements = [];

    foreach ($pairings as $pairing) {

        $queries = unserialize( $pairing->search_queries );

        
        if( $queries ){

            foreach ($queries as $query) {
    
                if( array_search( $query , $queriesArrayOfElements ) > -1 ){
    
                    $wpdb->delete(
                        $pairingsTableName,
                        [ "search_queries" => serialize( $queries ) ],
                        [ "%s"]
                    );
    
                } else {
    
                    array_push( $queriesArrayOfElements , $query );
    
                }
    
            }

        }

    }


}


function ___was_admin_menu_search_settings(){

    global $wpdb, $enginesTableName, $mappingTableName , $pairingsTableName;
    $engine_name = $_GET['page'];
    
    // GET POST NAMES FROM IMPORTED FILE

    $importSQL = "SELECT * FROM {$enginesTableName} WHERE engine_name = '{$engine_name}'"; 
    $importData = $wpdb->get_results( $importSQL );
    $importURL = $importData[0]->import_url;
    
    $file = fopen($importURL, "r");  
    $mappingsFromImport         = [];
    $mappingsFromImportTitles   = [];
    
    while (($data = fgetcsv($file)) !== false) {
        array_push( $mappingsFromImport , $data );
    }
    
    fclose( $file );
    unset( $mappingsFromImport[0] );

    foreach ($mappingsFromImport as $mapping) {

        if( !in_array( $mapping[0] , $mappingsFromImportTitles ) ){
            array_push( $mappingsFromImportTitles , $mapping[0] );
        }
        
    }

    if( isset( $_POST ) ){

        if( isset( $_POST['submit-mapping']) ) {

            foreach( $mappingsFromImport as $key => $value ){

                if( isset( $_POST['mapping-post-type-' . $key ] ) && $_POST['mapping-post-type-' . $key ] == 'global' ){

                    $mapping = new SearchMapping(
                        $engine_name        = $engine_name,
                        $import_post_name   = $_POST['mapping-post-name-' . $key ],
                        $post_type          = $_POST['mapping-post-type-' . $key ],
                        $post_reference     = 0
                    );

                    $mapping->update_db();

                } else if( isset( $_POST['mapping-post-id-' . $key ] ) ){

                    $mapping = new SearchMapping(
                        $engine_name        = $engine_name,
                        $import_post_name   = $_POST['mapping-post-name-' . $key ],
                        $post_type          = $_POST['mapping-post-type-' . $key ],
                        $post_reference     = $_POST['mapping-post-id-'   . $key ]
                    );

                    $mapping->update_db();
    
                }

    
            }

        }

        if( isset( $_POST['submit-pairing'] ) || isset( $_POST['update-pairing'] ) ){
            
            foreach ($_POST as $key => $value) {

                $prefix = 'post-name-';

                if( str_contains( $key , $prefix ) ){

                    $id = substr( $key , strlen( $prefix ) );

                    if(

                        isset( $_POST['post-name-' . $id] ) && !empty( $_POST['post-name-' . $id] ) 
                        
                    ){

                        if( isset( $_POST['block-found-' . $id ] ) ){
    
                            $queries = $_POST[ 'search-queries-' . $id ];
        
                            $queriesArray = explode( '; ' , $queries );
        
                            $pairing = new SearchPairing(
                                $engine_name        = $engine_name,
                                $import_post_name   = $_POST[ 'import-post-name-'   . $id ],
                                $post_reference     = $_POST[ 'post-reference-'     . $id ],
                                $is_block_level     = $_POST[ 'is-block-target-'    . $id ],
                                $searchQueries      = serialize( $queriesArray ),
                            );
    
                            if( $pairing->is_block_level){
    
                                $pairing->import_block_name  = $_POST[ 'import-block-name-'  . $id ];
                                $pairing->block_label        = $_POST[ 'block-reference-'    . $id ];
    
                            }
        
                            $pairing->update_db();
    
                        } else {
    
                            $queries = $_POST[ 'search-queries-' . $id ];
        
                            $queriesArray = explode( '; ' , $queries );
        
                            $pairing = new SearchPairing(
                                $engine_name        = $engine_name,
                                $import_post_name   = $_POST[ 'import-post-name-'   . $id ],
                                $post_reference     = $_POST[ 'post-name-'     . $id ],
                                $is_block_level     = 0,
                                $searchQueries      = serialize( $queriesArray )
                            );
        
                            $pairing->update_db();
    
                        }

                    }

                }

            }

        }

    }

    
    // GET MAPPINGS FROM DB
    
    $mappingsSQL = $wpdb->get_results( "SELECT * FROM {$mappingTableName}" );

    $mappedIDS = [];

    foreach ($mappingsSQL as $mapping) {
        array_push( $mappedIDS , $mapping->post_reference );
    }
    
    $mappingsJSON = json_encode( $mappingsSQL );

    $postsArray = get_posts(
        array(
            'post_type'         => 'post',
            'posts_per_page'    => -1,
            'orderby'           => 'title',
            'order'             => 'ASC'
        )
    );
    
    $pagesArray = get_posts(
        array(
            'post_type'         => 'page',
            'posts_per_page'    => -1,
            'orderby'           => 'title',
            'order'             => 'ASC'
        )
    );
    
    $documentsArray = get_posts(
        array(
            'post_type'         => 'document',
            'posts_per_page'    => -1,
            'orderby'           => 'title',
            'order'             => 'ASC'
        )
    );

    ?>

    <div class="___was">

        <?php check_queries_for_duplicates(); ?>

        <div class="container">
            <h1 class="___was__title"><?php echo $engine_name; ?></h1>
        </div>

        <div class="container container_fullWidth colGr searchSettings__container">

            <div class="colGr__col_3 searchSettings__nav"></div>
            <div class="colGr__col_9 searchSettings__content">

                <div class="___wasTable">

                    <?php 

                    load_template(
                        $_template_file = WAS_PLUGIN_DIR . 'inc/menu/search-settings/mapping.php', $require_once   = true,
                        $args           = array(
                            'mappingsSQL'               => $mappingsSQL,
                            'mappingsFromImportTitles'  => $mappingsFromImportTitles,
                            'postsArray'                => $postsArray,
                            'pagesArray'                => $pagesArray,
                            'documentsArray'            => $documentsArray,
                        )
                    );

                    load_template(
                        $_template_file = WAS_PLUGIN_DIR . 'inc/menu/search-settings/pairings.php',
                        $require_once   = true,
                        $args           = array(
                            'mappingsFromImport'    => $mappingsFromImport,
                            'mappingsSQL'           => $mappingsSQL,
                            'postsArray'            => $postsArray,
                            'pagesArray'            => $pagesArray,
                            'documentsArray'        => $documentsArray,
                        )
                    );

                    ?>

                </div>
                
            </div>

        </div>


    </div>


<?php }