<?php 

add_submenu_page(
    $parent_slug    = $args['menu-slug'],
    $page_title     = 'Search Engines',
    $menu_title     = 'Search Engines',
    $capability     = 'administrator',
    $menu_slug      = 'search-engines',
    $function       = '___pas_admin_menu_search_engines'
);

require_once( PAS_PLUGIN_DIR . 'inc/classes/class-SearchEngine.php' );

function ___pas_admin_menu_search_engines(){ ?>


<?php

global $args;

    if( isset( $_POST['submit'] ) ){

        $engine = new SearchEngine(
            $name       = $_POST['engine-name'],
            $label      = $_POST['engine-label'],
            $importURL  = $_POST['engine-import-url']
        );

        $engine->update_db();

    }

    global $wpdb , $enginesTableName;

    $searchEnginesSQL = "SELECT * FROM {$enginesTableName}";

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

                <?php if( sizeof( $searchEngines ) < 1 ){ ?>
            
                <form class="___pasTable__entry ___pasTable__entry_openable open" action="#" method="POST">
                    <div class="___pasTable__entryHeader">
                        <h2>Add New Search Engine</h2>
                    </div>
                    <div class="___pasTable__entryBody colGr">
                        <div class="colGr__col_6 ___pasInputUnit ___pasInputUnit_engineLabel">
                            <label for="engine-label" class="___pasInputUnit__label">Engine Label</label>
                            <input
                                class="___pasInputUnit__input"
                                id="engine-label"
                                type="text"
                                name="engine-label"
                                data-content="engine-label"
                            >
                            <p class="___pasInputUnit__hint">This is the name which will appear in the dashboard</p>
                        </div>
                        <div class="colGr__col_6 ___pasInputUnit ___pasInputUnit_engineName">
                            <label for="engine-name" class="___pasInputUnit__label">Engine name</label>
                            <input
                                class="___pasInputUnit__input"
                                id="engine-name"
                                type="text"
                                name="engine-name"
                                data-content="engine-name"
                            >
                            <p class="___pasInputUnit__hint">Single word, no spaces. Underscores and dashes allowed</p>
                        </div>
                        <div class="colGr__col_12 ___pasInputUnit">
                            <label for="engine-name" class="___pasInputUnit__label">Import URL</label>
                            <input
                                class="___pasInputUnit__input"
                                id="engine-import-url"
                                type="text"
                                name="engine-import-url"
                                data-content="import-url"
                            >
                            <label for="engine-name" class="___pasInputUnit__hint">URL of the CSV Import file</label>
                        </div>
                        <div class="colGr__col_10"></div>
                        <div class="colGr__col_2">
                            <div class="___pasInputUnit">
                                <input class="___pasInputUnit__input" type="submit" name="submit" value="Submit">
                            </div>
                        </div>                        
                    </div>
                </form>

                <?php } ?>

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

        </div>

    </div>

<?php } 