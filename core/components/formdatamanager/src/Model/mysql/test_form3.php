<?php
namespace FormDataManager\Model\mysql;

use xPDO\xPDO;

class test_form3 extends \FormDataManager\Model\test_form3
{

    public static $metaMap = array (
        'package' => 'FormDataManager',
        'version' => '3.0',
        'table' => 'test_form3',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'test1' => NULL,
            'test2' => NULL,
            'test3' => NULL,
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
                'dbtype' => 'TIMESTAMP',
                'phptype' => 'date',
            ),
            'test3' => 
            array (
                'dbtype' => 'INT',
                'phptype' => '',
            ),
        ),
    );

}
