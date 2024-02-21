<?php
namespace FormDataManager\Model\mysql;

use xPDO\xPDO;

class immigration_lawyer extends \FormDataManager\Model\immigration_lawyer
{

    public static $metaMap = array (
        'package' => 'FormDataManager',
        'version' => '3.0',
        'table' => 'immigration lawyer',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'createdAt' => NULL,
            'status' => 0,
            'comment1' => NULL,
        ),
        'fieldMeta' => 
        array (
            'createdAt' => 
            array (
                'dbtype' => 'timestamp',
                'phptype' => 'date',
                'null' => true,
                'attributes' => 'DEFAULT CURRENT_TIMESTAMP',
            ),
            'status' => 
            array (
                'dbtype' => 'int',
                'precision' => '1',
                'phptype' => 'integer',
                'null' => true,
                'default' => 0,
            ),
            'comment1' => 
            array (
                'dbtype' => 'TINYTEXT',
                'phptype' => 'string',
            ),
        ),
        'indexes' => 
        array (
            'createdAt' => 
            array (
                'alias' => 'createdAt',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'createdAt' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => true,
                    ),
                ),
            ),
            'status' => 
            array (
                'alias' => 'status',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'status' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => true,
                    ),
                ),
            ),
        ),
    );

}
