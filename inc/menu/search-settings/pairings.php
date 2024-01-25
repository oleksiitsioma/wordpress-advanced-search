<?php 

$mappingsFromImport = $args['mappingsFromImport'];
$mappingsSQL        = $args['mappingsSQL'];
$postsArray         = $args['postsArray'];
$pagesArray         = $args['pagesArray'];
$documentsArray     = $args['documentsArray'];

$savedPairings = get_pairings();

load_template(
    $_template_file = WAS_PLUGIN_DIR . 'inc/menu/search-settings/pairing-not-saved.php',
    $require_once   = true,
    $args           = array(
        'mappings-from-import'  => $mappingsFromImport,
        'mappings-sql'          => $mappingsSQL,
        'posts-array'           => $postsArray,
        'pages-array'           => $pagesArray,
        'documents-array'       => $documentsArray

    )
);




echo 'saved pairings';
echo '<br>';

load_template(
    $_template_file = WAS_PLUGIN_DIR . 'inc/menu/search-settings/pairing-saved.php',
    $require_once   = true,
    $args           = array(
        'saved-pairings'  => $savedPairings
    )
);
