<?php 

add_submenu_page(
    $parent_slug    = $args['menu-slug'],
    $page_title     = 'Search Engines',
    $menu_title     = 'Search Engines',
    $capability     = 'administrator',
    $menu_slug      = 'search-engines',
    $function       = '___was_admin_menu_search_engines'
);

require_once( WAS_PLUGIN_DIR . 'inc/classes/class-SearchEngine.php' );

function ___was_admin_menu_search_engines(){ ?>


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
    <div class="___was">

        <div class="container">
            <h1 class="___was__title">Search Engines</h1>
        </div>
    
        <div class="container">

            <div class="___wasTable">

                <div class="___wasTable__entry ___wasTable__header colGr">
                    <div class="colGr__col_3 ___wasTable__column ___wasTable__column_header">Label</div>
                    <div class="colGr__col_3 ___wasTable__column ___wasTable__column_header">Imported File URL</div>
                </div>

                <?php if( sizeof( $searchEngines ) < 1 ){ ?>
            
                <form class="___wasTable__entry ___wasTable__entry_openable open" action="#" method="POST">
                    <div class="___wasTable__entryHeader">
                        <h2>Add New Search Engine</h2>
                    </div>
                    <div class="___wasTable__entryBody colGr">
                        <div class="colGr__col_6 ___wasInputUnit ___wasInputUnit_engineLabel">
                            <label for="engine-label" class="___wasInputUnit__label">Engine Label</label>
                            <input
                                class="___wasInputUnit__input"
                                id="engine-label"
                                type="text"
                                name="engine-label"
                                data-content="engine-label"
                            >
                            <p class="___wasInputUnit__hint">This is the name which will appear in the dashboard</p>
                        </div>
                        <div class="colGr__col_6 ___wasInputUnit ___wasInputUnit_engineName">
                            <label for="engine-name" class="___wasInputUnit__label">Engine name</label>
                            <input
                                class="___wasInputUnit__input"
                                id="engine-name"
                                type="text"
                                name="engine-name"
                                data-content="engine-name"
                            >
                            <p class="___wasInputUnit__hint">Single word, no spaces. Underscores and dashes allowed</p>
                        </div>
                        <div class="colGr__col_12 ___wasInputUnit">
                            <label for="engine-name" class="___wasInputUnit__label">Import URL</label>
                            <input
                                class="___wasInputUnit__input"
                                id="engine-import-url"
                                type="text"
                                name="engine-import-url"
                                data-content="import-url"
                            >
                            <label for="engine-name" class="___wasInputUnit__hint">URL of the CSV Import file</label>
                        </div>
                        <div class="colGr__col_10"></div>
                        <div class="colGr__col_2">
                            <div class="___wasInputUnit">
                                <input class="___wasInputUnit__input" type="submit" name="submit" value="Submit">
                            </div>
                        </div>                        
                    </div>
                </form>

                <?php } ?>

                <?php foreach ( $searchEngines as $engine ) { ?>

                    <div class="___wasTable__entry ___wasTable__entry_openable">
                        <div class="___wasTable__entryHeader colGr">
                            <div class="colGr__col_3 ___wasTable__column">
                                <h2 class="___wasTable__entryTitle"><?php echo $engine->engine_label; ?></h2>
                                <p class="___wasTable__entrySubtitle"><?php echo $engine->engine_name; ?></p>
                            </div>
                            <div class="colGr__col_3 ___wasTable__column">
                                <?php echo $engine->import_url; ?>
                            </div>
                        </div>
                        <div class="___wasTable__entryBody colGr">

                            <div class="colGr__col_6 ___wasSearchUnit">
                                <div class="___wasInputUnit">
                                    <label for="example-text" class="___wasInputUnit__label">Import Url</label>
                                    <p class="___wasInputUnit__hint"><?php echo $engine->import_url; ?></p>
                                </div>
                            </div>

                        </div>
                    </div>
                
                <?php } ?>
                    
            </div>

        </div>

    </div>

<?php } 