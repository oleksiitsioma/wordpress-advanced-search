<?php 

$mappingsFromImport = $args['mappings-from-import'];
$mappingsSQL        = $args['mappings-sql'];
$postsArray         = $args['posts-array'];
$pagesArray         = $args['pages-array'];
$documentsArray     = $args['documents-array'];

?>

<form action="#" method="POST">

    <input type="submit" value="Submit" name="submit-pairing">

<?php 

global $wpdb , $pairingsTableName;

?>

<?php 

echo 'unsaved pairings';
echo '<br>';

foreach ( $mappingsFromImport as $key => $item ) {

    foreach ( $mappingsSQL as $mappingItem ) {

        if ( $item[0] === $mappingItem->import_post_name && !pairing_is_saved( $item[0] ) ){
            
            $postID = $mappingItem->post_reference;

            $blockFound = false;
                                
            $postContent            = get_the_content( $post = $postID );
            $postContentBlocks      = parse_blocks( $postContent );

            $postFilteredBlocks     = [];

            foreach ($postContentBlocks as $block) {

                if( $block['blockName'] == 'kadence/accordion' ) {
                    foreach ( $block['innerBlocks'] as $innerBlock ) {

                        if( $innerBlock['blockName'] == 'kadence/pane' ){

                            array_push( $postFilteredBlocks , $innerBlock );

                        }

                    }

                }

                if( $block['blockName'] == 'kadence/tabs' ) {

                    foreach ( $block['innerBlocks'] as $innerBlock ) {

                        if( $innerBlock['blockName'] == 'kadence/tab' ){

                            array_push( $postFilteredBlocks , $innerBlock );

                        }

                    }

                }

            }

            foreach ( $postFilteredBlocks as $block ) {

                $label = $block['attrs']['ariaLabel'];

                if( strlen( $block['attrs']['ariaLabel'] ) > 0 ){

                    $label = $block['attrs']['ariaLabel'];

                } else {

                    $label = $block['innerContent'];
                    
                }

                $labelEscaped = strtolower( preg_replace("/[^A-Za-z0-9 ]/", '', $label) );    
                $blockImportedValue = strtolower( preg_replace("/[^A-Za-z0-9 ]/", '', $item[2]) );

                if(
                    strpos( $labelEscaped , $blockImportedValue ) > -1 ||
                    strpos( $blockImportedValue , $labelEscaped ) > -1
                ) {

                    $blockFound = true;

                }

            }

            $queries = preg_replace('/[\x00-\x1F\x7F]/', ';', $item[3] );
            $queriesSeparated = preg_split( '/([\.\?\,\;])/' , $queries );

            $queriesFormatted = [];

            foreach ($queriesSeparated as $query) {

                if( !strlen ( trim( $query ) ) == 0 ){

                    array_push( $queriesFormatted , trim( $query ) );

                }

            }

            ?>


            <div class="___pasTable__entry ___pasTable__entry_openable">


                <div class="___pasTable__entryHeader colGr">

                    <div class="colGr__col_4 ___pasTable__column">
                        <h2 class="___pasTable__entryTitle"><?php echo $item[2]; ?></h2>
                        <p class="___pasTable__entrySubtitle">Accordion pane</p>
                    </div>

                    <div class="colGr__col_3 ___pasTable__column ___pasTable__column_spacedVertically">
                        <p class="___pasTable__entryTitle"><?php echo $item[0]; ?></p>
                        <p class="___pasTable__entrySubtitle">
                            <?php echo get_post( $postID )->post_title . ' - ' . $postID ; ?>
                        </p>
                    </div>

                    <div class="colGr__col_3 ___pasTable__column">
                        <p class="___pasTable__entrySubtitle">Total queries</p>
                        <p class="___pasTable__entrySubtitle"><?php if ( $blockFound ) { echo 'block found'; } ?></p>
                    </div>

                    <div class="colGr__col_2 ___pasTable__column">
                    </div>

                </div>

                <div class="___pasTable__entryBody colGr">

                    <div class="colGr__col_12" style="display: none" >
                        <!-- Hidden Import Post Name Field -->
                        <input type="text" name="import-post-name-<?php echo $key; ?>" value="<?php echo $item[0]; ?>">
                        <!-- Hidden Indexing For Field -->
                        <input type="text" name="import-block-name-<?php echo $key; ?>" value="<?php echo $item[2]; ?>">
                        <!-- Hidden Global Field -->
                        <?php if( $mappingItem->post_type === 'global'){ ?>
                        <input type="text" name="is-global-<?php echo $key; ?>" value="1">
                        <?php } ?>
                        <!-- Block found -->
                        <input type="text" value="<?php echo $blockFound; ?>" name="block-found-<?php echo $key; ?>">
                    </div>

                    <div class="colGr__col_12 ___pasSearchUnit">
                        <div class="___pasInputUnit">
                            <label for="example-text" class="___pasInputUnit__label">Target post</label>
                            <select class="___pasInputUnit__input" name="post-name-<?php echo $key; ?>" id="">
                                <option disabled selected>Select Document</option>
                                <?php

                                    foreach( $documentsArray as $document ) {

                                        if( $document->ID == $postID){

                                            echo '<option value="' . $document->ID . '" selected="selected">' . $document->post_title . '</option>';

                                        } else {

                                            echo '<option value="' . $document->ID . '">' . $document->post_title . '</option>';

                                        }

                                    }
                                    
                                ?>
                                <option disabled>Select Page</option>
                                <?php

                                    foreach( $pagesArray as $page ) {

                                        if( $page->ID == $postID){

                                            echo '<option value="' . $page->ID . '" selected="selected">' . $page->post_title . '</option>';

                                        } else {

                                            echo '<option value="' . $page->ID . '">' . $page->post_title . '</option>';

                                        }

                                    }
                                    
                                ?>
                                <option disabled>Select Post</option>
                                <?php

                                    foreach( $postsArray as $post ) {

                                        if( $post->ID == $postID){

                                            echo '<option value="' . $post->ID . '" selected="selected">' . $post->post_title . '</option>';

                                        } else {

                                            echo '<option value="' . $post->ID . '">' . $post->post_title . '</option>';

                                        }

                                    }
                                    
                                ?>
                            </select>
                            <p class="___pasInputUnit__hint">Choose the target WordPress post</p>
                        </div>
                    </div>

                    <div class="colGr__col_3 ___pasSearchUnit">
                        <div class="___pasInputUnit">
                            <label for="example-text" class="___pasInputUnit__label">Do you want to target a specific block?</label>
                            <select class="___pasInputUnit__input" name="is-block-target-<?php echo $key; ?>" id="">
                                <option value="1" selected>yes</option>
                                <option value="0">no</option>
                            </select>
                        </div>
                    </div>

                    <div class="colGr__col_9 ___pasSearchUnit">
                        <div class="___pasInputUnit">
                            <label for="example-text" class="___pasInputUnit__label">Inner blocks</label>
                            <?php 
                            

                            ?>

                            <select class="___pasInputUnit__input" name="block-reference-<?php echo $key; ?>" id="">

                                <?php
                                
                                foreach ( $postFilteredBlocks as $block ) {

                                    $label = $block['attrs']['ariaLabel'];

                                    if( strlen( $block['attrs']['ariaLabel'] ) > 0 ){

                                        $label = $block['attrs']['ariaLabel'];

                                    } else {

                                        $label = $block['innerContent'];
                                        
                                    }

                                    $labelEscaped = strtolower( preg_replace("/[^A-Za-z0-9 ]/", '', $label) );    
                                    $blockImportedValue = strtolower( preg_replace("/[^A-Za-z0-9 ]/", '', $item[2]) );

                                    if(
                                        strpos( $labelEscaped , $blockImportedValue ) > -1 ||
                                        strpos( $blockImportedValue , $labelEscaped ) > -1
                                    ) {
                                        $blockFound = true;

                                        echo '<option value="' . $block['attrs']['uniqueID'] .'" selected="selected">' . $labelEscaped . '</option>';

                                    } else {

                                        echo '<option value="' . $block['attrs']['uniqueID'] .'">' . $labelEscaped . '</option>';

                                    }

                                }

                                ?>

                            </select>
                        </div>
                    </div>

                    <!-- <div class="colGr__col_12 ___pasSearchUnit">
                        <div class="___pasInputUnit">
                            <label for="example-text" class="___pasInputUnit__label">Target block</label>
                            <?php 
                            
                            // $targetBlockName                = $item[2];
                            // $targetBlockNameEscaped         = preg_replace("/[^A-Za-z0-9 ]/", '', $targetBlockName);
                            // $targetBlockNameEscapedLower    = strtolower( $targetBlockNameEscaped );

                            ?>
                            <input
                                type="text"
                                class="___pasInputUnit__input"
                                id="example-text"
                                value="<?php // echo $targetBlockNameEscapedLower; ?>"
                                name=""
                                readonly="readonly"
                            >
                            <p class="___pasInputUnit__hint">Choose the target block within a post</p>
                        </div>
                    </div> -->

                    <?php if ( $queriesFormatted ) { ?>

                        <div class="colGr__col_12 ___pasSearchUnit">
                            <div class="___pasInputUnit">
                                <label for="tags-input" class="___pasInputUnit__label">Search Queries</label>

                                <input type="text" name="search-queries-<?php echo $key; ?>" value="<?php echo implode( '; ' , $queriesFormatted ); ?>" style="display: none">

                                <div class="___pasInputUnit__input ___pasInputUnit__input_textarea ___pasInputUnit__tags" id="tags">

                                    <?php 
                                    
                                    foreach ( $queriesFormatted as $query ) { 
                                        
                                        echo '<span class="___pasInputUint__tag">' . $query . '</span>';

                                    }

                                    ?>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                    <div class="colGr__col_12">
                        <input type="text" name="post-reference-<?php echo $key; ?>" value="<?php echo $postID; ?>" style="display: none">
                    </div>





                </div>
            </div>

        <?php }

    }

}


?>

</form>