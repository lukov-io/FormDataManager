<?php
namespace FormDataManager\Model\mysql;

use xPDO\xPDO;

class FormsHandlers extends \FormDataManager\Model\FormsHandlers
{

    public static $metaMap = array (
        'package' => 'FormDataManager\\Model',
        'version' => '3.0',
        'table' => 'form_data_manager_links',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'form' => NULL,
            'handler' => NULL,
        ),
        'fieldMeta' => 
        array (
            'form' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
            ),
            'handler' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
            ),
        ),
        'aggregates' => 
        array (
            'Handlers' => 
            array (
                'class' => 'FormDataManager\\Model\\Handlers',
                'local' => 'handler',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'Forms' => 
            array (
                'class' => 'FormDataManager\\Model\\Forms',
                'local' => 'form',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
