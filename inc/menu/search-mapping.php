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

        $post_name      = $_POST['post-name'];
        $relation_type  = $_POST['mapping-relation-type'];
        $post_id        = $_POST['post-id'];

        $mappings = $wpdb->get_results("
            SELECT *
            FROM {$wpdb->prefix}advanced_search_mapping
            WHERE import_post_name = '{$post_name}'
        ");

        if ( sizeof($mappings) ){
            $wpdb->query("
                UPDATE {$wpdb->prefix}advanced_search_mapping
                SET engine_name     = 'support-search',
                relation_type   = '{$relation_type}',
                post_reference  = '{$post_id}'
                WHERE import_post_name = '{$post_name}'
            ");
        } else {
            $wpdb->query("
                INSERT INTO {$wpdb->prefix}advanced_search_mapping ( engine_name, relation_type, import_post_name, post_reference)
                VALUES ( 'support-search' , '{$relation_type}', '{$post_name}' , '{$post_id}' )
            ");
        }
    
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        // dbDelta( $sqlRequest );

    }


    $postsQueryArgs = array(
        'post_type'         => array( 'document' ),
        'posts_per_page'    => -1
    );

    $postsQuery = new WP_Query( $postsQueryArgs );

?>

<div class="___pas">

    <div class="container">
        <h1 class="___pas__title">Search Mappings</h1>
    </div>

    <div class="container">

        <div class="___pasTable">

            <div class="___pasTable__entry ___pasTable__entry_header colGr">
                <div class="colGr__col_3 ___pasTable__column ___pasTable__column_header">Post name</div>
                <div class="colGr__col_3 ___pasTable__column ___pasTable__column_header">Relation Type</div>
                <div class="colGr__col_6 ___pasTable__column ___pasTable__column_header">Post Reference</div>
            </div>

                <?php foreach ($posts as $item) { ?>
                    <form class="colGr" action="#" method="POST" style="margin-bottom: 30px">
                        <div class="colGr__col_3 post-name">
                            <span><?php echo $item; ?></span>
                            <br>
                            <input type="text" name="post-name" value="<?php echo $item; ?>" style="display: none">
                        </div>
                        <div class="colGr__col_3 relation-type">
                            <select name="mapping-relation-type" id="">
                                <option value="post">Post</option>
                                <option value="post">Global</option>
                            </select>
                        </div>
                        <div class="colGr__col_6 post-select-container">
                            <?php if( $postsQuery->have_posts() ) { ?>

                                <select name="post-id" id="post-mapping" style="width: 100%">

                                    <option disabled selected>Choose document</option>

                                    <?php
                                    
                                    while( $postsQuery->have_posts() ) : $postsQuery->the_post();
                                    
                                    echo '<option value=' . get_the_ID() . '>' . get_the_title() . '</option>';
                                    
                                    endwhile; wp_reset_postdata();

                                    ?>


                                </select>

                                <input type="submit" value="submit" name="submit">
                            <?php } ?>
                        </div>
                    </form>
                <?php } ?>


                
            </form>

        </div>
        
    </div>

</div>



<script>
    
    
    jQuery(document).ready(function($) {
        
        const mappings = <?php echo $mappingsJSON ; ?> ;

        mappings.forEach(el => {
            
            const postName      = el.import_post_name;
            const relationType  = el.relation_type
            const tablePostItem = $('.post-name');
            
            for (let i = 0; i < tablePostItem.length; i++) {

                const tablePostItemSpan = $(tablePostItem[i]).find('span');
                
                if( $(tablePostItemSpan).text() === postName ){

                    const tablePostSelectContainer = $(tablePostItem[i]).siblings('.post-select-container');
                    const tablePostSelectOptions = $(tablePostSelectContainer).find('option');
                    $(tablePostSelectOptions).removeAttr('selected');
                    const tablePostSelectCorrectOption = $(tablePostSelectContainer).find(`option[value="${el.post_reference}"]`);

                    $(tablePostSelectCorrectOption).attr('selected' , 'selected');

                    const tableRelationSelectContainer = $(tablePostItem[i]).siblings('.relation-type');
                    const tableRelationSelectContainerOption = $(tableRelationSelectContainer).find(`option[value="${relationType}"]`);

                    $(tableRelationSelectContainerOption).attr('selected' , 'selected');

                }
            
               }

            });

        });

    </script>

<?php }

?>
