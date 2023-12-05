<div class="___pasTable__entry ___pasTable__entry_openable">
    <div class="___pasTable__entryHeader colGr">
        <div class="colGr__col_12">
            <h2 class="___pasTable__entryTitle">Posts mapping</h2>
        </div>
    </div>
    <div class="___pasTable__entryBody">
        
        <?php
        
        load_template(
            $_template_file = PAS_PLUGIN_DIR . 'inc/menu/search-settings/mapping-saved.php',
            $require_once   = true,
            $args           = $args
        );
    
        load_template(
            $_template_file = PAS_PLUGIN_DIR . 'inc/menu/search-settings/mapping-not-saved.php',
            $require_once   = true,
            $args           = $args
        );
        
        ?>

    </div>
</div>