<?php

namespace FormDataManager\Handler\Interfaces;

use xPDO\Om\xPDOSimpleObject;

interface HandlerInterface
{
    public function run(xPDOSimpleObject $object);
}