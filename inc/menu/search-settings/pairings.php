<?php 

$mappingsFromImport = $args['mappingsFromImport'];
$mappingsSQL        = $args['mappingsSQL'];
$postsArray         = $args['postsArray'];
$pagesArray         = $args['pagesArray'];
$documentsArray     = $args['documentsArray'];

$savedPairings = get_pairings();

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
            
            ?>


            <div class="___pasTable__entry ___pasTable__entry_openable">

                <?php $postID = $mappingItem->post_reference; ?>

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
                    </div>

                    <div class="colGr__col_2 ___pasTable__column">
                    </div>

                </div>

                <div class="___pasTable__entryBody colGr">

                    <div class="colGr__col_6">
                        <input type="text" name="import-post-name-<?php echo $key; ?>" value="<?php echo $item[0]; ?>">
                    </div>
                    <div class="colGr__col_6">
                        <input type="text" name="import-block-name-<?php echo $key; ?>" value="<?php echo $item[2]; ?>">
                    </div>

                    <div class="colGr__col_12">
                        <?php if( $mappingItem->post_type === 'global'){ ?>

                        <input type="text" name="is-global-<?php echo $key; ?>" value="1">

                        <?php } ?>
                    </div>

                    <?php if( $mappingItem->post_type === 'global' ){ ?>

                        <div class="colGr__col_6 ___pasSearchUnit">
                            <div class="___pasInputUnit">
                                <label for="example-text" class="___pasInputUnit__label">Target post</label>
                                <select class="___pasInputUnit__input" name="post-name-<?php echo $key; ?>" id="">
                                    <option disabled selected>Select Document</option>
                                    <?php

                                        foreach( $documentsArray as $document ) {

                                            echo '<option value="' . $document->ID . '">' . $document->post_title . '</option>';

                                        }
                                        
                                    ?>
                                    <option disabled>Select Page</option>
                                    <?php

                                        foreach( $pagesArray as $page ) {

                                            echo '<option value="' . $page->ID . '">' . $page->post_title . '</option>';

                                        }
                                        
                                    ?>
                                    <option disabled>Select Post</option>
                                    <?php

                                        foreach( $postsArray as $post ) {

                                            echo '<option value="' . $post->ID . '">' . $post->post_title . '</option>';

                                        }
                                        
                                    ?>
                                </select>
                                <p class="___pasInputUnit__hint">Choose the target WordPress post</p>
                            </div>
                        </div>

                    <?php } else { ?>

                        <div class="colGr__col_6 ___pasSearchUnit">
                            <div class="___pasInputUnit">
                                <label for="example-text" class="___pasInputUnit__label">Target post</label>
                                <input
                                    type="text"
                                    class="___pasInputUnit__input"
                                    value="<?php echo get_post( $postID )->post_title ; ?>"
                                    name="post-name-<?php echo $key; ?>"
                                    readonly="readonly"
                                >
                                <p class="___pasInputUnit__hint">Choose the target WordPress post</p>
                            </div>
                        </div>

                    <?php } ?>

                    <?php if( $item[1] == 'Dropdown TXT' || $item[1] == 'On-Site TXT') { ?>

                        <div class="colGr__col_12">
                            <input type="text" name="is-block-target-<?php echo $key; ?>" value="1">
                        </div>

                        <div class="colGr__col_6 ___pasSearchUnit">
                            <div class="___pasInputUnit">
                                <label for="example-text" class="___pasInputUnit__label">Target block</label>
                                <?php 
                                
                                $targetBlockName                = $item[2];
                                $targetBlockNameEscaped         = preg_replace("/[^A-Za-z0-9 ]/", '', $targetBlockName);
                                $targetBlockNameEscapedLower    = strtolower( $targetBlockNameEscaped );

                                ?>
                                <input
                                    type="text"
                                    class="___pasInputUnit__input"
                                    id="example-text"
                                    value="<?php echo $targetBlockNameEscapedLower; ?>"
                                    name=""
                                    readonly="readonly"
                                >
                                <p class="___pasInputUnit__hint">Choose the target block within a post</p>
                            </div>
                        </div>

                        <div class="colGr__col_6 ___pasSearchUnit">
                            <div class="___pasInputUnit">
                                <label for="example-text" class="___pasInputUnit__label">Inner blocks</label>
                                <?php 

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
                                

                                ?>

                                <select name="block-reference-<?php echo $key; ?>" id="">

                                    <?php
                                    
                                    foreach ( $postFilteredBlocks as $block ) {

                                        $label = $block['attrs']['ariaLabel'];

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

                        <div class="colGr__col_6 ___pasSearchUnit">
                            <div class="___pasInputUnit">
                                <label for="example-text" class="___pasInputUnit__label">Target block</label>
                                <input type="text" value="<?php echo $blockFound; ?>" name="block-found-<?php echo $key; ?>">
                                <p><?php if( $blockFound ) { echo 'block found' ; } ?></p>
                            </div>
                        </div>

                        <div class="colGr__col_6"></div>

                    <?php } ?>
                    
                    <div class="colGr__col_12">
                            <?php

                            $queries = preg_replace('/[\x00-\x1F\x7F]/', ';', $item[3] );
                            $queriesSeparated = preg_split( '/([\.\?\,\;])/' , $queries );

                            $queriesFormatted = [];

                            foreach ($queriesSeparated as $query) {

                                if( !strlen ( trim( $query ) ) == 0 ){

                                    array_push( $queriesFormatted , trim( $query ) );

                                }

                            }
                            
                            ?>
                        </div>

                    <?php 
                    

                    if ( $queriesFormatted ) { ?>

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

<?php 



if( $savedPairings ){

    echo 'saved pairings';
    echo '<br>';
    
    load_template(
        $_template_file = __DIR__ . '/pairing-saved.php',
        $require_once   = true,
        $args           = array(
            'saved-pairings'  => $savedPairings
        )
    );

}