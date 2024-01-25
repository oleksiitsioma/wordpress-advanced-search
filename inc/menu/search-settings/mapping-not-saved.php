<?php

global $mappingTableName;

$mappingsFromImportTitles   = $args['mappingsFromImportTitles'];
$postsArray                 = $args['postsArray'];
$pagesArray                 = $args['pagesArray'];
$documentsArray             = $args['documentsArray'];

?>

<form action="#" method="POST">
    <input type="submit" name="submit-mapping" value="submit">
    <?php
    
    foreach ( $mappingsFromImportTitles as $key => $mapping ) {

        $isindb = $wpdb->get_results(
            "SELECT *
            FROM {$mappingTableName}
            WHERE import_post_name = '{$mapping}'
        ");

        if( sizeof( $isindb ) < 1 ){

            $key++
        
    ?>
        <div class="colGr">
            <div class="colGr__col_3 ___wasTable__column ___wasInputUnit ___wasInputUnit_postName">
                <label class="___wasInputUnit__label">Post reference</label>
                <input
                    class="___wasInputUnit__input"
                    type="text"
                    value="<?php echo $mapping; ?>"
                    name="mapping-post-name-<?php echo $key; ?>"
                >
            </div>
            <div class="colGr__col_3 ___wasTable__column ___wasInputUnit ___wasInputUnit_postType">
                <label for="engine-label" class="___wasInputUnit__label">Post type</label>
                <select class="___wasInputUnit__input" name="mapping-post-type-<?php echo $key; ?>">
                    <?php
                    
                    $postTypes = [ 'document' , 'post' , 'page' , 'global' ];

                    foreach ($postTypes as $postType) {
                        
                        echo '<option value="' . $postType . '">' . $postType . '</option>';

                    };

                    ?>
                </select>
            </div>
            <div class="colGr__col_6 ___wasTable__column ___wasInputUnit ___wasInputUnit_postID">
                <label class="___wasInputUnit__label">Post reference</label>
                <select
                    class="___wasInputUnit__input"
                    name="mapping-post-id-<?php echo $key; ?>"
                    data-dropdown-content="posts-document"
                >
                    <?php
                        
                    echo '<option selected disabled>Select Document</option>';

                    foreach ($documentsArray as $post) {
                        
                        echo '<option value="' . $post->ID . '">' . $post->post_title . '</option>';

                    };

                    ?>
                </select>
                <select class="___wasInputUnit__input" name="mapping-post-id-<?php echo $key; ?>" data-dropdown-content="posts-post">
                    <?php
                        
                    echo '<option selected disabled>Select Post</option>';

                    foreach ($postsArray as $post) {
                        
                        echo '<option value="' . $post->ID . '">' . $post->post_title . '</option>';

                    };

                    ?>
                </select>
                <select class="___wasInputUnit__input" name="mapping-post-id-<?php echo $key; ?>" data-dropdown-content="posts-page">
                    <?php
                        
                    echo '<option selected disabled>Select Page</option>';

                    foreach ($pagesArray as $post) {
                        
                        echo '<option value="' . $post->ID . '">' . $post->post_title . '</option>';

                    };

                    ?>
                </select>   
            </div>
        </div>
    <?php } } ?>
</form>