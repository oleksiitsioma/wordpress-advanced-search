<div class="___wasTable__entry ___wasTable__entry_openable">
    <div class="___wasTable__entryHeader colGr">
        <div class="colGr__col_12">
            <h2 class="___wasTable__entryTitle">Posts mapping</h2>
        </div>
    </div>
    <div class="___wasTable__entryBody">
        
        <?php
        
        load_template(
            $_template_file = WAS_PLUGIN_DIR . 'inc/menu/search-settings/mapping-saved.php',
            $require_once   = true,
            $args           = $args
        );
    
        load_template(
            $_template_file = WAS_PLUGIN_DIR . 'inc/menu/search-settings/mapping-not-saved.php',
            $require_once   = true,
            $args           = $args
        );
        
        ?>

    </div>
</div>