<?php 

add_submenu_page(
    $parent_slug    = $args['menu-slug'],
    $page_title     = 'Support Search',
    $menu_title     = 'Support Search',
    $capability     = 'administrator',
    $menu_slug      = 'support-search',
    $function       = '___pas_admin_menu_support_search'
);

function ___pas_admin_menu_support_search(){
    
    global $wpdb;



    if( isset( $_POST) ){

        foreach ($_POST as $key => $value) {

            if( strpos( $key , 'queries') ){

                $id = substr( $key, 0, strpos( $key , 'queries' ) - 1 );
                $queries = explode( '; ' , $_POST[$id . '-queries'] );
                $post_reference = $_POST[$id . '-postID'];
                $block_reference = $_POST[$id . '-block'];


                foreach ($queries as $query) {

                    $ifQueryExists = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}advanced_search_pairings WHERE search_query = '{$query}'" );

                    if ( $ifQueryExists ){
                        $wpdb->query("
                            UPDATE {$wpdb->prefix}advanced_search_pairings 
                            SET post_reference  = '{$post_reference}'
                            WHERE search_query  = '{$query}'
                        ");
                    } else {
                        $wpdb->query("
                            INSERT INTO {$wpdb->prefix}advanced_search_pairings ( engine_name , search_query , post_reference , is_block_level , block_id )
                            VALUES ( 'support-search' , '{$query}' , '{$post_reference}' , true , '{$block_reference}' )
                        ");
                    }


                }



            }

        }
    }

?>


<div class="___pas">

    <div class="container container_fullWidth">
        <h1 class="___pas__title">Support Search</h1>
    </div>

    <div class="container">

        <div class="___pasTable">

            <div class="___pasTable__entry ___pasTable__header colGr">
                <div class="colGr__col_4 ___pasTable__column ___pasTable__column_header">Target Block</div>
                <div class="colGr__col_3 ___pasTable__column ___pasTable__column_header">Target Post</div>
                <div class="colGr__col_3 ___pasTable__column ___pasTable__column_header">Status</div>
                <div class="colGr__col_2 ___pasTable__column ___pasTable__column_header"></div>
            </div>

            <form action="#" method="POST">

            <input type="submit" value="submit" name="submit">

            <?php
            
            global $wpdb;

            $savedPostMappings = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}advanced_search_mapping ");
        
            $savedPostMappingsLabels = [];
        
            foreach ($savedPostMappings as $el) {
                array_push( $savedPostMappingsLabels , $el->import_post_name);
            }

            $searchEnginesTable = $wpdb->prefix . 'advanced_search_engines';

            $importSQL = "SELECT * FROM {$searchEnginesTable} WHERE engine_name = 'support-search'";

            $importData = $wpdb->get_results( $importSQL );

            if ( $importData ){
        
                $importURL = $importData[0]->import_url;
            
                $file = fopen($importURL, "r"); 
        
                $pairingCounter = 1;
            
                fgets($file); 
                while (($data = fgetcsv($file)) !== false) {
        
                    if( array_search( $data[0] , $savedPostMappingsLabels ) > -1 ) {
        
                        global $wpdb;
            
                        $postIDSQL = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}advanced_search_mapping WHERE import_post_name = '{$data[0]}'");
            
                        $queries = preg_replace('/[\x00-\x1F\x7F]/', ';', $data[3] );
                
                        if(
                            strpos( $queries , '.' ) ||
                            strpos( $queries , ',' ) ||
                            strpos( $queries , ';' ) ||
                            strpos( $queries , '?' ) 
                                
                        ){
                                
                            $queriesFormatted = preg_split( '/([\.\?\,\;])/' , $queries );
                            
                            for ($i=0; $i < sizeof($queriesFormatted) ; $i++) {
                
                                $queriesFormatted[$i] = trim( $queriesFormatted[$i] );
                
                                if( strlen( $queriesFormatted[$i] ) == 0 ){
                                    array_splice( $queriesFormatted , $i , 1 );
                                }
                            }
                
                        } else {
                            echo $queries;
                        }
                        
                        ?>
                
                        <div class="___pasTable__entry ___pasTable__entry_openable">
        
                            <?php
                            
                            $postID     = $postIDSQL[0]->post_reference;
                            
                            ?>


                            <div class="___pasTable__entryHeader colGr">

                                <div class="colGr__col_4 ___pasTable__column">
                                    <h2 class="___pasTable__entryTitle"><?php echo $data[2]; ?></h2>
                                    <p class="___pasTable__entrySubtitle">Accordion pane</p>
                                </div>

                                <div class="colGr__col_3 ___pasTable__column ___pasTable__column_spacedVertically">
                                    <p class="___pasTable__entryTitle"><?php echo $data[0]; ?></p>
                                    <p class="___pasTable__entrySubtitle">
                                        <?php echo get_the_title( $postID ) . ' - ' . $postID ; ?>
                                    </p>
                                </div>

                                <div class="colGr__col_3 ___pasTable__column">

                                    <p class="___pasTable__entrySubtitle">
                                        Total queries - <?php echo sizeof( $queriesFormatted ); ?>
                                    </p>
                                    
                                </div>

                                <div class="colGr__col_2 ___pasTable__column">
                                </div>

                            </div>
                            <div class="___pasTable__entryBody colGr">

                                <div class="colGr__col_6 ___pasSearchUnit">
                                    <div class="___pasInputUnit">
                                        <label for="example-text" class="___pasInputUnit__label">Target post</label>
                                        <p class="___pasInputUnit__hint">Choose the target WordPress post</p>
                                        <input type="text" class="___pasInputUnit__input" id="example-text" value="<?php echo get_the_title( $postID ); ?>" readonly="readonly">
                                    </div>
                                </div>

                                <?php if( $data[1] == 'Dropdown TXT' ) { ?>

                                <div class="colGr__col_6 ___pasSearchUnit">
                                    <div class="___pasInputUnit">
                                        <label for="example-text" class="___pasInputUnit__label">Target block</label>
                                        <p class="___pasInputUnit__hint">Choose the target block within a post</p>
                                        <input
                                            type="text"
                                            class="___pasInputUnit__input"
                                            id="example-text"
                                            name="<?php echo $pairingCounter; ?>-block"
                                            value="<?php echo $data[2]; ?>"
                                            readonly="readonly"
                                        >
                                    </div>
                                </div>

                                <?php } 
                                
                                if ( $queriesFormatted ) { ?>

                                    <div class="colGr__col_12 ___pasSearchUnit">
                                        <div class="___pasInputUnit">
                                            <label for="tags-input" class="___pasInputUnit__label">Search Queries</label>

                                            <input type="text" name="<?php echo $pairingCounter; ?>-queries" value="<?php echo implode( '; ' , $queriesFormatted ); ?>" style="display: none">

                                            <div class="___pasInputUnit__input ___pasInputUnit__input_textarea ___pasInputUnit__tags" id="tags">

                                                <?php 
                                                
                                                foreach ($queriesFormatted as $query) { 
                                                    
                                                    echo '<span class="___pasInputUint__tag">' . $query . '</span>';

                                                }

                                                ?>
                                                <!-- <input type="text" class="___pasInputUnit__input" id="tags-input"> -->
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>

                                <!-- <div class="colGr__col_6 ___pasSearchUnit">
                                    <div class="___pasInputUnit">
                                        <label for="example-text" class="___pasInputUnit__label">Target Block</label>
                                        <p class="___pasInputUnit__hint">Choose the target block within a post</p>
                                        <div class="___pasDropdownOptions">
                                            <select name="" class="___pasInputUnit__input ___pasDropdownOptions__input" id="">
                                                <option class="___pasDropdownOptions__option">What are “wet checks” or irrigation checks?</option>

                                                <?php 
                                                
                                                // $content        = get_post( $postID)->post_content;
                                                // $contentBlocks  = parse_blocks( $content );

                                                // foreach ($contentBlocks as $block) {
                                                //     if( $block['blockName'] == 'kadence/accordion'){

                                                //         $panes = $block['innerBlocks'];

                                                //         foreach ($panes as $pane) {

                                                //             $attrs	= $pane['attrs'];
                                                //             $label = $attrs['ariaLabel'];

                                                //             if( strpos( strtolower($label) , strtolower( $data[2]) ) > -1){

                                                //                 echo '<option class="___pasDropdownOptions__option" selected="selected">' . $label . '</option>';

                                                //             } else {

                                                //                 echo '<option class="___pasDropdownOptions__option">' . $label . '</option>';

                                                //             }

                                                //         }
                                                //     }
                                                // }
                                                
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="colGr__col_12">
        
        <input type="text" name="<?php echo $pairingCounter; ?>-postID" value="<?php echo $postID; ?>" style="display: none">
                                </div>





                            </div>
        
                        </div>
        
        
                    <?php   }
        
                    $pairingCounter++ ;
            
                }
            
                fclose( $file );
        
            } ?>

            </form>
        
        </div>

    </div>

</div>


<?php } ?>