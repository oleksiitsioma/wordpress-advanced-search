<?php 

$savedPairings = $args['saved-pairings'];

function get_pairings_new(){

    global $wpdb;

    $pairings = $wpdb->get_results("
        SELECT *
        FROM blue_advanced_search_pairings
    ");

}

echo '<pre>';
print_r( get_pairings_new() );
echo '</pre>';


foreach ($savedPairings as $pairing ) {

?>




<div class="___pasTable__entry ___pasTable__entry_openable">

    <?php $postID = $pairing->post_reference; ?>

    <div class="___pasTable__entryHeader colGr">

        <div class="colGr__col_4 ___pasTable__column">
            <h2 class="___pasTable__entryTitle"><?php echo $pairing->import_block_name; ?></h2>
        </div>

        <div class="colGr__col_3 ___pasTable__column ___pasTable__column_spacedVertically">
            <p class="___pasTable__entryTitle"><?php echo $pairing->import_post_name; ?></p>
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

        <div class="colGr__col_12">
            <input type="text" name="is-block-target-<?php echo $key; ?>" value="1">
        </div>

        <div class="colGr__col_6 ___pasSearchUnit">
            <div class="___pasInputUnit">
                <label for="example-text" class="___pasInputUnit__label">Target block</label>
                <input
                    type="text"
                    class="___pasInputUnit__input"
                    id="example-text"
                    value="<?php echo $pairing->block_id; ?>"
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

                        $blockImportedValue = strtolower( preg_replace("/[^A-Za-z0-9 ]/", '', $pairing->import_block_name) );

                        if(
                            strpos( $labelEscaped , $blockImportedValue ) > -1 ||
                            strpos( $blockImportedValue , $labelEscaped ) > -1
                        ) {
                            $blockFound = true;

                            echo '<option value="' . $block['attrs']['uniqueID'] .'" selected="selected">' . $label . '</option>';

                        } else {

                            echo '<option value="' . $block['attrs']['uniqueID'] .'">' . $label . '</option>';

                        }

                    }

                    ?>

                </select>
            </div>
        </div>

        <div class="colGr__col_6 ___pasSearchUnit">
            <div class="___pasInputUnit">
                <label for="example-text" class="___pasInputUnit__label">Target block</label>
                <p><?php if( $blockFound ) { echo 'block found' ; } ?></p>
            </div>
        </div>
        <div class="colGr__col_6"></div>

        <div class="colGr__col_12">
            <?php

            
            ?>
        </div>

        <?php 
        
        $queriesFormatted = unserialize( $pairing->search_queries );
        
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

<?php } ?>