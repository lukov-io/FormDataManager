<?php

namespace FormDataManager\Generator;

use FormDataManager\Model\Forms;
use MODX\Revolution\modX;
use xPDO\Om\xPDOManager;

class ModelGenerator
{
    const NAMESPACE = "FormDataManager\\Model\\";
    public xPDOManager $manager;
    private string $modelPath;
    private array $classMap;
    public modX $modx;

    public function __construct(modX $modx, $currentClassMap = [])
    {
        $this->modx = $modx;
        $this->classMap = $currentClassMap;
        $this->manager = $modx->getManager();
        $this->modelPath = $modx->getOption('formdatamanager.core_path',null,$modx->getOption('core_path').'components/formdatamanager/') . "src/";
    }

    public function process() {

    }

    public function createModelClass(array $object)
    {
        $class = (string) $object['attributes']['class'];

        if ($this->modx->getObject(Forms::class, ['formName'=> $class])) {
            return false;
        }

        if(!preg_match("/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/", $class)) {
            return false;
        }

        if (!$object['field']) {
            return false;
        }

        $generator = $this->manager->getGenerator();
        $newModel = $this->modx->newObject(Forms::class);
        $modelFields = [];
        $outputDir = $this->modelPath;
        $generator->model = [
            "package" => "FormDataManager",
            "baseClass" => "xPDO\Om\xPDOSimpleObject",
            "platform" => "mysql",
            "defaultEngine" => "InnoDB",
            "version" => "3.0",
            "namespace" => "FormDataManager\Model",
        ];

        $newModel->set('formName', $class);
        $extends = $generator->model['baseClass'];
        $generator->classes[$class] = array('extends' => $extends);
        $generator->map[$class] = array(
            'package' => $generator->model['package'],
            'version' => $generator->model['version']
        );

        foreach ($object['attributes'] as $objAttrKey => $objAttr) {
            if ($objAttrKey == 'class') continue;
            $generator->map[$class][$objAttrKey]= (string) $objAttr;
            if (!in_array($objAttrKey, array('package', 'version', 'extends', 'table'))) {
                $generator->classes[$class][$objAttrKey] = (string) $objAttr;
            }
        }

        $engine = isset($object['engine']) ? (string) $object['engine'] : $generator->model['defaultEngine'];
        if (!empty($engine)) {
            $generator->map[$class]['tableMeta'] = array('engine' => $engine);
        }

        $generator->map[$class]['fields']= array();
        $generator->map[$class]['fieldMeta']= array();

        foreach ($object['field'] as $field) {
            $key = (string) $field['attributes']['key'];
            $modelFields[] = $key;
            $dbtype = (string) $field['attributes']['dbtype'];
            $defaultType = $generator->manager->xpdo->driver->getPhpType($dbtype);
            $generator->map[$class]['fields'][$key]= null;
            $generator->map[$class]['fieldMeta'][$key]= array();
            foreach ($field['attributes'] as $fldAttrKey => $fldAttr) {
                $fldAttrValue = (string) $fldAttr;
                switch ($fldAttrKey) {
                    case 'key':
                        continue 2;
                    case 'default':
                        if ($fldAttrValue === 'NULL') {
                            $fldAttrValue = null;
                        }
                        switch ($defaultType) {
                            case 'integer':
                            case 'boolean':
                            case 'bit':
                                $fldAttrValue = (integer) $fldAttrValue;
                                break;
                            case 'float':
                            case 'numeric':
                                $fldAttrValue = (float) $fldAttrValue;
                                break;
                            default:
                                break;
                        }
                        $generator->map[$class]['fields'][$key]= $fldAttrValue;
                        break;
                    case 'null':
                        $fldAttrValue = (!empty($fldAttrValue) && strtolower($fldAttrValue) !== 'false') ? true : false;
                        break;
                    default:
                        break;
                }
                $generator->map[$class]['fieldMeta'][$key][$fldAttrKey]= $fldAttrValue;
            }
        }

        if (isset($object['alias'])) {
            $generator->map[$class]['fieldAliases'] = array();
            foreach ($object['alias'] as $alias) {
                $aliasKey = (string) $alias['key'];
                $aliasNode = array();
                foreach ($alias['attributes'] as $attrName => $attr) {
                    $attrValue = (string) $attr;
                    switch ($attrName) {
                        case 'key':
                            continue 2;
                        case 'field':
                            $aliasNode = $attrValue;
                            break;
                        default:
                            break;
                    }
                }
                if (!empty($aliasKey) && !empty($aliasNode)) {
                    $generator->map[$class]['fieldAliases'][$aliasKey] = $aliasNode;
                }
            }
        }
        if (isset($object['index'])) {
            $generator->map[$class]['indexes'] = array();
            foreach ($object['index'] as $index) {
                $indexNode = array();
                $indexName = (string) $index['name'];
                foreach ($index['attributes'] as $attrName => $attr) {
                    $attrValue = (string) $attr;
                    switch ($attrName) {
                        case 'name':
                            continue 2;
                        case 'primary':
                        case 'unique':
                        case 'fulltext':
                            $attrValue = (empty($attrValue) || $attrValue === 'false' ? false : true);
                        default:
                            $indexNode[$attrName] = $attrValue;
                            break;
                    }
                }
                if (!empty($indexNode) && isset($index->column)) {
                    $indexNode['columns']= array();
                    foreach ($index['column'] as $column) {
                        $columnKey = (string) $column['key'];
                        $indexNode['columns'][$columnKey] = array();
                        foreach ($column['attributes'] as $attrName => $attr) {
                            $attrValue = (string) $attr;
                            switch ($attrName) {
                                case 'key':
                                    continue 2;
                                case 'null':
                                    $attrValue = (empty($attrValue) || $attrValue === 'false' ? false : true);
                                default:
                                    $indexNode['columns'][$columnKey][$attrName]= $attrValue;
                                    break;
                            }
                        }
                    }
                    if (!empty($indexNode['columns'])) {
                        $generator->map[$class]['indexes'][$indexName]= $indexNode;
                    }
                }
            }
        }
        /*if (isset($object->composite)) {
            $this->map[$class]['composites'] = array();
            foreach ($object->composite as $composite) {
                $compositeNode = array();
                $compositeAlias = (string) $composite['alias'];
                foreach ($composite->attributes() as $attrName => $attr) {
                    $attrValue = (string) $attr;
                    switch ($attrName) {
                        case 'alias' :
                            continue 2;
                        case 'criteria' :
                            $attrValue = $this->manager->xpdo->fromJSON(urldecode($attrValue));
                        default :
                            $compositeNode[$attrName]= $attrValue;
                            break;
                    }
                }
                if (!empty($compositeNode)) {
                    if (isset($composite->criteria)) {
                        foreach ($composite->criteria as $criteria) {
                            $criteriaTarget = (string) $criteria['target'];
                            $expression = (string) $criteria;
                            if (!empty($expression)) {
                                $expression = $this->manager->xpdo->fromJSON($expression);
                                if (!empty($expression)) {
                                    if (!isset($compositeNode['criteria'])) $compositeNode['criteria'] = array();
                                    if (!isset($compositeNode['criteria'][$criteriaTarget])) $compositeNode['criteria'][$criteriaTarget] = array();
                                    $compositeNode['criteria'][$criteriaTarget] = array_merge($compositeNode['criteria'][$criteriaTarget], (array) $expression);
                                }
                            }
                        }
                    }
                    $this->map[$class]['composites'][$compositeAlias] = $compositeNode;
                }
            }
        }*/
        /*if (isset($object->aggregate)) {
            $this->map[$class]['aggregates'] = array();
            foreach ($object->aggregate as $aggregate) {
                $aggregateNode = array();
                $aggregateAlias = (string) $aggregate['alias'];
                foreach ($aggregate->attributes() as $attrName => $attr) {
                    $attrValue = (string) $attr;
                    switch ($attrName) {
                        case 'alias' :
                            continue 2;
                        case 'criteria' :
                            $attrValue = $this->manager->xpdo->fromJSON(urldecode($attrValue));
                        default :
                            $aggregateNode[$attrName]= $attrValue;
                            break;
                    }
                }
                if (!empty($aggregateNode)) {
                    if (isset($aggregate->criteria)) {
                        foreach ($aggregate->criteria as $criteria) {
                            $criteriaTarget = (string) $criteria['target'];
                            $expression = (string) $criteria;
                            if (!empty($expression)) {
                                $expression = $this->manager->xpdo->fromJSON($expression);
                                if (!empty($expression)) {
                                    if (!isset($aggregateNode['criteria'])) $aggregateNode['criteria'] = array();
                                    if (!isset($aggregateNode['criteria'][$criteriaTarget])) $aggregateNode['criteria'][$criteriaTarget] = array();
                                    $aggregateNode['criteria'][$criteriaTarget] = array_merge($aggregateNode['criteria'][$criteriaTarget], (array) $expression);
                                }
                            }
                        }
                    }
                    $this->map[$class]['aggregates'][$aggregateAlias] = $aggregateNode;
                }
            }
        }*/
        if (isset($object['validation'])) {
            $generator->map[$class]['validation'] = array();
            $validation = $object['validation'][0];
            $validationNode = array();
            foreach ($validation['attributes'] as $attrName => $attr) {
                $validationNode[$attrName]= (string) $attr;
            }
            if (isset($validation['rule'])) {
                $validationNode['rules'] = array();
                foreach ($validation['rule'] as $rule) {
                    $ruleNode = array();
                    $field= (string) $rule['field'];
                    $name= (string) $rule['name'];
                    foreach ($rule['attributes'] as $attrName => $attr) {
                        $attrValue = (string) $attr;
                        switch ($attrName) {
                            case 'field' :
                            case 'name' :
                                continue 2;
                            default :
                                $ruleNode[$attrName]= $attrValue;
                                break;
                        }
                    }
                    if (!empty($field) && !empty($name) && !empty($ruleNode)) {
                        $validationNode['rules'][$field][$name]= $ruleNode;
                    }
                }
                if (!empty($validationNode['rules'])) {
                    $generator->map[$class]['validation'] = $validationNode;
                }
            }
        }

        $path= !empty($outputDir) ? $outputDir : 'model/';
        //$generator->outputMeta($path, "FormDataManager");
        $generator->outputClasses($path, 0, 0, "FormDataManager");

        if ($this->manager->createObjectContainer($generator->model['namespace'] . "\\$class")) {
            $newModel->set('fields', implode(",", $modelFields));
            return $newModel;
        }

        unlink($outputDir . "Model/" . $class . ".php");
        unlink($outputDir . "Model/mysql/" . $class . ".php");
        return false;
    }
}