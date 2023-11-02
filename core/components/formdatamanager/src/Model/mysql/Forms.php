<?php
namespace FormDataManager\Model\mysql;

use xPDO\xPDO;

class Forms extends \FormDataManager\Model\Forms
{

    public static $metaMap = array (
        'package' => 'FormDataManager\\Model',
        'version' => '3.0',
        'table' => 'form_data_manager_forms',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'formName' => NULL,
            'fields' => NULL,
            'handler' => NULL,
        ),
        'fieldMeta' => 
        array (
            'formName' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
            ),
            'fields' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '355',
                'phptype' => 'string',
                'null' => false,
            ),
            'handler' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => true,
            ),
        ),
    );

}
