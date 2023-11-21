<?php
namespace FormDataManager\Model\mysql;

use xPDO\xPDO;

class Handlers extends \FormDataManager\Model\Handlers
{

    public static $metaMap = array (
        'package' => 'FormDataManager\\Model',
        'version' => '3.0',
        'table' => 'form_data_manager_handlers',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'name' => NULL,
            'className' => NULL,
        ),
        'fieldMeta' => 
        array (
            'name' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
            ),
            'className' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '355',
                'phptype' => 'string',
                'null' => false,
            ),
        ),
        'composites' => 
        array (
            'FormsHandlers' => 
            array (
                'class' => 'FormDataManager\\Model\\FormsHandlers',
                'local' => 'id',
                'foreign' => 'handler',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
    );

}
