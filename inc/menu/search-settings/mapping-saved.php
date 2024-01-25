<?php

$mappingsSQL                = $args['mappingsSQL'];
$postsArray                 = $args['postsArray'];
$pagesArray                 = $args['pagesArray'];
$documentsArray             = $args['documentsArray'];

foreach ($mappingsSQL as $mapping) {
    
?>
    <div class="colGr">
        <div class="colGr__col_3 ___wasTable__column ___wasInputUnit ___wasInputUnit_postName">
            <label class="___wasInputUnit__label">Post reference</label>
            <input
                class="___wasInputUnit__input"
                type="text"
                value="<?php echo $mapping->import_post_name; ?>"
                name="mapping-post-name"
            >
        </div>
        <div class="colGr__col_3 ___wasTable__column ___wasInputUnit ___wasInputUnit_postType">
            <label for="engine-label" class="___wasInputUnit__label">Post type</label>
            <select class="___wasInputUnit__input" name="mapping-post-type">
                <?php
                
                $postTypes = [ 'document' , 'post' , 'page' , 'global' ];

                foreach ($postTypes as $postType) {

                    if( $postType == $mapping->post_type ){
                    
                        echo '<option value="' . $postType . '" selected="selected">' . $postType . '</option>';

                    } else {
                    
                        echo '<option value="' . $postType . '">' . $postType . '</option>';

                    }

                };

                ?>
            </select>
        </div>
        <div class="colGr__col_6 ___wasTable__column ___wasInputUnit ___wasInputUnit_postID">
            <label class="___wasInputUnit__label">Post reference</label>
            <select
                class="___wasInputUnit__input"
                name="mapping-post-id"
                data-dropdown-content="posts-document"
            >
                <?php
                    
                echo '<option disabled>Select Document</option>';

                foreach ($documentsArray as $post) {

                    if( $post->ID == $mapping->post_reference ){
                    
                        echo '<option value="' . $post->ID . '" selected="selected">' . $post->post_title . '</option>';

                    } else {
                    
                        echo '<option value="' . $post->ID . '">' . $post->post_title . '</option>';

                    }

                };

                ?>
            </select>
            <select class="___wasInputUnit__input" name="mapping-post-id" data-dropdown-content="posts-post">
                <?php
                    
                echo '<option disabled>Select Post</option>';

                foreach ($postsArray as $post) {

                    if( $post->ID == $mapping->post_reference ){
                    
                        echo '<option value="' . $post->ID . '" selected="selected">' . $post->post_title . '</option>';

                    } else {
                    
                        echo '<option value="' . $post->ID . '">' . $post->post_title . '</option>';

                    }

                };

                ?>
            </select>
            <select class="___wasInputUnit__input" name="mapping-post-id" data-dropdown-content="posts-page">
                <?php
                    
                echo '<option disabled>Select Page</option>';

                foreach ($pagesArray as $post) {

                    if( $post->ID == $mapping->post_reference ){
                    
                        echo '<option value="' . $post->ID . '" selected="selected">' . $post->post_title . '</option>';

                    } else {
                    
                        echo '<option value="' . $post->ID . '">' . $post->post_title . '</option>';

                    }

                };

                ?>
            </select>   
        </div>
    </div>
<?php }