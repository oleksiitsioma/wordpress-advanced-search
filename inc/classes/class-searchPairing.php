<?php

require_once( PAS_PLUGIN_DIR . 'inc/settings/plugin-vars.php');

class SearchPairing {

    public $engine_name;
    public $import_post_name;
    public $post_reference;
    public $is_block_level;
    public $import_block_name;
    public $block_label;
    public $searchQueries;

    function __construct(
        $engine_name,
        $import_post_name,
        $post_reference,
        $is_block_level,
        $searchQueries
    ){
        
        $this->engine_name          = $engine_name;
        $this->import_post_name     = $import_post_name;
        $this->post_reference       = $post_reference;
        $this->is_block_level       = $is_block_level;
        $this->searchQueries        = $searchQueries;
    
    }

    public function update_db(){

        global $wpdb , $pairingsTableName;

        $ifinDB = $wpdb->get_results("
            SELECT *
            FROM {$pairingsTableName}
            WHERE import_block_name = '{$this->import_block_name}'
        ");

        if( sizeof( $ifinDB ) > 0 ){

            $wpdb->query("
                UPDATE {$pairingsTableName}
                SET engine_name         = '{$this->engine_name}',
                    post_reference      = '{$this->post_reference}',
                    is_block_level      = '{$this->is_block_level}',
                    import_post_name    = '{$this->import_block_name}',
                    block_id            = '{$this->block_label}',
                    search_queries      = '{$this->searchQueries}'
                WHERE import_block_name = '{$this->import_block_name}'
            ");

        } else {

            $wpdb->insert(

                $table  = $pairingsTableName,
                $data   = [
                    'engine_name'       => $this->engine_name,
                    'post_reference'    => $this->post_reference,
                    'import_post_name'  => $this->import_post_name,
                    'is_block_level'    => $this->is_block_level,
                    'import_block_name' => $this->import_block_name,
                    'block_id'          => $this->block_label,
                    'search_queries'    => $this->searchQueries,
                ]

            );
        
        }

    }

    public function set_import_block_name( $import_block_name ){
        $this->import_block_name = $import_block_name;
    }

    public function set_block_label( $block_label ){
        $this->block_label = $block_label;
    }

}