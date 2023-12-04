<?php

require_once( __DIR__ . '/../settings/plugin-vars.php');

class SearchEngine {

    public $name;
    public $label;
    public $importURL;

    function __construct( $name , $label , $importURL ){

        $this->name         = $name;
        $this->label        = $label;
        $this->importURL    = $importURL; 

    }

    public function update_db(){
        
        global $wpdb , $enginesTableName , $parentMenuSlug;
        
        $ifinDB = $wpdb->get_results("
            SELECT *
            FROM {$enginesTableName}
            WHERE engine_name = '{$this->name}'
        ");

        if( sizeof( $ifinDB ) > 0 ){

            $wpdb->query("
                UPDATE {$enginesTableName}
                SET engine_label    = {$this->label},
                    import_url      = {$this->importURL}
                WHERE engine_name   = {$this->name}
            ");

        } else {

            $wpdb->insert(
    
                $table  = $enginesTableName,
                $data   = [
                    'engine_label'  => $this->label,
                    'engine_name'   => $this->name,
                    'import_url'    => $this->importURL
                ],
                $format = [ '%s' ]
            
            );

        }

    }

}