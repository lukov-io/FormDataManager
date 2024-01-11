<?php

use MODX\Revolution\Processors\Model\GetListProcessor;
class FormsGetListProcessor extends GetListProcessor {
    const NAMESPACE = "FormDataManager\\Model\\";
    public $classKey = '';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';

    public function initialize(): bool
    {
        $className = str_replace(" ", "_", $this->getProperty('class'));

        $this->classKey = self::NAMESPACE . $className;

        return parent::initialize();
    }

    public function afterIteration(array $list): array
    {
        $this->prepareOutputStatus($list);
        return $list;
    }

    /**
     * @throws Exception
     */
    public function prepareQueryBeforeCount(xPDOQuery $c): xPDOQuery
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $query = json_decode($query);

            $queryArg = [];

            if (isset($query->from)) {
                $dateStart = new DateTime($query->from);
                $queryArg['createdAt:>='] = $dateStart->format("Y-m-d H:i:s");
            }

            if (isset($query->to)) {
                $dateTo = new DateTime($query->to);
                $queryArg['createdAt:<='] = $dateTo->format("Y-m-d H:i:s");
            }
            $c->where($queryArg);
        }

        return $c;
    }

    private function prepareOutputStatus(array &$array): void
    {
        $count = count($array);

        for ($i = 0; $i < $count; $i++) {
            if ($array[$i]["status"] === 0) {
                $array[$i]["status"] = "Not delivered!";
            } else {
                $array[$i]["status"] = "Delivered";
            }
        }
    }
}
return 'FormsGetListProcessor';