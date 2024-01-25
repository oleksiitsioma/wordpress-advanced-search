<?php 

function get_pairings_new(){

    global $wpdb;

    $pairings = $wpdb->get_results("
        SELECT *
        FROM blue_advanced_search_pairings
    ");

    return $pairings;

}

$savedPairings = get_pairings_new();


foreach ( $savedPairings as $pairing ) {

?>




<form class="___wasTable__entry ___wasTable__entry_openable open" method="POST" action="#">

    <?php $postID = $pairing->post_reference; ?>

    <div class="___wasTable__entryHeader colGr">

        <div class="colGr__col_4 ___wasTable__column">
            <h2 class="___wasTable__entryTitle"><?php echo $pairing->import_block_name; ?></h2>
        </div>

        <div class="colGr__col_3 ___wasTable__column ___wasTable__column_spacedVertically">
            <p class="___wasTable__entryTitle"><?php echo $pairing->import_post_name; ?></p>
            <p class="___wasTable__entrySubtitle">
                <?php echo get_post( $postID )->post_title . ' - ' . $postID ; ?>
            </p>
        </div>

        <div class="colGr__col_3 ___wasTable__column">
            <p class="___wasTable__entrySubtitle">Total queries</p>
        </div>

        <div class="colGr__col_2 ___wasTable__column">
        </div>

    </div>

    <div class="___wasTable__entryBody colGr">

<div class="colGr__col_6 ___wasSearchUnit">
    <div class="___wasInputUnit">
        <label for="example-text" class="___wasInputUnit__label">Import post name</label>
        <input
            type="text"
            class="___wasInputUnit__input"
            value="<?php echo $pairing->import_post_name; ?>"
            name="import-post-name-<?php echo $pairing->id; ?>"
            readonly="readonly"
        >
        <p class="___wasInputUnit__hint">Choose the target WordPress post</p>
    </div>
</div> 

<div class="colGr__col_6 ___wasSearchUnit">
    <div class="___wasInputUnit">
        <label for="example-text" class="___wasInputUnit__label">Target post</label>
        <input
            type="text"
            class="___wasInputUnit__input"
            value="<?php echo $pairing->post_reference ; ?>"
            name="post-name-<?php echo $pairing->id; ?>"
            readonly="readonly"
        >
        <p class="___wasInputUnit__hint">Choose the target WordPress post</p>
    </div>
</div> 

        <div class="colGr__col_12">
            <input type="text" name="is-block-target-<?php echo $pairing->id; ?>" value="1">
        </div>

        <div class="colGr__col_6 ___wasSearchUnit">
            <div class="___wasInputUnit">
                <label for="example-text" class="___wasInputUnit__label">Target block</label>
                <input
                    type="text"
                    class="___wasInputUnit__input"
                    id="example-text"
                    value="<?php echo $pairing->block_id; ?>"
                    name=""
                    readonly="readonly"
                >
                <p class="___wasInputUnit__hint">Choose the target block within a post</p>
            </div>
        </div>

        <div class="colGr__col_6 ___wasSearchUnit">
            <div class="___wasInputUnit">
                <label for="example-text" class="___wasInputUnit__label">Inner blocks</label>
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

        <div class="colGr__col_6 ___wasSearchUnit">
            <div class="___wasInputUnit">
                <label for="example-text" class="___wasInputUnit__label">Target block</label>
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

            <div class="colGr__col_12 ___wasSearchUnit">
                <div class="___wasInputUnit">
                    <label for="tags-input" class="___wasInputUnit__label">Search Queries</label>


                    <div class="___wasInputUnit__input ___wasInputUnit__input_textarea ___wasInputUnit__tags">

                        <input type="text" class="all-queries" name="search-queries-<?php echo $pairing->id; ?>" value="<?php echo implode( '; ' , $queriesFormatted ); ?>">

                        <?php 
                        
                        foreach ( $queriesFormatted as $query ) { 
                            
                            echo '<input class="___wasInputUint__tag" value="' . $query . '"></input>';

                        }

                        ?>
                    </div>
                </div>
            </div>

        <?php } ?>

        <div class="colGr__col_12">
            <input type="submit" name="update-pairing" value="Update <?php echo $pairing->id; ?>">
        </div>





    </div>

</form>

<?php } ?>