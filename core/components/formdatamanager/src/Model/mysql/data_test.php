<?php
namespace FormDataManager\Model\mysql;

use xPDO\xPDO;

class data_test extends \FormDataManager\Model\data_test
{

    public static $metaMap = array (
        'package' => 'FormDataManager',
        'version' => '3.0',
        'table' => 'data_test',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'test1' => NULL,
            'test2' => NULL,
        ),
        'fieldMeta' => 
        array (
            'test1' => 
            array (
                'dbtype' => 'TIMESTAMP',
                'phptype' => 'date',
            ),
            'test2' => 
            array (
                'dbtype' => 'TEXT',
                'phptype' => 'string',
            ),
        ),
    );

}
