<?php


use FormDataManager\Model\Forms;
use MODX\Revolution\Processors\Model\GetListProcessor;

class FormDataManagerGetListProcessor extends GetListProcessor
{
    public $classKey = Forms::class;
    public $languageTopics = array('formdatamanager:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'formdatamanager.formdatamanager';
}

return 'FormDataManagerGetListProcessor';