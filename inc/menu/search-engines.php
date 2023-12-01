<?php 

add_submenu_page(
    $parent_slug    = $args['menu-slug'],
    $page_title     = 'Search Engines',
    $menu_title     = 'Search Engines',
    $capability     = 'administrator',
    $menu_slug      = 'search-engines',
    $function       = '___pas_admin_menu_search_engines'
);

function addSearchToDB(){
    
    global $wpdb;

    $wpdb->insert(

        $table  = $wpdb->prefix . 'advanced_search_engines',
        $data   = [
            'engine_label'  => $_POST['engine-label'],
            'engine_name'  => $_POST['engine-name'],
            'import_url'    => $_POST['import-url']
        ],
        $format = [ '%s' ]
    );

}

function ___pas_admin_menu_search_engines(){ ?>


<?php

    if( isset( $_POST['submit'] ) ){

        addSearchToDB();

    }

    global $wpdb;

    $searchEnginesTable = $wpdb->prefix . 'advanced_search_engines';

    $searchEnginesSQL = "SELECT * FROM {$searchEnginesTable}";

    $searchEngines = $wpdb->get_results( $searchEnginesSQL );
    
    ?>
    <div class="___pas">

        <div class="container">
            <h1 class="___pas__title">Search Engines</h1>
        </div>
    
        <div class="container">

            <div class="___pasTable">

                <div class="___pasTable__entry ___pasTable__header colGr">
                    <div class="colGr__col_3 ___pasTable__column ___pasTable__column_header">Label</div>
                    <div class="colGr__col_3 ___pasTable__column ___pasTable__column_header">Imported File URL</div>
                </div>

                <?php foreach ( $searchEngines as $engine ) { ?>

                    <div class="___pasTable__entry ___pasTable__entry_openable">
                        <div class="___pasTable__entryHeader colGr">
                            <div class="colGr__col_3 ___pasTable__column">
                                <h2 class="___pasTable__entryTitle"><?php echo $engine->engine_label; ?></h2>
                                <p class="___pasTable__entrySubtitle"><?php echo $engine->engine_name; ?></p>
                            </div>
                            <div class="colGr__col_3 ___pasTable__column">
                                <?php echo $engine->import_url; ?>
                            </div>
                        </div>
                        <div class="___pasTable__entryBody colGr">

                            <div class="colGr__col_6 ___pasSearchUnit">
                                <div class="___pasInputUnit">
                                    <label for="example-text" class="___pasInputUnit__label">Import Url</label>
                                    <p class="___pasInputUnit__hint"><?php echo $engine->import_url; ?></p>
                                </div>
                            </div>

                        </div>
                    </div>
                
                <?php } ?>
                    
            </div>

            <form action="#" method="POST">
                <input type="text" name="engine-name" placeholder="Engine name">
                <br>
                <input type="text" name="engine-label" placeholder="Engine Label">
                <br>
                <input type="text" name="import-url" placeholder="Import URL">
                <br>
                <input type="submit" value="Submit" name="submit">
            </form>

        </div>

    </div>

<?php } 