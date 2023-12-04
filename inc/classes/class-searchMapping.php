<?php 

require_once( __DIR__ . '/../settings/plugin-vars.php');

class SearchMapping{

    public $engine_name;
    public $import_post_name;
    public $post_type;
    public $post_reference;

    function __construct( $engine_name , $import_post_name , $post_type , $post_reference ){

        $this->engine_name      = $engine_name;
        $this->import_post_name = $import_post_name;
        $this->post_type        = $post_type;
        $this->post_reference   = $post_reference;
    
    }

    public function update_db(){

        global $wpdb , $mappingTableName;
        
        $ifinDB = $wpdb->get_results("
            SELECT *
            FROM {$mappingTableName}
            WHERE import_post_name = '{$this->import_post_name}'
        ");

        if( sizeof( $ifinDB ) > 0 ){

            $wpdb->query("
                UPDATE {$mappingTableName}
                SET engine_name         = '{$this->engine_name}',
                    post_type           = '{$this->post_type}',
                    post_reference      = '{$this->post_reference}'
                WHERE import_post_name  = '{$this->import_post_name}'
            ");

        } else {

            $wpdb->insert(
    
                $table  = $mappingTableName,
                $data   = [
                    'engine_name'       => $this->engine_name,
                    'import_post_name'  => $this->import_post_name,
                    'post_type'         => $this->post_type,
                    'post_reference'    => $this->post_reference
                ],
                $format = [ '%s' ]
            
            );
        }
        
    }

}