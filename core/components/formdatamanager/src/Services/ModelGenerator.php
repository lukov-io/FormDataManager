<?php

namespace FormDataManager\Services;

use FormDataManager\Model\Forms;
use MODX\Revolution\modX;
use xPDO\Om\xPDOManager;
use xPDO\Om\xPDOSimpleObject;

class ModelGenerator
{
    const NAMESPACE = "FormDataManager\\Model\\";
    public xPDOManager $manager;
    private string $modelPath;
    private array $classMap;
    public modX $modx;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
        $this->manager = $modx->getManager();
        $this->modelPath = $modx->getOption('formdatamanager.core_path',null,$modx->getOption('core_path').'components/formdatamanager/') . "src/";

        $this->classMap = [
            'Handlers' => [
                "extends" => xPDOSimpleObject::class
            ],
            'Forms' => [
                "extends" => xPDOSimpleObject::class
            ],
            'FormsHandlers' => [
                "extends" => xPDOSimpleObject::class
            ],
        ];

        foreach ($modx->getCollection(Forms::class) as $form) {
            $className = $form->get("formName");
            $this->classMap[$className] = ["extends" => xPDOSimpleObject::class];
        }
    }

    public function process(array $object) {
        $class = (string) $object['class'];
        if ($this->modx->getObject(Forms::class, ['formName'=> $class])) {
            return false;
        }

        if(!preg_match("/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_ \x80-\xff]*$/", $class)) {
            return false;
        }

        $generator = $this->manager->getGenerator();
        $newModel = $this->modx->newObject(Forms::class);
        $modelFields = [];

        $generator->model = [
            "package" => "FormDataManager",
            "baseClass" => "xPDO\Om\xPDOSimpleObject",
            "platform" => "mysql",
            "defaultEngine" => "InnoDB",
            "version" => "3.0",
            "namespace" => "FormDataManager\Model",
        ];

        $newModel->set('formName', $class);
        $class = str_replace(" ", '_', $class);

        $extends = $generator->model['baseClass'];
        $generator->classes[$class] = ['extends' => $extends];
        $generator->map[$class] = [
            'package' => $generator->model['package'],
            'version' => $generator->model['version'],
            'table' => $object['table'],
            'tableMeta' => [
                'engine' => $generator->model['defaultEngine']
            ]
        ];
        $generator->map[$class]['fields'] = [
            'createdAt' => NULL,
            'status' => 0,
        ];
        $generator->map[$class]['fieldMeta'] = [
            'createdAt' => [
                'dbtype' => 'timestamp',
                'phptype' => 'date',
                'null' => true,
                'attributes' => 'DEFAULT CURRENT_TIMESTAMP',
            ],
            'status' => [
                'dbtype' => 'int',
                'precision' => '1',
                'phptype' => 'integer',
                'null' => true,
                'default' => 0,
            ],
        ];

        $generator->map[$class]['indexes'] = [
            'createdAt' => [
                'alias' => 'createdAt',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => [
                    'createdAt' => [
                        'length' => '',
                        'collation' => 'A',
                        'null' => true,
                    ],
                ],
            ],
            'status' => [
                'alias' => 'status',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => [
                    'status' => [
                        'length' => '',
                        'collation' => 'A',
                        'null' => true,
                    ],
                ],
            ],
        ];

        foreach ($object["fields"] as $key=>$field) {
            $modelFields[] = $field;
            $generator->map[$class]['fields'][$field] = null;
            $generator->map[$class]['fieldMeta'][$field] = $object['fieldMeta'][$key];
        }

        $generator->outputClasses($this->modelPath, 0, 0, "FormDataManager");

        $generator->classes = array_merge($generator->classes, $this->classMap);
        $generator->outputMeta($this->modelPath, "FormDataManager");

        if ($this->manager->createObjectContainer($generator->model['namespace'] . "\\$class")) {
            $newModel->set('fields', implode(",", $modelFields));
            $this->classMap[$class] = ["extends" => xPDOSimpleObject::class];
            return $newModel;
        }

        unlink($this->modelPath . "Model/" . $class . ".php");
        unlink($this->modelPath . "Model/mysql/" . $class . ".php");
        return false;
    }
}